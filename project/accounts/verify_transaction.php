<?php

require_once __DIR__."/../resource_mappings.php";
require_once getpageabsolute("db_functions");
require_once getPageAbsolute("drawfunctions");

if (empty($_SESSION["user_id"]))
	die("User missing");
if (empty($_SESSION["account_id"]))
	die("Please choose an account");

$account_id = $_SESSION["account_id"];

$dest_code		= ( isset($_POST["dest_code"]) ? $_POST["dest_code"] : '' );
$amount			= ( isset($_POST["amount"]) ? $_POST["amount"] : '' );
$description	= ( isset($_POST["description"]) ? $_POST["description"] : '' );
$tan_code		= ( isset($_POST["tan_code"]) ? $_POST["tan_code"] : '' );

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

# sanitize input 
# no need for stage one 

# Verify Operation
$transaction_res = verifyTransaction($account_id, $dest_code, $amount , $description , $tan_code ) ; 
#$transaction_res 	= array(
#	'result'	=> false,
#	'message'	=> '[TAN Code] Tan code has been used'
#	) ; 

#var_dump($transaction_res) ; 	
	
if ($transaction_res["result"] == true ){
	
	#  draw table with all further restuls and mark as verifed and provide button to confrim transaction
	$r = 1 ; 
} else {
	$r = 2 ; 
#	
	$error_message 	= 'Transaction could not be completed ->'. $transaction_res["message"]	; 
	
	echo	'<h3>'.$error_message.'</h3>' ; 
	
	echo 	'<form method="post" action="do_transaction.php">'
		.	'<input type="hidden" name="dest_code" value="'.$dest_code.'">'
		.	'<input type="hidden" name="amount" value="'.$amount.'">'
		.	'<input type="hidden" name="description" value="'.$description.'">'
		.	'<input type="hidden" name="tan_code" value="'.$tan_code.'">'
		.	'<input type="hidden" name="account_id" value="'.$account_id.'">'
        .	'<input type="submit" value="Go Back">'
		.	'</form>' ;	
}


#var_dump($transaction_res) ; 


#drawSingleTransactionTable($transaction_res) ; 


#verifyTANCode($account_id, $tan_code) ;
#ver
# showing restult of verification 



# Charge Extra fee if transfer is to outside Bank 

# performing transaction




?>
