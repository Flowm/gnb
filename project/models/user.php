<?php

require_once __DIR__."/../resource_mappings.php";
require_once getPageAbsolute("db_functions");
require_once getPageAbsolute("mail");
require_once getPageAbsolute("account");

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
			if (approveClient($this->id, $approver_id)) {
				$balance = rand(100,1000);
				$account_id = addAccountWithBalance($this->id, $balance);
				$account = new account(array('id'=>$account_id));
				$tans = $account->generateTANs();
				$name = "$this->firstname $this->lastname";
				$gnbmailer = new GNBMailer();
				return $gnbmailer->sendMail_Approval($this->email, $name, $balance, $tans);
			}
		} else if ($this->role == '1') {
			if (approveEmployee($this->id, $approver_id)) {
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
            if (rejectClient($this->id, $denier_id) || true) {
                //DO SOMETHING IN CASE OF REJECTION?!
                return true;
			}
        }
        else if($this->role == '1') {
            if (rejectEmployee($this->id, $denier_id) || true) {
                //DO SOMETHING IN CASE OF REJECTION?!
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
			$data = getUser($id,$role);
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
            $data = getUser($id,$role);
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
}
