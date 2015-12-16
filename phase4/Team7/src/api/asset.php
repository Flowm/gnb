<?php
/**
 * Collection of useful helper Methods
 */

/**
 * @param $app
 * Slim Application
 * @param $db
 * @param $query
 * String that represents a SQL queryf
 *
 * This function executes a SQL query and
 * sends an error code if something went wrong
 */

//ini_set( 'error_reporting', E_ALL ); //TODO disable this for production!

//error_reporting(-1);
//ini_set('display_errors', 1);  //TODO disable this for production!
//ini_set('display_startup_errors', 1);

require_once('FPDF_Protection.php');
require_once('vendor/PHPMailer/PHPMailerAutoload.php');

function validate_scs_tan($tan, $data, $pin){
    //Usage: $data = $receiver_no.$amount
    // OR $data = batch_file_bytes
    $app = $GLOBALS["app"];
    if(strlen($tan) != 15){
        $app -> halt(400, "The transaction could not be performed: Invalid Tan length");
    }


    $hash = substr($tan, 0, 10);
    $rand = substr($tan,10, 5);

    $correct_hash = hash ('sha256', $pin.$data.$rand);

    $output = substr($correct_hash, 0, 10);
    
    if ($output != $hash) {
        $app -> halt(400, "The transaction could not be performed: Wrong SCS TAN"); //TODO edit for production
    }
}


function csrf_check(){ 

    $app = $GLOBALS["app"];
    
    $headers = getallheaders();
    if(array_key_exists("X-XSRF-TOKEN", $headers)){
        $token = $headers["X-XSRF-TOKEN"];
        if ($token != $_COOKIE["XSRF-TOKEN"]) {
            //header token different than cookie
            $app->halt(400, "XSRF token is different from the XSRF cookie value");
        }
        return true;
    }
    //Not found in header
    $app->halt(400, "XSRF token was not found in header");
}


function validate_pass($pass) {
   $r1='/[A-Z]/';  //require Uppercase
   $r2='/[a-z]/';  //require lowercase
   $r3='/[!@#$%^&*()\-_=+{};:,<.>]/';  // require a special char
   $r4='/[0-9]/';  //require numbers
   $msg ="";

   if(preg_match_all($r1,$pass, $out)<1) $msg .= "An upper case letter. ";

   if(preg_match_all($r2,$pass, $out)<1) $msg .= "A lower case letter. ";
      
   if(preg_match_all($r3,$pass, $out)<1) $msg .= "A special character (!@#$%^&*()\-_=+{};:,<.>)";

   if(preg_match_all($r4,$pass, $out)<1) $msg.= "A number. ";

   if(strlen($pass)<8) $msg.= "At least 8 characters. ";
   
   if($msg) $msg = "Weak password detected. Please include: ".$msg;

   return $msg;
}

function mailer($email, $subject, $body, $attachment = False){
    $app = $GLOBALS["app"];

    $mail = new PHPMailer(); // defaults to using php "mail()"
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com'; // dns resolve is slow using direct ip //'smtp.gmail.com;'; // Specify main and backup SMTP servers
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = 'team.7.bank@gmail.com'; // SMTP username
    $mail->Password = 'crazypassword'; // SMTP password
    $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587; // TCP port to connect to

    $mail->AddReplyTo("no-reply@team7bank.com","Team 7 bank");
    $mail->SetFrom('registration@team7bank.com', "Team 7 bank");
    
    $mail->AddAddress($email, "Customer");

    $mail->Subject    = $subject;
    $mail->Body = $body;
    $mail->AltBody    = $body;
     
    $mail->MsgHTML($body);

    if ($attachment) {
        $mail->AddAttachment($attachment);      // attachment
    }

    if(!$mail->Send()) {
        $app->halt(500, "Error sending mail to ".$email.": ".$mail->ErrorInfo);   //TODO edit for production
        return False;
        //echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
      return True;
    }

}


function executeSQLQuery($query)
{
    $db = $GLOBALS["db"];
    $app = $GLOBALS["app"];

    $querySuccess = mysqli_query($db, $query);
    if (!$querySuccess) {
        $error = mysqli_error($db);
        $app->halt(500, "Error during executing query: $error");
    }
    return $querySuccess;
}

/**
 * Executes a query and returns all pending users
 */
function getUsersToApprove()
{
    $queries = $GLOBALS["queries"];

    $query = $queries["getUsersToApprove"]();
    $result = executeSQLQuery($query);
    return getQueryResultAsArray($result);
}

function getHistory($userId) //NOT USED CURRENTLY
{
    $queries = $GLOBALS["queries"];
    $app = $GLOBALS["app"];
    if(!$userId) {
        $app->halt(400, "No user id given!");
    }
    $query = $queries["getTransactionHistory"]($userId);
    $result = executeSQLQuery($query);
    
    return getQueryResultAsArray($result);
}


