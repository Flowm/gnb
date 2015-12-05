<?php

require_once __DIR__."/../resource_mappings.php";
require_once getPageAbsolute("db_functions");
require_once getPageAbsolute("mail");
require_once getPageAbsolute("account");
require_once getPageAbsolute("fpdf_protection");

class user {
    public $id;
    public $email;
    public $firstname;
    public $lastname;
    public $status;
    public $role;
    public $approved_by;
    public $password;
    public $accounts;
    public $auth_device;
    public $pin;

    public function __construct($data) {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        if (isset($data['email'])) {
            $this->email = $data['email'];
        }
        if (isset($data['first_name'])) {
            $this->firstname = $data['first_name'];
        }
        if (isset($data['last_name'])) {
            $this->lastname = $data['last_name'];
        }
        if (isset($data['pw_hash'])) {
            $this->password = $data['pw_hash'];
        }
        if (isset($data['status'])) {
            $this->status = $data['status'];
        }
        if (isset($data['role'])) {
            $this->role = $data['role'];
        }
        if (isset($data[DB::i()->USER_TABLE_PIN])) {
            $this->pin = $data[DB::i()->USER_TABLE_PIN];
        }
        if (isset($data[DB::i()->USER_TABLE_AUTHDEV])) {
            $this->auth_device = $data[DB::i()->USER_TABLE_AUTHDEV];
        }
        $this->accounts = array();
        if (isset($data['accounts'])) {
            //SET ACCOUNTS
        }
    }

    public function setAccounts($data) {
        for ($i=0; $i<count($data); $i++) {
            $element = $data[$i];
            $account = new account($element);
            array_push($this->accounts, $account);
        }
    }

    public function getBasicInfo() {
        return array('id'=>$this->id,
            'email'=>$this->email,
            'firstname'=>$this->firstname,
            'lastname'=>$this->lastname,	
            'status'=>$this->status,
            'role'=>$this->role);
    }
	
	 

	public function approve($approver_id) {
		if ($this->role == '0') {
			if (DB::i()->approveClient($this->id, $approver_id)) {
				$balance = rand(100,1000);
				$account_id = DB::i()->addAccountWithBalance($this->id, $balance);
				$account = new account(array('id'=>$account_id));     
				
				$name = "$this->firstname $this->lastname";
				$gnbmailer = new GNBMailer();
				
				# Check if User uses TANs or SCS
				$user_uses_tan	= true ; 

				# if User uses TANs generate and attach PDF
				if ( $user_uses_tan ){
					
					$tans 		= $account->generateTANs(); 
					#Enter user PIN 
					#$pass		= 
					$user_pass	= '909090' ;
					$pdf_pass	= hash('sha512', 'HaveYouMetTed' . $user_pass . '6');
					
					$tanpdffile	= $this->generateTanPDF($tans,$pdf_pass) ;
					
					return $gnbmailer->sendMail_Approval($this->email, $name, $balance, $tanpdffile);
					
				# if User uses SCS dont sent PDF 
				} else {
					return $gnbmailer->sendMail_Approval($this->email, $name, $balance );
				}
				
			}
		} else if ($this->role == '1') {
			if (DB::i()->approveEmployee($this->id, $approver_id)) {
				$name = "$this->firstname $this->lastname";
				$gnbmailer = new GNBMailer();
				return $gnbmailer->sendMail_Approval($this->email, $name);
			}
		}
		//TODO: SHOW ERROR MESSAGE?
		return false;
	}

    public function reject($denier_id) {
        if ($this->role == '0') {
            if (DB::i()->rejectClient($this->id, $denier_id)) {
                //DO SOMETHING IN CASE OF REJECTION?!
                return true;
			}
        }
        else if($this->role == '1') {
            if (DB::i()->rejectEmployee($this->id, $denier_id)) {
                //DO SOMETHING IN CASE OF REJECTION?!
                return true;
			}
		}
		//TODO: SHOW ERROR MESSAGE?
        return false;
    }

    public function block($timestamp) {
        //TODO: IMPLEMENT
    }

    public function unblock($approver_id) {
        if ($this->role == '0') {
            if (DB::i()->approveClient($this->id, $approver_id)) {
                return true;
            }
        }
        else if($this->role == '1') {
            if (DB::i()->approveEmployee($this->id, $approver_id)) {
                return true;
            }
        }
        //TODO: SHOW ERROR MESSAGE?
        return false;
    }

	public static function approveRegistrations($requests, $employee_id) {
		$requests = explode(";",$requests);
		foreach ($requests as $request) {
			$exploded = explode(":",$request);
			$id = $exploded[0];
			$role = $exploded[1];
			$data = DB::i()->getUser($id,$role);
			if (!$data) {
				return false;
			}
			$user = new user($data);
			$result = $user->approve($employee_id);
			if (!$result) {
				//TODO: handle registration error
			}
		}
        return true;
	}

    public static function rejectRegistrations($requests, $employee_id) {
        $requests = explode(";",$requests);
        foreach ($requests as $request) {
            $exploded = explode(":",$request);
            $id = $exploded[0];
            $role = $exploded[1];
            $data = DB::i()->getUser($id,$role);
            if (!$data) {
                return false;
            }
            $user = new user($data);
            $result = $user->reject($employee_id);
            if (!$result) {
                //TODO: handle registration error
            }
        }
        return true;
    }

    public static function unblockUsers($requests, $employee_id) {
        $requests = explode(";",$requests);
        foreach ($requests as $request) {
            $exploded = explode(":",$request);
            $id = $exploded[0];
            $role = $exploded[1];
            $data = DB::i()->getUser($id,$role);
            if (!$data) {
                return false;
            }
            $user = new user($data);
            $result = $user->unblock($employee_id);
            if (!$result) {
                //TODO: handle registration error
            }
        }
        return true;
    }
	
	private function generateTanPDF($tans,$pass='iDontKnowAndIDontCare') {
		
		$fullname 	= $this->firstname.' '.$this->lastname ;
		
		$pdf	= new FPDF_Protection();
		$pdf->SetProtection(array('print'),$pass);
		$pdf->AddPage();
		$cell_number	= 40 ; 

		# Add image/header for GNB Bank 
		$pdf->SetY(10); 
		$pdf->SetFont('Arial','',20); 	//Set Font to Arial/Helvetica 20 pt font
		$pdf->SetTextColor(0,0,0); 		//Set Text Color to Black;
		
		$pdf->Image('/var/www/gnb/project/media/gnb_logo.png',10,10,-300);
		$pdf->Ln() ; 
		$pdf->Cell(0,30,"TAN Codes for $fullname ",0,1,'C');   
		
		foreach ($tans as $tan_code){
			$pdf->Cell(100,7,$tan_code, 1,2,'C' );
		}
	
		$tanpdffilename	= $this->id.'_'.$this->firstname.'.pdf';
		$tanpdfdir 		= "/var/www/gnb/project/holder/"; 
		$tanpdffile		= $tanpdfdir.$tanpdffilename ;
		$pdf->Output($tanpdffile,'F');
		return $tanpdffile ; 
	}
}
