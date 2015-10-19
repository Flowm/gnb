<?php

include 'dbheader.php' ; 

function RecordInTable($record_value,$record_name,$table_name)
{
	$SQL_STATEMENT	= "
		SELECT *
		FROM $table_name
		WHERE 
			$record_name = '$record_value'
	" ;
	if (is_null(executeSelectStatement($SQL_STATEMENT)))
	{
		return false;
	}
	else 
	{
		return true ;  
	}
}


# Function to get user details ( Employee or Client ) 
function getUserDetails($user_ID,$filter)
{
	$role			= $USER_ROLES($filter) ; 
	$SQL_STATEMENT	= "
		SELECT *
		FROM $USER_TABLE_NAME
		WHERE 
			$USER_TABLE_KEY 		= '$user_ID'
			AND $USER_TABLE_ROLE 	= '$role'
	" ;
	return executeSelectStatement($SQL_STATEMENT) ; 
}

function getClientDetails($client_ID)
{
	return getUserDetails($client_ID,'client') ;
}

function getEmployeeDetails($employee_ID)
{
	return getUserDetails($employee_ID,'employee') ;
}

function getAccountTransactions($account_ID, $filter ='ALL')
{
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
	$SQL_STATEMENT	= "
		SELECT *
		FROM $TRANSACTION_TABLE_NAME
		WHERE $TRANSACTION_TABLE_AP_AT is EMPTY
	" ;
	return executeSelectStatement($SQL_STATEMENT) ; 
}

function getPendingRequests($filter)
{
	$status = $USER_STATUS('unapproved') ; 
	$role	= $USER_ROLES($filter) ; 
	
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
	$SQL_STATEMENT	= "
		SELECT *
		FROM $TAN_TABLE_NAME
		WHERE
			$TAN_TABLE_KEY	= '$tan_code'
			AND $TAN_TABLE_USED_TS is EMPTY
	" ;
	return executeSelectStatement($SQL_STATEMENT) ; 
}
	
function processTransaction($src, $dest, $ammount, $desc, $tan)
{
	
	$approved_at	= ( amount >= 10000 ? 'NULL' : 'now' ) ; 
	
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
				, 'now'
				, '$ammount'
				, '$desc'
				, '$tan'
				, $approved_at
			)
	" ; 
	executeAddStatement($SQL_STATEMENT) ; 
	
}
	
function approvePendingTransaction($approver,$transaction_code)
{
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
			$TRANSACTION_TABLE_AP_AT 	= 'now'
			,$TRANSACTION_TABLE_AP_BY 	= '$approver' 
		
		WHERE
			$TRANSACTION_TABLE_KEY	= '$transaction_code'
	" ;
	return executeSetStatement($SQL_STATEMENT) ; 
}
	

function approveUser($approver_id, $user_id, $role_filter )
{

	$role		= USER_ROLES($role_filter)	; 
	$new_status	= USER_STATUS('approved')	; 
	
	#Approve User
	$SQL_STATEMENT	= "
		UPDATE $USER_TABLE_NAME
		SET
			$USER_TABLE_STATUS 			= '$new_status'
			,$USER_TABLE_APPROVER 		= '$approver_id' 
		
		WHERE
			$USER_TABLE_KEY			= '$user_id'
			AND $USER_TABLE_ROLE	= '$role'
	" ;
	return executeSetStatement($SQL_STATEMENT) ; 
}	
	
function approveEmployee($approver_id, $employee_id )
{	
	approveUser($approver_id, $employee_id, 'employee' ) ;
	# Send mail function here
}	

function approveClient($approver_id, $client_id )
{	
	approveUser($approver_id, $client_id, 'client' ) ; 
	addAccountClient($client_id) ;	
	
	# Send mail function here
	

	//generateTANCodes(
}	

function addAccountClient($client_id){

	# insert code about crearting account 
}


function generateTANCodes($account_id)
{
	# generate codes 
	
	# upload codes in DB
	
	# get Client ID from account ID 
	
	# Send client email
}




