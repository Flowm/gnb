<?php

$DB_HOST				= "localhost" ;
$DB_USERNAME			= "samurai" ;
$DB_PASSWORD			= "samurai" ;
$DB_SCHEMA				= "gnbdb" ;

$USER_TABLE_NAME		= "$DB_SCHEMA.user" ;
$USER_TABLE_KEY			= "id" ;
$USER_TABLE_ROLE		= "role" ;
$USER_TABLE_STATUS		= "status" ;
$USER_TABLE_APPROVER	= "approved_by_user_id" ;
$USER_TABLE_FIRSTNAME	= "first_name" ;
$USER_TABLE_LASTNAME	= "last_name" ;
$USER_TABLE_EMAIL		= "email" ;
$USER_TABLE_SALT		= "pw_salt" ;
$USER_TABLE_HASH		= "pw_hash" ;

$TAN_TABLE_NAME			= "$DB_SCHEMA.tan" ;
$TAN_TABLE_KEY			= "id" ;
$TAN_TABLE_ACCOUNT_ID	= "account_id" ;
$TAN_TABLE_USED_TS		= "used_timestamp" ;

$TRANSACTION_TABLE_NAME		= "$DB_SCHEMA.transaction" ;
$TRANSACTION_TABLE_KEY		= "id" ;
$TRANSACTION_TABLE_TO		= "destination_account_id" ;
$TRANSACTION_TABLE_FROM		= "source_account_id" ;
$TRANSACTION_TABLE_AP_AT	= "approved_at" ;
$TRANSACTION_TABLE_AP_BY	= "approved_by_user_id" ;
$TRANSACTION_TABLE_AMOUNT	= "amount" ;
$TRANSACTION_TABLE_DESC		= "description" ;
$TRANSACTION_TABLE_TAN		= "tan_id" ;
$TRANSACTION_TABLE_C_TS		= "creation_timestamp" ;

#$TRANSACTION_TABLE_STATUS	= "TRANSACTION_TABLE_STATUS" ;
$ACCOUNT_TABLE_NAME			= "$DB_SCHEMA.account" ;
$ACCOUNT_TABLE_KEY			= "id" ;
$ACCOUNT_TABLE_BALANCE		= "balance" ;
$ACCOUNT_TABLE_USER_ID		= "user_id" ;

$FAKE_APPROVER_USER_ID		= 0;


# ROLES in USER TABLE
$USER_ROLES = array(
	'client'		=> 0,
	'employee'		=> 1
); 
	
# STATUS for USER TABLE
$USER_STATUS = array(
	'unapproved'	=> 0,
	'approved'		=> 1,
	'rejected'		=> 2,
	'blocked'		=> 3
);


# Reversed ROLES in USER TABLE
$R_USER_ROLES = array(
	0	=> 'client',
	1	=> 'employee'
); 
	
# Reversed STATUS for USER TABLE
$R_USER_STATUS = array(
	0	=> 'unapproved',
	1	=> 'approved',
	2	=> 'rejected',
	3	=> 'blocked'
);
	

	

function executeSelectStatementOneRecord($sql)
{
	$data = executeSelectStatement($sql);
	$result = $data[0];

	return $result;
}

function executeSelectStatement($sql)
{
	$connection = getDatabaseConnection();

	$result = mysql_query($sql, $connection);

	if ($result == false) {

		$message = 'Invalid query: ' . mysql_error() . '<br>';
		$message .= 'Query: ' . $sql . '<br>';

		closeDatabaseConnection($connection);
		print "ERROR: $message";
		return -1;

	} else {

		$data = array();
		while($row = mysql_fetch_assoc($result)) {
			$data[] = $row;
		}
	}

	closeDatabaseConnection($connection);
	return $data;
}
 
function executeAddStatementOneRecord($sql)
{
	$connection = getDatabaseConnection();

	$result = mysql_query($sql, $connection);

	if ($result == false) {

		$message = 'Invalid query: ' . mysql_error() . '<br>';
		$message .= 'Query: ' . $sql . '<br>';

		closeDatabaseConnection($connection);
		print "ERROR: $message";
		return -1;

	} else {
		$data = mysql_insert_id();
	}

	closeDatabaseConnection($connection);
	return $data;
}

function executeSetStatement($sql)
{
	$connection = getDatabaseConnection();

	$result = mysql_query($sql, $connection);

	if ($result == false) {
		$message = 'Invalid query: ' . mysql_error() . '<br>';
		$message .= 'Query: ' . $sql . '<br>';

		closeDatabaseConnection($connection);
		print "ERROR: $message";
		return -1;
	} else {
		$data = mysql_affected_rows();
	}

	closeDatabaseConnection($connection);
	return $data;
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
