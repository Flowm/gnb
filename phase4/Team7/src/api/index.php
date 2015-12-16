<?php
include("dbconnect.php");
include("queries.php");
include("asset.php");

require('PDF_table.php');
//ini_set( 'error_reporting', E_ALL ); //TODO disable this for production!

//error_reporting(-1);
//ini_set('display_errors', 1);  //TODO disable this for production!
//ini_set('display_startup_errors', 1);
date_default_timezone_set('UTC');

header('Access-Control-Allow-Origin: http://localhost:9000');
header('Access-Control-Allow-Headers: accept, origin, content-type');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Credentials: true');
header('Cache-Control: no-cache, no-store, must-revalidate');
$ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';

header('X-Frame-Options: DENY'); //to prevent clickjacking

// respond to preflights
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('Access-Control-Allow-Origin: http://localhost:9000');
    header('Access-Control-Allow-Headers: accept, origin, content-type');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Credentials: true');

    exit;
}

try {
    // Initialize Composer autoloader
    if (!file_exists($autoload = __DIR__ . '/vendor/autoload.php')) {
        throw new \Exception('Composer dependencies not installed. Run `make install --directory app/api`');
    }
    require_once $autoload;

    // Initialize Slim Framework
    if (!class_exists('\\Slim\\Slim')) {
        throw new \Exception(
            'Missing Slim from Composer dependencies.'
            . ' Ensure slim/slim is in composer.json and run `make update --directory app/api`'
        );
    }

    // Run application
    $app = new \Slim\Slim(array(
        'debug' => true,
        'cookies.encrypt' => true,
        'cookies.lifetime' => '20 minutes',
        'cookies.httponly' => true
    ));
    //$app->config('cookies.httponly', true);

    $app->get('/userinfodetails', function() use ($app, $db, $queries){
        $user = getCurrentUserInfo();
        $userId = $app->request()->params('userId');
        // Deny access if user is not approved yet
        if(!$user->IsApproved){
            $app -> halt(401, "You are not approved yet");
        }
        // Display user information if the user hat the right to do so:
        // employees are allowed to look at everybody
        // user are only allowed to get infos about themselves
        if($userId == $user->UserId || !isset($userId)){
            
            if ($user->TanMethod == "1") $user->pin="Check your e-mails!"; //SCS users receive their pin via e-mail
            echo json_encode($user);
            return;
        }
        if(!$user->IsEmployee){
            $app -> halt(401, "Action not allowed");
        }
        $userInfo = getUserInfoById($userId);
        echo json_encode($userInfo);
    });

    $app->get('/getHistory', function() use($app, $db, $queries){
        $user = getCurrentUserInfo();
        $userId = $app->request()->params('userId');
        if(!$user->IsApproved){
            $app -> halt(401, "You are not approved yet");
        }
        if($userId == $user->UserId || !isset($userId) || $user->IsEmployee){
            $userId = isset($userId) ? $userId : $user->UserId;
            echo json_encode(getHistoryStmt($userId));
            return;
        }
        $app->halt(401, "You do not have the right to perform this action");
    });

    $app->post('/uploadFile', function() use($app, $db, $queries){
        csrf_check();
        $user = getCurrentUserInfo();
        if(!$user->IsApproved){
            $app -> halt(401, "You are not approved yet");
        }
        if($user->IsEmployee){
            $app -> halt(401, "Employees are not allowed to do transactions");
        }

        $tan = $_POST['tan'];
        $desc = $_POST['desc'];


        if(!isset($_FILES['file'])){
            $app->halt(400, "No file attached");
        }

        if ($_FILES['file']['type'] != 'text/plain') {
            $app->halt(400, "Invalid file format. Please upload a .txt file");
        }

        // move the file to the directory of the parser
        do {
            $randomString = generateRandomString();
            $fileName = "$randomString.txt";
            $filePath = "./parser/$fileName";
        } while(file_exists($filePath));
        if(!move_uploaded_file($_FILES['file']['tmp_name'], $filePath)){
            $app->halt(500, "Unable to move file");
        }
        $output = array();
        $currentUserAccountNo = getCurrentUserAccountNo();


        checkTan($user, $tan, $user->TanMethod);  //Even SCS users cannot reuse tans!
        if($user->TanMethod){ //SCS users
            $filedata = file_get_contents($filePath);
            $realdata = mb_convert_encoding ($filedata, "UTF-8");
            validate_scs_tan($tan, $realdata, $user->pin);
            $query = $queries["addTAN"](); //Insert unused tan to be invalidated by the C parser
            $statement = getStatement($query);
			$statement->bind_param("ss",$tan, $currentUserAccountNo);
			$statement->execute();
			$statement->store_result();
        } 

        echo "./parser/a.out $filePath $currentUserAccountNo $tan \"$desc\"";

        exec("./parser/a.out $filePath $currentUserAccountNo $tan \"$desc\"", $output, $returnCode);

        // delete the file
        unlink($filePath);

        // send error code if transaction was not succesfull
        if($returnCode != 0){
            $app->halt(400, json_encode($output));
        }

    });

    $app->get('/transactionInfo', function() use($app, $db, $queries){
        $txId = $app->request()->params('txId');
        if(!isset($txId)){
            $app->halt(400, "Missing Parameter txId");
        }
        $user = getCurrentUserInfo();
        if(!$user->IsEmployee){
            $app->halt(401, "You are not authorized to perform this action");
        }
        echo json_encode(transactionInfo($txId));
    });

    $app->get('/login', function() use ($app, $db, $queries) {
        $email = $app->request()->params('email');
        $plainpassword = $app->request()->params('password');
        
        $statement = getStatement($queries["getUserSalt"]());
        $statement->bind_param("s", $email);
        			
        $statement->execute();
        $statement->store_result();
        
        if($statement->num_rows != 1){
			$app -> halt(400, "Wrong user or password");
	    }
	    
		$statement->bind_result($Name, $salt) ; 
		$statement->fetch() ; 
        
        $password = hash('sha256', $plainpassword.$salt);

        //get ban time and amount of tries
        $query = $queries["getNumberOfTries"]();
        $statement = getStatement($query);
        $statement->bind_param("s", $email);
		$statement->execute();
        $statement->store_result();
        $statement->bind_result($wrong_password_counter, $ban_end_time) ; 
		$statement->fetch() ; 
        
        if(new DateTime($ban_end_time) > new DateTime("now")) {
            $date = (new DateTime($ban_end_time));
            $date->add(new DateInterval("PT1H"));
            $app -> halt(400, "You were banned till ".($date->format('d-m-Y H:i:s')));
        }

        $query = $queries["getUserByCredentials"]();
        $statement = getStatement($query);
        $statement->bind_param('ss', $password, $email);
        $statement->execute();
     
        $statement->store_result();
        $statement->bind_result($UserId, $IsApproved) ; 
		$statement->fetch() ;

        // send a error code if no or multiple rows match
        if($statement->num_rows != 1){
            //count tries	
            if($wrong_password_counter >= 5) {
                //ban the user for 10 min
                $bantime = new DateTime();
                $bantime->add(new DateInterval('PT15M'));
                $query = $queries["updateBanTime"]($bantime->format('Y-m-d H:i:s'), $email);
                $statement = getStatement($query);
                $time = $bantime->format('Y-m-d H:i:s');
				$statement->bind_param('ss', $time, $email);
				$statement->execute();
				$statement->store_result();
       
                $query = $queries["updateNumberOfTries"]();
                $statement = getStatement($query);
                $statement->bind_param('is', $time, $email);
                $time=0;
                $statement->execute();
				$statement->store_result();
				
                $bantime->add(new DateInterval('PT1H'));
                $app -> halt(400, "Wrong user or password. You were banned till ".$bantime->format('d-m-Y H:i:s'));
            } else {
                //+1 to tries
                
                $query = $queries["updateNumberOfTries"]();
                 $statement = getStatement($query);
                $statement->bind_param('is', $time, $email);
                $time=$wrong_password_counter+1;
                $statement->execute();
				$statement->store_result();
                $app -> halt(400, "Wrong user or password. You have ".(5 - ($wrong_password_counter))." tries more.");
            }
            $app -> halt(400, "Wrong user or password");
        } else {
            //set counter to null, the password is ok
            $query = $queries["updateNumberOfTries"]();
            $statement = getStatement($query);
            $statement->bind_param('is', $time, $email);
            $time=0;
            $statement->execute();
		    $statement->store_result();
            
        }
       
        if(!$IsApproved){
            $app -> halt(400, "User is not approved");
        }
        // start a new session after
        // the user has successfully logged in
        // and store it into the database

        //-------------------------------------------------------------------
        //set cookie params (before session starts)
        //the function: session_set_cookie_params($expire, $path, $domain, $secure, true);
        //session_set_cookie_params(time()+900, true, true); 
        // Change the session name 
        // session_name('Team7');
        //then do session_starts(); and then session_regenerate_id(true);
        //-------------------------------------------------------------------------

        if(session_id()) {
            session_unset();   // Remove the $_SESSION variable information.
            session_destroy(); // Remove the server-side session information.
        }
        // Start a new session
        session_start();

        // Generate a new session ID
        session_regenerate_id(true);

        $salt = uniqid(mt_rand(), true);
        $csrf_token = hash('sha256', session_id().$salt);
        setcookie("XSRF-TOKEN", $csrf_token, 0,  "/");

        $query = $queries["updateSessionId"]();
        $statement = getStatement($query);
        $statement->bind_param('si', $sessionId, $UserId);
        $sessionId=session_id();
        $statement->execute();
		$statement->store_result();
        
        $query = $queries["extendSessionTime"]();
        $statement = getStatement($query);
        $statement->bind_param('s', $sessionId);
        $sessionId=session_id();
        $statement->execute();
		$statement->store_result();
		
        $userInfo = getUserInfoById($UserId);
      
        echo json_encode($userInfo);
    });


    $app->get('/logout', function() use ($app, $db, $queries) {
        csrf_check();
        $user = getCurrentUserInfo();
        session_start();
        if (isset($_COOKIE['PHPSESSID'])) {
            setcookie('PHPSESSID', '', time() - 3600, '/');
        }
        if (isset($_COOKIE['XSRF-TOKEN'])) {
            setcookie('XSRF-TOKEN', '', time() - 3600, '/');
        }
        session_unset();   // Remove the $_SESSION variable information.
        session_destroy();// Remove the server-side session information.
        $query = $queries["updateSessionId"]();
        $statement = getStatement($query);
        $statement->bind_param('si', $sessionId, $UserId);
        $sessionId=NULL;
        $statement->execute();
		$statement->store_result();
    });

    $app->get('/getUsersToApprove', function() use ($app, $db, $queries){
        $user = getCurrentUserInfo();
        if(!$user->IsApproved || !$user->IsEmployee){
            $app->halt(401, "You do not have the right to perform this action");
        }
        echo json_encode(getUsersToApprove());
    });

    //reset password
    $app->post('/resetpass', function() use ($app, $db, $queries){
        $entityBody = json_decode(file_get_contents('php://input'));
        // $email = $entityBody->{'email'};
        $newpass = $entityBody->{'newpass'};
        $key = $entityBody->{'key'};

        //get user email 
        $query = $queries["getUserEmail"]();
        $statement = getStatement($query);
        $statement->bind_param("s",$key);
        $statement->execute();
        $statement->store_result();
        if ($statement->num_rows != 1) {
            $app -> halt(400, 'Your password reset key is invalid');
        }
        $statement->bind_result($email, $Name) ; 
		$statement->fetch() ; 

        //check for weak password
        $validpass = validate_pass($newpass);
        if($validpass != ""){  // Weak Password
           $app -> halt(400, $validpass);
        }
        //hash and secure new password
        $salt = uniqid(mt_rand(), true);
        $password = hash('sha256', $newpass.$salt);

        $query = $queries["updateUserPasswordbyEmail"]();
        $statement = getStatement($query);
        $statement->bind_param('sss', $password, $salt, $email);
        $statement->execute();
		$statement->store_result();
        //sendemail and inform user for password reset
        $subject = "Reset Password successful";
        $body = "Dear ". $Name. ",<br/><br/>We would like to inform you that your password 
                was reset successfully.<br/><br/>
                Best regards,<br/><br/>Your Team7 Administration";
        mailer($email, $subject, $body);

    });     

    //forgot password
    $app->post('/forgotpass', function() use ($app, $db, $queries){
        $entityBody = json_decode(file_get_contents('php://input'));
        $email = $entityBody->{'email'};
        $path = $entityBody->{'path'};

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $app -> halt(400, 'Please specify a valid eMail address');
        }

        $statement = getStatement($queries["getUserSalt"]());
        $statement->bind_param("s", $email);
        $statement->execute();
        $statement->store_result();
        
        if($statement->num_rows != 1){
			$app -> halt(400, "There is no user with this email");
	    }
	    
		$statement->bind_result($Name, $usersalt) ; 
		$statement->fetch() ; 
       
        //create the reset key
        $resetkey = hash('sha256', $email.$usersalt);
        //store it in the db
        $query = $queries["updateResetkey"]();
        $statement = getStatement($query);
        $statement->bind_param("ss",$resetkey, $email);
        $statement->execute();
        $statement->store_result();
       
        //create the url for reseting the password
        $resurl = $path."/app/#/resetpass?key=".$resetkey;
        //send email to the user with the link
        $subject = "Instructions to reset your password";
        $body = "Dear ". $Name. ",<br/><br/>It appears that you have requested to reset your password.<br/>
        Please click the link below. If you cannot click it, 
        please paste it into your web browser's address bar.<br/><br/>" . $resurl . "<br/><br/>
        Best regards,<br/><br/>Your Team7 Administration";
        mailer($email, $subject, $body);

    });

    //change password
    $app->post('/changepass', function() use ($app, $db, $queries){
        $entityBody = json_decode(file_get_contents('php://input'));
        $curpass = $entityBody->{'currentpass'};
        $newpass = $entityBody->{'newpass'};
        csrf_check();
        $user = getCurrentUserInfo();
        $userId = $user->UserId;

        $query = $queries["getPasswordByUserId"]($userId);
        $statement = getStatement($query);
        $statement->bind_param("i", $userId);
        $statement->execute();
        $statement->store_result();
	    
		$statement->bind_result($usersalt, $Name, $email, $Password) ; 
		$statement->fetch() ; 

        // check if password and user inserted current password match
        $password = hash('sha256', $curpass.$usersalt);
        if($password != $Password){
             $app -> halt(400, "Current password is wrong");
        }

        //check for weak password
        $validpass = validate_pass($newpass);
        if($validpass != ""){  // Weak Password
           $app -> halt(400, $validpass);
        }
        //hash and secure new password
        $salt = uniqid(mt_rand(), true);
        $newpassword = hash('sha256', $newpass.$salt);

        $query = $queries["updateUserPassword"]($userId, $newpassword, $salt);
        $statement = getStatement($query);
        $statement->bind_param("ssi", $newpassword, $salt, $userId);
        $statement->execute();
        $statement->store_result();

        //sendemail and inform user
        $subject = "Change Password successful";
        $body = "Dear ". $Name. ",<br/><br/>We would like to inform you that your password 
                was changed successfully.<br/><br/>
                Best regards,<br/><br/>Your Team7 Administration";
        mailer($email, $subject, $body);

    });

     $app->post('/register', function() use ($app, $db, $queries){
        $entityBody = json_decode(file_get_contents('php://input'));
        $name = $entityBody->{'name'};
        $email = $entityBody->{'email'};
        $plainpassword = $entityBody->{'password'};
        $isEmployee = $entityBody->{'isEmployee'};
        $tanMethod = $entityBody->{'tanMethod'};
        // checking necessary
        // see also: https://stackoverflow.com/questions/15081227/php-json-how-to-read-boolean-value-received-in-json-format-and-write-in-string-o
        if($isEmployee == 1){
            $isEmployee = True;
        } else {
            $isEmployee = False;
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $app -> halt(400, 'Please specify a valid eMail address');
        }

        $validpass = validate_pass($plainpassword);
        if($validpass != ""){  // Weak Password
           $app -> halt(400, $validpass);
        }

        $salt = uniqid(mt_rand(), true);
        $pin = mt_rand(100000, 999999);
        $password = hash('sha256', $plainpassword.$salt);
        $query = $queries["createUser"]();
        $statement = getStatement($query);
        $statement->bind_param("sssiiis", $email,$password, $salt,$pin,$tanMethod,$isEmployee,$name);
        $statement->execute();
        $statement->store_result();
    });

    $app->post('/approveUser', function() use ($app, $db, $queries){
        $entityBody = json_decode(file_get_contents('php://input'));
        $userId = $entityBody->{'userId'};
        $approved = $entityBody->{'approved'};
        $balance = $entityBody->{'balance'}; 
        if(!$balance) $balance = 0;
        
        csrf_check();

        $user = getCurrentUserInfo();
        if(!$user->IsEmployee){
            $app -> halt(401, "Action not allowed");
        }


        $userToApprove = getUserInfoById($userId);
        if($approved == 'true') {
            if(!($userToApprove->IsApproved)) {
                //approveUserById($userId, $userToApprove->Email, $balance, $userToApprove->TanMethod, $userToApprove->pin);
                approveUserById($userToApprove, $balance);
            }
        }
        else{
            deleteUser($userId, $userToApprove->email);
        }
    });

    $app->get('/getUsers', function() use ($app, $db, $queries){
        $user = getCurrentUserInfo();
        if(!$user->IsApproved || !$user->IsEmployee){
            $app->halt(401, "You do not have the right to perform this action");
        }
        echo json_encode(getUsers());
    });

    $app->post('/createTx', function() use ($app, $db, $queries){
        $entityBody = json_decode(file_get_contents('php://input'));
        $amount = $entityBody->{'amount'};
        $tan = $entityBody->{'TAN'};
        $receiverAccountNo = $entityBody->{'receiverAccountNo'};
        $description = $entityBody->{'description'};

        csrf_check();
        $user = getCurrentUserInfo();
        $txId = createTx($user, $amount, $tan, $receiverAccountNo, $description);

        if($amount < 10000){
            approveTx($user ->Account_no, $amount, $receiverAccountNo, $txId);
        }

    });

    $app->post('/approveTx', function() use ($app, $db, $queries){
        csrf_check();
        $user = getCurrentUserInfo();
        if(!$user->IsApproved || !$user->IsEmployee){
            $app->halt(401, "You do not have the right to perform this action");
        }

        $entityBody = json_decode(file_get_contents('php://input'));
        $txId = $entityBody->{'txId'};
        $approved = $entityBody->{'approved'};

        $query = $queries["getTxById"]($txId);
        $result = executeSQLQuery($query);
        if ($result->num_rows != 1) {
            $app->halt(400, "There is no transaction with id $txId");
        }
        $tx = mysqli_fetch_object($result);

        if($approved == "true"){
            approveTx($tx -> Txn_SndAccountNo, $tx->Txn_amount, $tx -> Txn_RcvAccountNo, $txId);
        }
        else{
            updateTxStatus($txId, "DENIED", "FAILED");
        }

    });

    $app->get('/getTxToApprove', function() use ($app, $db, $queries){
        $user = getCurrentUserInfo();
        if(!$user->IsApproved || !$user->IsEmployee){
            $app->halt(401, "You do not have the right to perform this action");
        }
        echo json_encode(getTxToApprove());
    });

    $app->get('/downloadTxHistory', function() use ($app, $db, $queries){
        $userId = $app->request()->params('userId');

        $user = getCurrentUserInfo();

        if(!$user->IsApproved){
            $app -> halt(401, "You are not approved yet");
        }
        if($userId == $user->UserId || $user->IsEmployee){

            $pdf = new PDF_Table();
            $pdf->AddPage('0');

            $query = $queries["getTransactionHistory"]( $userId);
            $pdf->Table($query);

            $filename="TX_HST_".generateRandomString().".pdf";
            $pdf->Output($filename,'D');
        }
        else{
            $app->halt(401, "You do not have the right to perform this action");
        }

    });


    $app->run();

} catch (\Exception $e) {
    if (isset($app)) {
        $app->handleException($e);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => 500,
            'statusText' => 'Internal Server Error',
            'description' => $e->getMessage(),
        ));
    }
}
?>
