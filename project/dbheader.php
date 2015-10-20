<?php

$DB_HOST				= "localhost" ;
$DB_USERNAME			= "samurai" ;
$DB_PASSWORD			= "samurai" ;
$DB_SCHEMA				= "GNBDB" ;

$USER_TABLE_NAME		= "$DB_SCHEMA.USER" ;
$USER_TABLE_KEY			= "ID" ;
$USER_TABLE_ROLE		= "ROLE" ;
$USER_TABLE_STATUS		= "STATUS" ;
$USER_TABLE_APPROVER		= "APPROVED_BY" ;

$TAN_TABLE_NAME			= "$DB_SCHEMA.TAN" ;
$TAN_TABLE_KEY			= "ID" ;
$TAN_TABLE_ACCOUNT_ID	= "ACCOUNT_ID" ;
$TAN_TABLE_USED_TS		= "USED_TIMESTAMP" ;

$TRANSACTION_TABLE_NAME		= "$DB_SCHEMA.TRANSACTION" ;
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
$BANKACCOUNTS_TABLE_NAME	= "$DB_SCHEMA.ACCOUNT" ;
$BANKACCOUNTS_TABLE_KEY		= "ID" ;

# ROLES in USER TABLE
$USER_ROLES = array(
	'employee'		=> 0
	,'client'		=> 1
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
		$data = array(mysql_num_rows(), mysql_fetch_assoc($result));
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

	if (mysql_select_db($DB_SCHEMA, $connection)) {
		return NULL; //TODO: Do something with error message?
	}

	return $connection;
}

function closeDatabaseConnection($connection) {
	mysql_close($connection);
}

?>
