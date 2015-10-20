<?php

$DB_HOST				= "localhost" ;
$DB_USERNAME			= "samurai" ;
$DB_PASSWORD			= "samurai" ;
$DB_SCHEMA				= "gnbdb" ;

$USER_TABLE_NAME		= "$DB_SCHEMA.user" ;
$USER_TABLE_KEY			= "id" ;
$USER_TABLE_ROLE		= "role" ;
$USER_TABLE_STATUS		= "status" ;
$USER_TABLE_APPROVER	= "approved_by" ;

$TAN_TABLE_NAME			= "$DB_SCHEMA.tan" ;
$TAN_TABLE_KEY			= "id" ;
$TAN_TABLE_ACCOUNT_ID	= "account_id" ;
$TAN_TABLE_USED_TS		= "used_timestamp" ;

$TRANSACTION_TABLE_NAME		= "$DB_SCHEMA.transaction" ;
$TRANSACTION_TABLE_KEY		= "id" ;
$TRANSACTION_TABLE_TO		= "source_account_id" ;
$TRANSACTION_TABLE_FROM		= "destination_account_id" ;
$TRANSACTION_TABLE_AP_AT	= "approved_at" ;
$TRANSACTION_TABLE_AP_BY	= "approved_by_user_id" ;
$TRANSACTION_TABLE_AMOUNT	= "amount" ;
$TRANSACTION_TABLE_DESC		= "description" ;
$TRANSACTION_TABLE_TAN		= "tan_id" ;
$TRANSACTION_TABLE_C_TS		= "creation_timestamp" ;

#$TRANSACTION_TABLE_STATUS	= "TRANSACTION_TABLE_STATUS" ;
$BANKACCOUNTS_TABLE_NAME	= "$DB_SCHEMA.account" ;
$BANKACCOUNTS_TABLE_KEY		= "id" ;

# ROLES in USER TABLE
$USER_ROLES = array(
	'client'		=> 0
	,'employee'		=> 1
); 
	
# STATUS for USER TABLE
$USER_STATUS = array(
	'unapproved'	=> 0
	,'approved'		=> 1
	,'rejected'		=> 2
	,'blocked'		=> 3
);
	

function executeSelectStatementOneRecord($sql)
{
	return executeSelectStatement($sql);
}

function executeSelectStatement($sql)
{
	$connection = getDatabaseConnection();

	$result = mysql_query($sql, $connection);

	if ($result == false) {
		$message = 'Invalid query: ' . mysql_error() . '\n';
		$message .= 'Query: ' . $sql;

		die($message);
	} else {
		$data = array(mysql_num_rows($result), mysql_fetch_assoc($result));
	}

	closeDatabaseConnection($connection);
	return $data;
}
 
function executeAddStatement($sql)
{
	$connection = getDatabaseConnection();

	$result = mysql_query($sql, $connection);

	if ($result == false) {
		$message = 'Invalid query: ' . mysql_error() . '\n';
		$message .= 'Query: ' . $sql;

		die($message);
	} else {
		$data = array(mysql_affected_rows());
	}

	closeDatabaseConnection($connection);
	return $data;
}

function executeSetStatement($sql)
{
	return executeAddStatement($sql);
}

function getDatabaseConnection() {

	global $DB_HOST;
	global $DB_USERNAME;
	global $DB_PASSWORD;
	global $DB_SCHEMA;

	$connection = mysql_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD);

	if ($connection == false) {
		return NULL; //TODO: Do something with error message?
	}

	if (mysql_select_db($DB_SCHEMA, $connection) == false) {
		return NULL; //TODO: Do something with error message?
	}
	
	return $connection;
}

function closeDatabaseConnection($connection) {
	mysql_close($connection);
}

?>
