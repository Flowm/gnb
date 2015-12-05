<?php

require_once __DIR__."/../resource_mappings.php";

//Worst case, an unauthenticated user is trying to access this page directly
if (!isset($_SESSION["username"]) || !isset($_SESSION["role"])) {
	include(getPageAbsolute('error'));
	exit();
}
//The user is logged in, but tries to access another page directly
else if (!isset($frame)) {
	header("Location:".getPageURL('home'));
	exit();
}

require_once getpageabsolute("db_functions");
require_once getPageAbsolute("user");
require_once getPageAbsolute("util");
require_once getPageAbsolute("drawfunctions");

if (empty($_SESSION["account_id"]))
	die("Please choose an account");


$account_id 	= $_SESSION["account_id"];
$role			= $_SESSION["role"] ; 
$dest_code		= ( isset($_POST["dest_code"]) ? $_POST["dest_code"] : '' );
$amount			= ( isset($_POST["amount"]) ? $_POST["amount"] : '' );
$description	= ( isset($_POST["description"]) ? $_POST["description"] : '' );
$tan_code		= ( isset($_POST["tan_code"]) ? $_POST["tan_code"] : '' );
$pin            = ( isset($_POST["pin"]) ? $_POST["pin"] : '' );

# Process Transaction 
if ( isset($_POST["process"]) && $_POST["process"] == 'yes'){
	$trans_res	 = DB::i()->processTransaction($account_id, $dest_code, $amount, $description, $tan_code) ;
	if ($trans_res == false){
		die ("Unkonwn Transaction error, please connect our bros for help!");
	}
	echo	'<h3>Transaction Successful</h3>' ;
	echo 	'<form method="post" action="'.$_SERVER["PHP_SELF"].'">'
		.	'<input type="hidden" name="frame" value="account_overview">'
		.	'<input type="hidden" name="section" value="my_accounts">'
		.	'<input type="submit" value="Back to Overview" class="simpleButton">'
		.	'</form">' ;
}
# Otherwise confirm transction parameters 
else 
{
    //Need user details in order to check the PIN
    $user_id = $_SESSION["user_id"];
    $user = new user(DB::i()->getUser($user_id));
    $account = new account(DB::i()->getAccountDetails($account_id));
    $auth_type = DB::i()->mapAuthenticationDevice($user->auth_device);

	# verify all input is there 
	if (empty($account_id))
		die("Account ID not found");
	if (empty($dest_code))
		die("Destination Code not found");
	if (empty($amount))
		die("Amount not found");
	if (empty($description))
		die("Description Code not found");
	if (empty($tan_code))
		die("TAN Code not found");
    if ($auth_type == 'SCS' && empty($pin)) {
        die("PIN Code not found");
    }

    //This stuff is not checked inside the verify transaction function
    $error_message = null;
    $timestamp = null;
    if ($auth_type == 'SCS') {
        if ($pin != $user->pin) {
            $error_message = 'The transaction could not be completed -> Invalid PIN';
        }
        if ($error_message == null) {
            $timestamp = verifyAppGeneratedTAN($tan_code,$pin,$dest_code,$amount);
            if ($timestamp == null || $timestamp <= $account->last_tan_time) {
                $error_message = 'The transaction could not be completed -> Invalid TAN';
            }
            else {
                $account->last_tan_time = $timestamp;
                //DB::i()->setLastTANTime($account_id, $timestamp); TODO: IMPLEMENT THIS!!!
            }
        }
    }
    if ($error_message != null) {
        echo	'<h3>'.$error_message.'</h3>' ;
        echo 	'<form method="post" action="'.getPageURL($role).'">'
            .	'<input type="hidden" name="dest_code" value="'.$dest_code.'">'
            .	'<input type="hidden" name="amount" value="'.$amount.'">'
            .	'<input type="hidden" name="description" value="'.$description.'">'
            .	'<input type="hidden" name="tan_code" value="'.$tan_code.'">'
            .	'<input type="hidden" name="account_id" value="'.$account_id.'">'
            .	'<input type="submit" class="simpleButton" value="Go Back">'
            .	'</form>' ;
        return;
    }

	
	# sanitize input 
	# no need for stage one 
	
	# Verify Operation
	$transaction_res = DB::i()->verifyTransaction($account_id, $dest_code, $amount , $description , $tan_code, $auth_type ) ;
	if ($transaction_res["result"] == true ){
		
		# Setting the summary line 
		$summary 		= 'All details verified as of '.date("Y-m-d h:i:sa")  ; 
		## drawing headers
		
		echo '<br><table border="1">' 
			. '<thead>'."\n"
			. '<tr>'.'<th colspan="3">'.$summary.'</th>'."\n" 
			. '</tr>'.'</thead>'."\n"
			. '<tr><th>Destination</th><td>'.$dest_code.'</th></tr>' 
			. '<tr><th>Amount</th><td>'.$amount.'</th></tr>' 
			. '<tr><th>Description</th><td>'.$description.'</th></tr>' 
			. '<tr><th>TAN Code</th><td>'.$tan_code.'</th></tr>'
			. '</table>' ; 
		
		
		echo 	'<form method="post" action="'.$_SERVER["PHP_SELF"].'">'
			.	'<input type="hidden" name="frame" value="verify_transaction">'
			.	'<input type="hidden" name="section" value="my_accounts">'
			.	'<input type="hidden" name="dest_code" value="'.$dest_code.'">'
			.	'<input type="hidden" name="amount" value="'.$amount.'">'
			.	'<input type="hidden" name="description" value="'.$description.'">'
			.	'<input type="hidden" name="tan_code" value="'.$tan_code.'">'
			.	'<input type="hidden" name="account_id" value="'.$account_id.'">'
			.	'<input type="hidden" name="process" value="yes">'
			.	'<input type="submit" value="Confirm" class="simpleButton">'
			.	'</form>' ;	
		
	} else {
	#	
		$error_message 	= 'Transaction could not be completed ->'. $transaction_res["message"]	; 
		
		echo	'<h3>'.$error_message.'</h3>' ; 
		echo 	'<form method="post" action="'.getPageURL($role).'">'
			.	'<input type="hidden" name="dest_code" value="'.$dest_code.'">'
			.	'<input type="hidden" name="amount" value="'.$amount.'">'
			.	'<input type="hidden" name="description" value="'.$description.'">'
			.	'<input type="hidden" name="tan_code" value="'.$tan_code.'">'
			.	'<input type="hidden" name="account_id" value="'.$account_id.'">'
			.	'<input type="submit" value="Go Back" class="simpleButton">'
			.	'</form>' ;	
	}
}

?>
