<?php


include_once ('../main_include.php') ; 





# Check that user is allowed to transfer money for said account 
# not needed for phase 1 

# get input 
foreach ($_POST as $var => $value ){
	$$var	= $value ; 
}
#var_dump($_POST) ;
# for testing 
#$account_id=10000001 ;
##$account_id=100 ;
#$dest_code='IBAN213213123' ;
#$description='Test Transaction' ;
#$amount=500 ;
#
#$tan_code='1234567890ABCDE' ;			# invalid
#$tan_code='04696eac02be2ae' ;         	# valid


# verify all input is there 
if (!isset($account_id)){
	die("Account ID not found");
}	

if (!isset($dest_code)){
	die("Destination Code not found");
}	

if (!isset($amount)){
	die("Amount not found");
}	

if (!isset($description)){
	die("Description Code not found");
}	

if (!isset($tan_code)){
	die("TAN Code not found");
}	

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