function getHistoryStmt($userId)  //TODO only keep one
{
    $queries = $GLOBALS["queries"];
    $app = $GLOBALS["app"];

    if(!$userId) {
        $app->halt(400, "No user id given!");
    }

    $query = $queries["getTransactionHistoryStmt"]();
    $statement = getStatement($query);
    $statement->bind_param('ii', $userId, $userId);
    $exec = $statement->execute();
    if (false===$exec) {
        $app->halt(400, "Error executing statement while retrieving user's history"); //TODO remove for production
    }
    $statement->store_result();

    //Current: Txn_description  Txn_id  Txn_amount  Txn_TAN_used    Txn_date    Txn_SndAccountNo    Txn_RcvAccountNo    Txn_ApprovalStatus  Txn_Status
    //New: Txn_id, Txn_amount  Txn_TAN_used  SndAccountNo    RcvAccountNo    SndName RcvName Txn_date    Txn_description
    $bind = $statement->bind_result($Txn_Id, $Txn_amount, $Txn_TAN_used, $Txn_SndAccountNo, $Txn_RcvAccountNo, $SndName,   $RcvName, $Txn_date, $Txn_description,   $Txn_ApprovalStatus, $Txn_Status) ; 
    if (false===$bind) {
        $app->halt(400, "Error binding while retrieving user's history"); //TODO remove for production
    }

    
    //if ($statement->num_rows < 1) {
        //$app->halt(400, "No history found". htmlspecialchars($statement->error));
    //}

    $results = array();
    while ($statement->fetch()) {
        $results[] = array(
            'Txn_Id' => $Txn_Id, 
            'Txn_amount' => $Txn_amount,
            'Txn_TAN_used' => $Txn_TAN_used,    
            'Txn_SndAccountNo' => $Txn_SndAccountNo,
            'Txn_RcvAccountNo' => $Txn_RcvAccountNo ,   
            'Txn_SndName' => $SndName,     
            'Txn_RcvName' => $RcvName,    
            'Txn_date' => $Txn_date,     
            'Txn_description' => $Txn_description,     
            'Txn_ApprovalStatus' => $Txn_ApprovalStatus,    
            'Txn_Status' => $Txn_Status  
            ) ;
        
    }
    //$app->halt(400, "Array: ". json_encode($results));
    return $results;
}

function getQueryResultAsArray($result)
{
    $result_array = array();
    while ($row = mysqli_fetch_object($result)) {
        $result_array[] = $row;
    }
    return $result_array;
}

function transactionInfo($txId)
{
    $app = $GLOBALS["app"];
    $queries = $GLOBALS["queries"];

    $query = $queries["getTransactionInfo"]($txId);
    $result = executeSQLQuery($query);
    if ($result->num_rows != 1) {
        $app->halt(400, "Could not find a transaction with given Id");
    }
    return mysqli_fetch_object($result);
}

// returns an object that contains
function getUserInfoById($userId)
{
    $app = $GLOBALS["app"];
    $queries = $GLOBALS["queries"];
    if(!$userId){
        $app->halt(400, "No user id given!");
    }
    
    $query = $queries["getUserById"]();
    $statement = getStatement($query);
    $statement->bind_param('i', $userId);
    $statement->execute();
    $statement->store_result();
    
    if ($statement->num_rows != 1) {
        $app->halt(400, "Wrong user or password");
    }
    $statement->bind_result($data->pin,$data->TanMethod, $data->UserId, $data->email,   $data->IsApproved, $data->IsEmployee, $data->Name,    $data->SessionId,   $data->Session_expire,  $data->wrong_password_counter,  $data->ban_end_time , $data->Account_no, $data->Account_bal) ; 
    $statement->fetch() ; 
    
    return $data;
}

// gets the info of the current user by its session cookie
// In case that there is no
function getCurrentUserInfo()
{
    $queries = $GLOBALS["queries"];
    $app = $GLOBALS["app"];
    try {
        $session = $_COOKIE["PHPSESSID"];
    } catch (Exception $e) {
        $app->halt(400, "No cookie found. Please Login");
    }

    //$query = $queries["getUserBySessionId"]($session);
    $query = $queries["getUserBySessionId"]();
    $statement = getStatement($query);
    $statement->bind_param('s', $session);
    $statement->execute();
    $statement->store_result();
    $statement->bind_result($data->pin,$data->TanMethod, $data->UserId, $data->email,   $data->IsApproved, $data->IsEmployee, $data->Name,    $data->SessionId,   $data->Session_expire,  $data->wrong_password_counter,  $data->ban_end_time , $data->Account_no, $data->Account_bal) ; 
    $statement->fetch() ; 

    //$result = executeSQLQuery($query);
    if ($statement->num_rows != 1) {
        $app->halt(400, "No session found. Please Login");
    }
    //$data = mysqli_fetch_object($result);

    if(new DateTime($data->Session_expire) < new DateTime("now")) {
        $app->halt(400, "Session expired. Please Login");
    } else {
        $query = $queries["extendSessionTime"]();
        $statement = getStatement($query);
        $statement->bind_param('s', $session); //TODO this was sessionId?
        $statement->execute();
		$statement->store_result();
    }
    
    return $data;
}

