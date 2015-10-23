<?php

include 'dbheader.php' ; 


global $USER_TABLE_NAME;
global $USER_TABLE_KEY;
global $USER_TABLE_ROLE;
global $USER_TABLE_STATUS;
global $USER_TABLE_APPROVER;

global $TAN_TABLE_NAME;
global $TAN_TABLE_KEY;
global $TAN_TABLE_ACCOUNT_ID;
global $TAN_TABLE_USED_TS;

global $TRANSACTION_TABLE_NAME;
global $TRANSACTION_TABLE_KEY;
global $TRANSACTION_TABLE_TO;
global $TRANSACTION_TABLE_FROM;
global $TRANSACTION_TABLE_AP_AT;
global $TRANSACTION_TABLE_AP_BY;
global $TRANSACTION_TABLE_AMOUNT;
global $TRANSACTION_TABLE_DESC;
global $TRANSACTION_TABLE_TAN;
global $TRANSACTION_TABLE_C_TS;

global $BANKACCOUNTS_TABLE_NAME;
global $BANKACCOUNTS_TABLE_KEY;

//tested
function RecordIsInTable($record_value,$record_name,$table_name)
{
	$SQL_STATEMENT	= "
		SELECT *
		FROM $table_name
		WHERE 
			$record_name = '$record_value'
	" ;

	list($rows, $data) = executeSelectStatement($SQL_STATEMENT);

	if ($rows == 1) {
		return true;
	}
	else 
	{
		return false;  
	}
}

//tested
function getUserDetails($user_ID,$filter)
{
	global $USER_ROLES;
	global $USER_TABLE_NAME;
	global $USER_TABLE_KEY;
	global $USER_TABLE_ROLE;

	$role			= $USER_ROLES[$filter] ; 
	$SQL_STATEMENT	= "
		SELECT *
		FROM $USER_TABLE_NAME
		WHERE 
			$USER_TABLE_KEY 		= '$user_ID'
			AND $USER_TABLE_ROLE 	= '$role'
	" ;
	
	list($numberOfRows, $data) = executeSelectStatementOneRecord($SQL_STATEMENT) ;

	return $data;
}

//tested
function getClientDetails($client_ID)
{
	return getUserDetails($client_ID,'client') ;
}

//tested
function getEmployeeDetails($employee_ID)
{
	return getUserDetails($employee_ID,'employee') ;
}

function getAccountTransactions($account_ID, $filter ='ALL')
{
	global $TRANSACTION_TABLE_TO;
	global $TRANSACTION_TABLE_FROM;
	global $TRANSACTION_TABLE_NAME;

	# only get transfers to said account 
	if ( $filter == 'TO' )
	{
		$where = "WHERE $TRANSACTION_TABLE_TO = '$account_ID'" ; 
	}	
	# only get transfers from said account 
	elseif ( $filter == 'FROM' )
	{
		$where = "WHERE $TRANSACTION_TABLE_FROM = '$account_ID'" ; 
	}
	# get all transfers for said account 
	else
	{
		$where = "
		WHERE 
			$TRANSACTION_TABLE_TO 		= '$account_ID'
			OR $TRANSACTION_TABLE_FROM 	= '$account_ID'
		" ; 	
	} 
	
	$SQL_STATEMENT	= "
		SELECT *
		FROM $TRANSACTION_TABLE_NAME
		$where
	" ;
	return executeSelectStatement($SQL_STATEMENT) ; 
}

function getPendingTransactions()
{
	global $TRANSACTION_TABLE_NAME;
	global $TRANSACTION_TABLE_AP_AT;

	$SQL_STATEMENT	= "
		SELECT *
		FROM $TRANSACTION_TABLE_NAME
		WHERE $TRANSACTION_TABLE_AP_AT is EMPTY
	" ;
	return executeSelectStatement($SQL_STATEMENT) ; 
}

