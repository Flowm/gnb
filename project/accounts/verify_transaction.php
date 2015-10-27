<?php


include_once ('../main_include.php') ; 





# Check that user is allowed to transfer money for said account 
# not needed for phase 1 

# get input 
foreach ($_POST as $var => $value )
{
	$$var	= $value ; 
}
var_dump($_POST) ;
# for testing 
$account_id=10000001 ;
$dest_code='IBAN213213123' ;
$description='Test Transaction' ;
$amount=50 ;
$tan_code='1312321321' ;
 


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
$transaction_res = verify_transaction($account_id, $dest_code, $amount , $description , $tan_code ) ; 

#var_dump($transaction_res) ; 


#drawSingleTransactionTable($transaction_res) ; 


#verifyTANCode($account_id, $tan_code) ;
#ver
# showing restult of verification 



# Charge Extra fee if transfer is to outside Bank 

# performing transaction




?>