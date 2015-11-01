<?php

require_once __DIR__."/../resource_mappings.php";
require_once getpageabsolute("db_functions");
require_once getPageAbsolute("drawfunctions");

if (empty($_SESSION["user_id"]))
	die("User missing");
if (empty($_SESSION["account_id"]))
	die("Please choose an account");


$account_id 	= $_SESSION["account_id"];
$role			= $_SESSION["role"] ; 
$dest_code		= ( isset($_POST["dest_code"]) ? $_POST["dest_code"] : '' );
$amount			= ( isset($_POST["amount"]) ? $_POST["amount"] : '' );
$description	= ( isset($_POST["description"]) ? $_POST["description"] : '' );
$tan_code		= ( isset($_POST["tan_code"]) ? $_POST["tan_code"] : '' );

if ( isset($_POST["process"]) && $_POST["process"] == 'yes'){
	if ( processTransaction($account_id, $dest_code, $amount, $description, $tan_code) == false)
		die ("Unkonwn Transaction error, please connect our bros for help");
	header("Location:".getPageURL($role));
} 

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
if ($transaction_res["result"] == true ){
	
	# Setting the summary line 
	$summary 		= 'All details verified as of '.date("Y-m-d h:i:sa", $d)  ; 
	## drawing headers
	
 	echo '<table border="1">' 
		. '<thead>'."\n"
		. '<tr>'.'<th colspan="3">'.$summary.'</th>'."\n" 
		. '</tr>'.'</thead>'."\n"
		. '<tr><th>Destination</th>'.$dest_code.'</th></tr>' 
		. '<tr><th>Amount</th>'.$amount.'</th></tr>' 
		. '<tr><th>Description</th>'.$description.'</th></tr>' 
		. '<tr><th>TAN Code</th>'.$tan_code.'</th></tr>' 
		. '</table>' ; 
	
	echo 	'<form method="post" action="">'
		.	'<input type="hidden" name="dest_code" value="'.$dest_code.'">'
		.	'<input type="hidden" name="amount" value="'.$amount.'">'
		.	'<input type="hidden" name="description" value="'.$description.'">'
		.	'<input type="hidden" name="tan_code" value="'.$tan_code.'">'
		.	'<input type="hidden" name="account_id" value="'.$account_id.'">'
		.	'<input type="hidden" name="process" value="yes">'
        .	'<input type="submit" value="Go Back">'
		.	'</form>' ;	
	
	#echo "</table>"."\n" ;
	#  draw table with all further restuls and mark as verifed and provide button to confrim transaction 
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
        .	'<input type="submit" value="Go Back">'
		.	'</form>' ;	
}





?>