function generateRandomString()
{
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 15);
}

function approveUserById($user, $balance)
{
    $app = $GLOBALS["app"];
    $queries = $GLOBALS["queries"];
    
    //$userId, $userToApprove->Email, $balance, $userToApprove->TanMethod, $userToApprove->pin
    $userId = $user->UserId;
    $mail = $user->email;
    $pin = $user->pin;
    $tanMethod = $user->TanMethod;
    if($balance < 0){
        $balance = 0;
    }
    
    $query = $queries["approveUser"]();
    $statement = getStatement($query);
    $statement->bind_param("i", $userId);
    $statement->execute();
    $statement->store_result();

    if($user->IsEmployee){
        $msg = "Employee Account activated! Welcome aboard!";
        if ( !mailer($mail, "Your account was activated", $msg) ) {
            $app->halt(401, "Error sending approval mail for employee\n");
        }  
    } else {
        $accountNo = 1000000000 + $userId;
        if ($balance == null) {
            $balance = 0;
        }
        
        $query = $queries["createAccount"]();
        $statement = getStatement($query);
        $statement->bind_param("sid", $accountNo, $userId, $balance);
        $statement->execute();
        $statement->store_result();

        if (!$tanMethod){ //TAN List
            for ($i = 1; $i <= 100; $i++) {
                $success = FALSE;
                do {
                    $success = addTAN($accountNo);
                } while (!$success);
            }
         
            sendEmail($accountNo, $mail,$pin);
        } else { //Smart Card Simulator
            $msg = "Account activated! Please use this pin for your SCS: ".$pin. "\n";
            if ( !mailer($mail, "Your account was activated", $msg) ) {
                $app->halt(401, "Error sending approval mail for user with SCS\n");
            }  
        }
    }
}

function addTAN($accountNo)
{
    $queries = $GLOBALS["queries"];
    $tan = generateRandomString();
    $query = $queries["addTAN"]();
    $statement = getStatement($query);
    $statement->bind_param("ss",$tan, $accountNo);
    $ok=$statement->execute();
    $statement->store_result();
    return $ok;
}

function sendEmail($accountNo, $email, $pin)
{
    $app = $GLOBALS["app"];
    $queries = $GLOBALS["queries"];

    $query = $queries["getTANs"]();
    $statement = getStatement($query);
    $statement->bind_param("s", $accountNo);
    $statement->execute();

    $statement->bind_result($tan);
    $result_array = array();
    while ($statement->fetch()) {
        array_push($result_array, $tan);
    }

    try {
        $pdf=new FPDF_Protection();
        $pdf->SetProtection(array('copy', 'print'),$pin);
        $pdf->AddPage();
        $pdf->SetFont('Arial');
        $pdfmsg = "List of active TANs: \n\n" . implode("\r\n", $result_array);
        $pdf->Write(7, $pdfmsg);
        $outpath = './pdfs/'.$accountNo.'.pdf';
        $pdf->Output($outpath, 'F');
        $msg = "You will find a list of valid TANs in the attached PDF document. A PIN will appear on your customer's home page which you can use as a password for this PDF.\r\n";
   
        if ( !mailer($email, "Your account was activated", $msg, $outpath) ) {
            $app->halt(401, "Error sending approval mail\n");
        }  else {
            unlink($outpath);
        }

    } catch (Exception $e) {
        $app->halt(401, "Exception sending activation mail\n".$e->getTraceAsString());

    }
    
}

function deleteUser($userId, $mail)
{
    $queries = $GLOBALS["queries"];
    mailer($mail, "Your registration request was rejected", "Bad luck");
    $query = $queries["deleteUser"]();
    $statement = getStatement($query);
    $statement->bind_param("i",$userId);
    $ok=$statement->execute();
    $statement->store_result();
}

function getUsers()
{
    $queries = $GLOBALS["queries"];
    $query = $queries["getUsers"]();
    $result = executeSQLQuery($query);
    return getQueryResultAsArray($result);
}