function getPendingRequests($filter)
{
	global $USER_STATUS;
	global $USER_ROLES;
	global $USER_TABLE_NAME;
	global $USER_TABLE_ROLE;
	global $USER_TABLE_STATUS;

	$status = $USER_STATUS['unapproved'] ; 
	$role	= $USER_ROLES[$filter] ; 
	
	$SQL_STATEMENT	= "
		SELECT *
		FROM $USER_TABLE_NAME
		WHERE 
			$USER_TABLE_ROLE 		= $role
			AND $USER_TABLE_STATUS	= $status
	" ;
	return executeSelectStatement($SQL_STATEMENT) ; 
}

function getPendingClientRequests()
{	
	return getPendingRequests('client') ; 
}

function getPendingEmployeeRequests()
{
	return getPendingRequests('employee') ; 
}

function verifyTANCode($account_id,$tan_code)
{
	global $TAN_TABLE_NAME;
	global $TAN_TABLE_KEY;
	global $TAN_TABLE_USED_IS;

	$SQL_STATEMENT	= "
		SELECT *
		FROM $TAN_TABLE_NAME
		WHERE
			$TAN_TABLE_KEY	= '$tan_code'
			AND $TAN_TABLE_USED_TS is EMPTY
	" ;
	return executeSelectStatement($SQL_STATEMENT) ; 
}
	
function processTransaction($src, $dest, $amount, $desc, $tan)
{
	global $TRANSACTION_TABLE_NAME;
	global $TRANSACTION_TABLE_FROM;
	global $TRANSACTION_TABLE_TO;
	global $TRANSACTION_TABLE_C_TS;
	global $TRANSACTION_TABLE_AMOUNT;
	global $TRANSACTION_TABLE_DESC;
	global $TRANSACTION_TABLE_TAN;
	global $TRANSACTION_TABLE_AP_AT;
	
	$approved_at	= ( $amount >= 10000 ? 'NULL' : 'now()' ) ; 
	
	$SQL_STATEMENT	= "
		INSERT INTO $TRANSACTION_TABLE_NAME
			(
				$TRANSACTION_TABLE_FROM
				,$TRANSACTION_TABLE_TO
				,$TRANSACTION_TABLE_C_TS
				,$TRANSACTION_TABLE_AMOUNT
				,$TRANSACTION_TABLE_DESC
				,$TRANSACTION_TABLE_TAN
				,$TRANSACTION_TABLE_AP_AT
			)
		VALUES
			(
				'$src'
				, '$dest'
				, 'now()'
				, '$amount'
				, '$desc'
				, '$tan'
				, $approved_at
			)
	" ; 
	$transactionId = executeAddStatementOneRecord($SQL_STATEMENT) ; 
	
}
	
function approvePendingTransaction($approver,$transaction_code)
{
	global $USER_TABLE_KEY;
	global $USER_TABLE_NAME;
	global $TRANSACTION_TABLE_KEY;
	global $TRANSACTION_TABLE_NAME;
	global $TRANSACTION_TABLE_AP_AT;
	global $TRANSACTION_TABLE_AP_BY;

	# Check if approver exists
	if (! RecordInTable($approver,$USER_TABLE_KEY, $USER_TABLE_NAME))
	{
		return null ; 
	}
	
	# Check if transaction code exists and is not approved 
	if (! RecordInTable($transaction_code,$TRANSACTION_TABLE_KEY, $TRANSACTION_TABLE_NAME))
	{
		return null ; 
	}
	
	#Approve Transactions
	$SQL_STATEMENT	= "
		UPDATE $TRANSACTION_TABLE_NAME
		SET
			$TRANSACTION_TABLE_AP_AT 	= 'now()'
			,$TRANSACTION_TABLE_AP_BY 	= '$approver' 
		
		WHERE
			$TRANSACTION_TABLE_KEY	= '$transaction_code'
	" ;
	return executeSetStatement($SQL_STATEMENT) ; 
}
	
