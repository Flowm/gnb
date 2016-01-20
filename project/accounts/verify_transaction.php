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

$error_types = array(0=>'Invalid destination Bank account!',
	1=>'Invalid amount!',
	2=>'Invalid description!',
	3=>'Invalid TAN!',
	4=>'Invalid input!',
	5=>'The transaction could not be completed -> Invalid TAN',
	6=>'Transaction could not be completed');

if (empty($_SESSION["account_id"]))
	die("Please choose an account");


$account_id 	= $_SESSION["account_id"];
$role			= $_SESSION["role"] ;

$dest_code = check_post_input("dest_code",SANITIZE_INT);
$amount = check_post_input("amount",SANITIZE_DOUBLE);
$description = check_post_input("description",SANITIZE_STRING_DESC);
$tan_code = check_post_input("tan_code",SANITIZE_STRING_DESC);

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
	echo 	'<form method="post" action="'.getPageURL($role).'">'
		.	'<input type="hidden" name="frame" value="account_overview">'
		.	'<input type="hidden" name="section" value="my_accounts">'
		.	'<input type="submit" value="Back to Overview" class="simpleButton">'
		.	'<input type="hidden" name="token" value="' . $token . '">'
		.	'</form">' ;
    unset($_SESSION["process"]);
	exit();
}
# Otherwise confirm transaction parameters
else 
{
    //Need user details in order to check the PIN
    $user_id = $_SESSION["user_id"];
    $user = new user(DB::i()->getUser($user_id));
    $account = new account(DB::i()->getAccountDetails($account_id));
    $auth_type = DB::i()->mapAuthenticationDevice($user->auth_device);

	//Setting error codes in case the input sanitization returned an error
	if ($dest_code == null) {
		$dest_code = '';
		$error = 0;
	}
	if ($amount == null) {
		$amount = '';
		$error = (isset($error)) ? 4 : 1;
	}
	if ($description == null) {
		$description = '';
		$error = (isset($error)) ? 4 : 2;
	}
	if ($tan_code == null) {
		$tan_code = '';
		$error = (isset($error)) ? 4 : 3;
	}
	if (empty($account_id)) {
		die("Account ID not found");
	}

    //Checking TAN
    $timestamp = null;
    if ($auth_type == 'SCS') {
        $timestamp = verifyAppGeneratedTAN($tan_code,$user->pin,$dest_code,$amount);
        if ($timestamp == null || $timestamp <= $account->last_tan_time) {
			$error = 5;
        }
        else {
            $account->last_tan_time = $timestamp;
            DB::i()->setLastTANTime($account_id,$timestamp);
        }
    }
	//If we found any errors so far, we should print them
	if (!isset($error)) {
		# Verify Operation
		$transaction_res = DB::i()->verifyTransaction($account_id, $dest_code, $amount , $description , $tan_code, $auth_type ) ;
		if ($transaction_res["result"] == true ) {

			# Setting the summary line
			$summary = 'All details verified as of ' . date("Y-m-d h:i:sa");

			//Can be processed correctly
			$_SESSION["process"] = true;
		}
		else {
			$error = 6;
		}
	}
	//If we found any errors so far, we report them to the user and quit
	if (isset($error) && isset($error_types[$error])) {
		?>
		<h3><?= $error_types[$error] ?></h3>
		<form method="post" action="<?php getPageURL($role) ?>">
			<input type="hidden" name="dest_code" value="<?= $dest_code ?>">
			<input type="hidden" name="amount" value="<?= $amount ?>">
			<input type="hidden" name="description" value="<?= $description ?>">
			<input type="hidden" name="tan_code" value="<?= $tan_code ?>">
			<input type="hidden" name="account_id" value="<?= $account_id ?>">
			<input type="hidden" name="section" value="my_accounts">
			<input type="hidden" name="frame" value="new_transaction">
			<input type="hidden" name="token" value="<?= $token ?>">
			<input type="hidden" name="error" value="error">
			<input type="submit" class="simpleButton" value="Go Back">
		</form>
		<?php
		exit();
	}
}

?>

<table id="details_verification" class="simple-text-big">
	<tr>
		<th colspan="2"><?=$summary ?></th>
	</tr>
	<tr>
		<td>Destination</td>
		<td><?= $dest_code?></td>
	</tr>
	<tr>
		<td>Amount</td>
		<td><?= $amount?></td>
	</tr>
	<tr>
		<td>Description</td>
		<td><?= $description?></td>
	</tr>
	<tr>
		<td>TAN Code</td>
		<td><?= $tan_code?></td>
	</tr>
</table>

<form method="post" action="<?= getPageURL($role)?>">
	<input type="hidden" name="frame" value="verify_transaction">
	<input type="hidden" name="section" value="my_accounts">
	<input type="hidden" name="dest_code" value="<?= $dest_code ?>">
	<input type="hidden" name="amount" value="<?= $amount ?>">
	<input type="hidden" name="description" value=" <?= $description ?>">
	<input type="hidden" name="tan_code" value="<?= $tan_code ?>">
	<input type="hidden" name="account_id" value="<?= $account_id?>">
	<input type="hidden" name="token" value="<?= $token ?>">
	<input type="hidden" name="confirmed" value="yes">
	<input type="submit" value="Confirm" class="simpleButton">
</form>

