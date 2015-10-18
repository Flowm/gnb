<?php 
$SCHEMA						= "GNBDB"
$USER_TABLE_NAME			= "$SCHEMA.USER" ;
$USER_TABLE_KEY				= "ID" ;
$USER_TABLE_ROLE			= "ROLE"
$USER_TABLE_STATUS			= "STATUS"
$USER_TABLE_APPROVER		= "APPROVED_BY"

$TAN_TABLE_NAME				= "$SCHEMA.TAN" ;
$TAN_TABLE_KEY				= "ID" ;
$TAN_TABLE_ACCOUNT_ID		= "ACCOUNT_ID" ;
$TAN_TABLE_USED_TS			= "USED_TIMESTAMP"

$TRANSACTION_TABLE_NAME		= "$SCHEMA.TRANSACTION" ;
$TRANSACTION_TABLE_KEY		= "ID" ; 
$TRANSACTION_TABLE_TO		= "SOURCE_ACCOUNT_ID" ;
$TRANSACTION_TABLE_FROM		= "DESTINATION_ACCOUNT_ID" ;
$TRANSACTION_TABLE_AP_AT	= "APPROVED_AT" ;
$TRANSACTION_TABLE_AP_BY	= "APPROVED_BY_USER_ID" ;
$TRANSACTION_TABLE_AMOUNT	= "AMOUNT" ;
$TRANSACTION_TABLE_DESC		= "DESCRIPTION" ;
$TRANSACTION_TABLE_TAN		= "TAN_ID" ;
$TRANSACTION_TABLE_C_TS		= "CREATION_TIMESTAMP" ;

#$TRANSACTION_TABLE_STATUS	= "TRANSACTION_TABLE_STATUS" ;
$BANKACCOUNTS_TABLE_NAME	= "$SCHEMA.ACCOUNT" ; 
$BANKACCOUNTS_TABLE_KEY		= "ID" ; 

# ROLES in USER TABLE
$USER_ROLES = array(
	'employee'		=>  0
	,'client'		=>	1
); 
	
# STATUS for USER TABLE
$USER_STATUS = array(
	'unapproved'		=>  0
	,'approved'			=>	1
	,'rejected'			=>	2
	,'blocked'			=>	3
);
	

function executeSelectStatementOneRecord($sql)
{
	return $results, 1;
}

function executeSelectStatement($sql)
{
	return $results,$number_of_rows ;
}
 
function executeAddStatement($sql)
{
	return $results,$number_of_rows ;
}

function executeSetStatement($sql)
{
	return $results,$number_of_rows ;
}



?>