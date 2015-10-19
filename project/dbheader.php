<?php

$pdo;

$DB_HOST			= "localhost" ;
$DB_USERNAME			= "samurai" ;
$DB_PASSWORD			= "samurai" ;
$DB_SCHEMA			= "GNBDB" ;

$USER_TABLE_NAME		= "$DB_SCHEMA.USER" ;
$USER_TABLE_KEY			= "ID" ;
$USER_TABLE_ROLE		= "ROLE" ;
$USER_TABLE_STATUS		= "STATUS" ;
$USER_TABLE_APPROVER		= "APPROVED_BY" ;

$TAN_TABLE_NAME			= "$DB_SCHEMA.TAN" ;
$TAN_TABLE_KEY			= "ID" ;
$TAN_TABLE_ACCOUNT_ID		= "ACCOUNT_ID" ;
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
	global $pdo;

	$statement = $pdo->query($sql);
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);	


	return array(1, $results);
}

function executeSelectStatement($sql)
{
	global $pdo;

	$statement = $pdo->query($sql);
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

	return array($statement->rowCount(), $results);
}
 
function executeAddStatement($sql)
{
	$changedOrDeletedLines = $db->exec($sql);
	$insertId = $db->lastInsertId();

	return array($changedOrDeletedLines, $insertId);
}

function executeSetStatement($sql)
{
	return executeAddStatement($sql);
	//return array($number_of_rows, $results);
}

function getDatabaseConnection($host, $schema, $username, $password)
{
	try {
		return new PDO('mysql:host=' . $host . ';dbname=' . $schema . ';charset=utf8', $username, $password);
        } catch(PDOException $ex) {
		//TODO: To something with this error message?
		return NULL;
        }
}

$pdo = getDatabaseConnection($DB_HOST, $DB_SCHEMA, $DB_USERNAME, $DB_PASSWORD);

echo $pdo;

//TODO: Testing only
print_r(executeSelectStatementOneRecord('SELECT * FROM user LIMIT 1;'));

?>