function checkTAN($user, $tan, $tanMethod){
    $queries = $GLOBALS["queries"];
    $db = $GLOBALS["db"];
    $app = $GLOBALS["app"];

    if($tanMethod){
        $query = $queries["getInActiveTANStatus"]($user->Account_no, $tan);
        $querySuccess = mysqli_query($db, $query);
        if(!$querySuccess || $querySuccess->num_rows >= 1)
        {
            //updateTxStatus($txId, "DENIED", "FAILED");
            $app -> halt(400, "The transaction could not be performed: Used TAN");
        }
        
    } else {
        $query = $queries["getActiveTANStatus"]($user->Account_no, $tan);
        $querySuccess = mysqli_query($db, $query);
        if(!$querySuccess || $querySuccess->num_rows < 1)
        {
            //updateTxStatus($txId, "DENIED", "FAILED");
            $app -> halt(400, "The transaction could not be performed: Wrong TAN");
        }
        $row = $querySuccess->fetch_assoc();
        if($row["ActiveTAN_Status"]=="0"){
            //updateTxStatus($txId, "DENIED", "FAILED");
            $app -> halt(400, "The transaction could not be performed: Used TAN");
        }
    }
}

function markTANUsed($tan, $userId){
    $queries = $GLOBALS["queries"];
    $query = $queries["updateTAN"]($tan, $userId);
    executeSQLQuery($query);
}

function checkAmount($user, $amount){
    $app = $GLOBALS["app"];
    if( !is_numeric($amount)){
        $app -> halt(400, "The transaction could not be performed: Please insert a valid amount to be transferred!");
    }
    if( $amount <= 0){
        $app -> halt(400, "The transaction could not be performed: Amount of money should be greater than 0!");
    }
    if($user->Account_bal < $amount)
    {
        //updateTxStatus($txId, "DENIED", "FAILED");
        $app -> halt(400, "The transaction could not be performed: Insufficient funds");
    }

}

function createTx($user, $amount, $tan, $receiverAccount, $description)
{
    $queries = $GLOBALS["queries"];
    $db = $GLOBALS["db"];
    $app = $GLOBALS["app"];

    if ($user->Account_no == null) {
        $app->halt(400, "The transaction could not be performed: The user does not have an account");
    } 
    if ($user->Account_no == $receiverAccount) {
        $app->halt(400, "The transaction could not be performed: Unable to send money to your own account");
    }

    //check if receiver account exists
    $query = $queries["getReceiverAccount"]();
    $statement = getStatement($query);
    $statement->bind_param("s", $receiverAccount);
    $statement->execute();
    $statement->store_result();
    if ($statement->num_rows != 1) {
        $app -> halt(400, "The transaction could not be performed: Receiver account does not exist");
    }

    checkAmount($user, $amount);
    checkTAN($user, $tan,$user->TanMethod);

    if($user->TanMethod){
        $pin = $user->pin;

        $data = $receiverAccount.$amount;
        validate_scs_tan($tan,$data,$pin);
        $query = $queries["addUsedTAN"]();
		$statement = getStatement($query);
        $statement->bind_param("ss", $tan, $acc);
        $acc = $user->Account_no;
        $statement->execute();
        $statement->store_result();
    } else {
        markTANUsed($tan,$user->Account_no);
    }
    
    $query = $queries["createTx"]();
    $statement = getStatement($query);
    $statement->bind_param('sdsii', $description, $amount, $tan, $user->Account_no, $receiverAccount);
    $exec = $statement->execute();
    $statement->store_result();
    
    if (false===$exec) {
        $app->halt(400, "The transaction could not be performed, error inserting ". htmlspecialchars($statement->error));
    }

    return $statement->insert_id;
}

function getCurrentUserAccountNo()
{
    $app = $GLOBALS["app"];
    $user = getCurrentUserInfo();
    $queries = $GLOBALS["queries"];
    $query = $queries["getAccountNoOfUser"]($user->UserId);

    // just return the first result
    // id is unique
    $result = getQueryResultAsArray(executeSQLQuery($query));
    if (!$result) {
        $app->halt(400, "Current user does not have an account!");
    }
    return $result[0]->Account_no;
}

function updateTxStatus($txId, $approved, $status){
    $queries = $GLOBALS["queries"];
    $query = $queries["updateTxStatus"]($txId, $approved, $status);
    executeSQLQuery($query);
}

function approveTx($senderAccountNo, $amount, $receiverAccountNo, $txId){
    $queries = $GLOBALS["queries"];
    $query = $queries["updateTxSuccessful"]($txId);
    executeSQLQuery($query);

    $query = $queries["updateAccount"]($senderAccountNo, -$amount);
    executeSQLQuery($query);

    //we don't care if it fails
    $db = $GLOBALS["db"];
    $query = $queries["updateAccount"]($receiverAccountNo,  $amount);
    mysqli_query($db, $query);
}

function getTxToApprove()
{
    $queries = $GLOBALS["queries"];

    $query = $queries["getTxToApprove"]();
    $result = executeSQLQuery($query);
    return getQueryResultAsArray($result);
}

function getStatement($query)
{
    $db = $GLOBALS["db"];
    return $db->prepare($query);
}