//tested
function approveUser($approver_id, $user_id, $role_filter )
{
	global $USER_ROLES;
	global $USER_STATUS;
	global $USER_TABLE_NAME;
	global $USER_TABLE_STATUS;
	global $USER_TABLE_APPROVER;
	global $USER_TABLE_KEY;
	global $USER_TABLE_ROLE;

	$role		= $USER_ROLES[$role_filter]	; 
	$new_status	= $USER_STATUS['approved']	; 
	
	#Approve User
	$SQL_STATEMENT	= "
		UPDATE $USER_TABLE_NAME
		SET
			$USER_TABLE_STATUS 		= '$new_status'
			,$USER_TABLE_APPROVER 	= '$approver_id' 
		
		WHERE
			$USER_TABLE_KEY			= '$user_id'
			AND $USER_TABLE_ROLE	= '$role'
	" ;
	if (executeSetStatement($SQL_STATEMENT) == 1) {
		return true;
	} else {
		return false;
	} 
}	

//tested
function approveEmployee($approver_id, $employee_id )
{	
	return approveUser($approver_id, $employee_id, 'employee' ) ;
}	

//tested
function approveClient($approver_id, $client_id )
{	
	return approveUser($approver_id, $client_id, 'client' ) ; 
}	

function getAccountsForClient($client_id) {

	//TODO: Check for client status?

	global $ACCOUNT_TABLE_NAME;
	global $ACCOUNT_TABLE_USER_ID;

	$SQL_STATEMENT	= "
		SELECT *
		FROM $ACCOUNT_TABLE_NAME
		WHERE 
			$ACCOUNT_TABLE_USER_ID	= $client_id;
	" ;
	return executeSelectStatement($SQL_STATEMENT) ; 	
}

function addAccountForClient($client_id) {
	return addAccountForClientWithBalance($client_id, 0);
}

function addAccountForClientWithBalance($client_id, $balance) {

	global $ACCOUNT_TABLE_NAME;
	global $ACCOUNT_TABLE_USER_ID;
	global $ACCOUNT_TABLE_BALANCE;

	$SQL_STATEMENT	= "
		INSERT
		INTO $ACCOUNT_TABLE_NAME ( $ACCOUNT_TABLE_USER_ID, $ACCOUNT_TABLE_BALANCE )
		VALUES
			($client_id, $balance) ;
	" ;
	return executeSelectStatement($SQL_STATEMENT) ; 	
}

//tested
function addUser($first_name, $last_name, $email, $role_filter) { //TODO: Add salt and Hash?

	global $USER_ROLES;
	global $USER_TABLE_NAME;
	global $USER_TABLE_FIRSTNAME;
	global $USER_TABLE_LASTNAME;
	global $USER_TABLE_EMAIL;
	global $USER_TABLE_ROLE;
	global $USER_TABLE_SALT;
	global $USER_TABLE_HASH;

	$role       = $USER_ROLES[$role_filter] ;

	$SQL_STATEMENT	= "
		INSERT INTO $USER_TABLE_NAME
			(
				$USER_TABLE_FIRSTNAME
				,$USER_TABLE_LASTNAME
				,$USER_TABLE_EMAIL
				,$USER_TABLE_ROLE
				,$USER_TABLE_SALT
				,$USER_TABLE_HASH
			)
		VALUES
			(
				'$first_name'
				, '$last_name'
				, '$email'
				, '$role'
				, 'somesalt'
				, 'somehash'
			)
	" ; 
	return executeAddStatementOneRecord($SQL_STATEMENT) ;
}	

//tested
function addClient($first_name, $last_name, $email) { //TODO: Add salt and Hash?
	return addUser($first_name, $last_name, $email, 'client');
}

//tested
function addEmployee($first_name, $last_name, $email) { //TODO: Add salt and Hash?
	return addUser($first_name, $last_name, $email, 'employee');
}

function insertTAN($account_id, $tan) {
	// Inserts TAN in DB
	// Returns true if successful
	// Returns false in case of error
}
