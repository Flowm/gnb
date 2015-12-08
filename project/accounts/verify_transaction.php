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

$token = "";
if ($_POST['token'] != $_SESSION['token']) {
	die("CSRF detected!");
} else {
	$token = $_SESSION['token'];
}

require_once getpageabsolute("db_functions");
require_once getPageAbsolute("user");
require_once getPageAbsolute("util");
require_once getPageAbsolute("drawfunctions");

if (empty($_SESSION["account_id"]))
	die("Please choose an account");


$account_id 	= $_SESSION["account_id"];
$role			= $_SESSION["role"] ; 

$dest_code		= ( isset($_POST["dest_code"]) ? santize_input($_POST["dest_code"],SANITIZE_INT) : '' );
$amount			= ( isset($_POST["amount"]) ? santize_input($_POST["amount"],SANITIZE_DOUBLE) : '' );
$description	= ( isset($_POST["description"]) ? santize_input($_POST["description"],SANITIZE_STRING_DESC) : '' );
$tan_code		= ( isset($_POST["tan_code"]) ? santize_input($_POST["tan_code"],SANITIZE_STRING_DESC) : '' );


//Need user details in order to check the authentication type
$user_id = $_SESSION["user_id"];
$user = new user(DB::i()->getUser($user_id));
$account = new account(DB::i()->getAccountDetails($account_id));
$auth_type = DB::i()->mapAuthenticationDevice($user->auth_device);

# Process Transaction 
if ( isset($_SESSION["process"]) && $_SESSION["process"] == true
    && isset($_POST["confirmed"]) && $_POST["confirmed"] == "yes") {
	$trans_res	 = DB::i()->processTransaction($account_id, $dest_code, $amount, $description, $tan_code, $auth_type);
	if ($trans_res == false){
		die ("Unkonwn Transaction error, please connect our bros for help!");
	}
	echo	'<h3>Transaction Successful</h3>' ;
	echo 	'<form method="post" action="'.$_SERVER["PHP_SELF"].'">'
		.	'<input type="hidden" name="frame" value="account_overview">'
		.	'<input type="hidden" name="section" value="my_accounts">'
		.	'<input type="submit" value="Back to Overview" class="simpleButton">'
		.	'<input type="hidden" name="token" value="' . $token . '">'
		.	'</form">' ;
    unset($_SESSION["process"]);
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

    //This stuff is not checked inside the verify transaction function
    $error_message = null;
    $timestamp = null;
    if ($auth_type == 'SCS') {
        $timestamp = verifyAppGeneratedTAN($tan_code,$user->pin,$dest_code,$amount);
        if ($timestamp == null || $timestamp <= $account->last_tan_time) {
            $error_message = 'The transaction could not be completed -> Invalid TAN';
        }
        else {
            $account->last_tan_time = $timestamp;
            DB::i()->setLastTANTime($account_id,$timestamp);
        }
    }
    if ($error_message != null) {
        ?>
        <h3><?= $error_message ?></h3>
        <form method="post" action="<?php getPageURL($role) ?>">
            <input type="hidden" name="dest_code" value="<?= $dest_code ?>">
            <input type="hidden" name="amount" value="<?= $amount ?>">
            <input type="hidden" name="description" value="<?= $description ?>">
            <input type="hidden" name="tan_code" value="<?= $tan_code ?>">
            <input type="hidden" name="account_id" value="<?= $account_id ?>">
            <input type="hidden" name="section" value="my_accounts">
            <input type="hidden" name="frame" value="new_transaction">
            <input type="hidden" name="token" value="<?php $token ?>">
            <input type="submit" class="simpleButton" value="Go Back">
            </form>
        <?php
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
			.	'<input type="hidden" name="token" value="'.$token.'">'
            .   '<input type="hidden" name="confirmed" value="yes">'
			.	'<input type="submit" value="Confirm" class="simpleButton">'
			.	'</form>' ;

        //Can be processed correctly
        $_SESSION["process"] = true;
		
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
			.	'<input type="hidden" name="token" value="'.$token.'">'
			.	'<input type="submit" value="Go Back" class="simpleButton">'
			.	'</form>' ;	
	}
}

?>
