<?php

//Need these two lines because of the warning/errors popping up in the html
ini_set("display_errors",2);
ini_set("error_reporting",E_ALL|E_STRICT);

include 'dbheader.php' ;

//tested
function RecordIsInTable($record_value,$record_name,$table_name)
{
	$SQL_STATEMENT	= "
		SELECT *
		FROM $table_name
		WHERE 
			$record_name = '$record_value'
	" ;

	$result = executeSelectStatement($SQL_STATEMENT);

	if ($result != -1 && sizeof($result) > 0) {
		return true;
	} else {
		return false;
	}
}

//tested
function getUserDetails($user_ID,$filter = null)
{
	global $USER_ROLES;
	global $USER_TABLE_NAME;
	global $USER_TABLE_KEY;
	global $USER_TABLE_ROLE;
	global $USER_TABLE_FIRSTNAME;
	global $USER_TABLE_LASTNAME;
	global $USER_TABLE_EMAIL;
	global $USER_TABLE_STATUS;
	global $USER_TABLE_APPROVER;

    $role = null;
    if (isset($USER_ROLES[$filter])) {
        $role			= $USER_ROLES[$filter] ;
    }

    if ($role != null) {
        $SQL_STATEMENT	= "
		SELECT
			$USER_TABLE_KEY
			, $USER_TABLE_FIRSTNAME
			, $USER_TABLE_LASTNAME
			, $USER_TABLE_EMAIL
			, $USER_TABLE_STATUS
			, $USER_TABLE_ROLE
			, $USER_TABLE_APPROVER
		FROM $USER_TABLE_NAME
		WHERE
			$USER_TABLE_KEY 		= '$user_ID'
			AND $USER_TABLE_ROLE 	= '$role'
	    " ;
    }
    else {
        $SQL_STATEMENT	= "
		SELECT
			$USER_TABLE_KEY
			, $USER_TABLE_FIRSTNAME
			, $USER_TABLE_LASTNAME
			, $USER_TABLE_EMAIL
			, $USER_TABLE_STATUS
			, $USER_TABLE_ROLE
			, $USER_TABLE_APPROVER
		FROM $USER_TABLE_NAME
		WHERE
			$USER_TABLE_KEY 		= '$user_ID'
	    " ;
    }
	
	$result = executeSelectStatementOneRecord($SQL_STATEMENT) ;

	if ($result != -1) {
		return $result;
	} else {
		return false;
	}
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

function getClientBySurname($client_surname)
{
    global $USER_TABLE_NAME;
    global $USER_TABLE_LASTNAME;

    $SQL_STATEMENT = "
        SELECT *
        FROM $USER_TABLE_NAME
        WHERE
            $USER_TABLE_LASTNAME = '$client_surname'
    ";

    $result = executeSelectStatement($SQL_STATEMENT);

    if ($result != -1) {
        return $result;
    } else {
        return false;
    }
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
	
	$result = executeSelectStatement($SQL_STATEMENT) ; 

	if ($result != -1) {
		return $result;
	} else {
		return false;
	}
}

//tested
function getPendingTransactions()
{
	global $TRANSACTION_TABLE_NAME;
	global $TRANSACTION_TABLE_AP_AT;
	global $TRANSACTION_TABLE_AP_BY;

    $SQL_STATEMENT	= "
		SELECT *
		FROM $TRANSACTION_TABLE_NAME
		WHERE $TRANSACTION_TABLE_AP_AT IS NULL
			  OR $TRANSACTION_TABLE_AP_BY IS NULL
	" ;

    $result = executeSelectStatement($SQL_STATEMENT) ;

    if ($result != -1) {
        return $result;
    } else {
        return false;
    }
}

//tested
function getPendingRequests($filter = 'ALL')
{
    global $USER_STATUS;
    global $USER_ROLES;
    global $USER_TABLE_NAME;
    global $USER_TABLE_ROLE;
    global $USER_TABLE_STATUS;

    $status = $USER_STATUS['unapproved'];
    $role = ($filter != null) ? $USER_ROLES[$filter] : null;

    if ($filter == 'ALL') {
        $SQL_STATEMENT = "
		SELECT *
		FROM $USER_TABLE_NAME
		WHERE
			$USER_TABLE_STATUS	    = $status
		";
    } else {
        $SQL_STATEMENT = "
		SELECT *
		FROM $USER_TABLE_NAME
		WHERE
			$USER_TABLE_ROLE 		= $role
			AND $USER_TABLE_STATUS	= $status
		";
    }

    $result = executeSelectStatement($SQL_STATEMENT);

    if ($result != -1) {
        return $result;
    } else {
        return false;
    }
}

//tested
function getPendingClientRequests()
{
    return getPendingRequests('client');
}

//tested
function getPendingEmployeeRequests()
{
    return getPendingRequests('employee');
}

//tested
function verifyTANCode($account_id, $tan_code)
{
    global $TAN_TABLE_NAME;
    global $TAN_TABLE_KEY;
    global $TAN_TABLE_USED_TS;
    global $TAN_TABLE_ACCOUNT_ID;

    $SQL_STATEMENT = "
		SELECT *
		FROM $TAN_TABLE_NAME
		WHERE
			$TAN_TABLE_KEY	= '$tan_code'
			AND $TAN_TABLE_ACCOUNT_ID = '$account_id'
			AND $TAN_TABLE_USED_TS IS NULL
	";

    $result = executeSelectStatement($SQL_STATEMENT);

    if ($result != -1) {
        return $result;
    } else {
        return false;
    }
}

//TODO: Set TAN to used
//TODO: Check if inserted TAN is valid (at insertTAN)
//tested
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
    global $TRANSACTION_TABLE_AP_BY;
    global $FAKE_APPROVER_USER_ID;

    $approved_at = ($amount >= 10000 ? 'NULL' : 'now()');
    $approver = ($amount >= 10000 ? 'NULL' : $FAKE_APPROVER_USER_ID);

    $SQL_STATEMENT = "
		INSERT INTO $TRANSACTION_TABLE_NAME
			(
				$TRANSACTION_TABLE_FROM
				, $TRANSACTION_TABLE_TO
				, $TRANSACTION_TABLE_C_TS
				, $TRANSACTION_TABLE_AMOUNT
				, $TRANSACTION_TABLE_DESC
				, $TRANSACTION_TABLE_TAN
				, $TRANSACTION_TABLE_AP_AT
				, $TRANSACTION_TABLE_AP_BY
			)
		VALUES
			(
				'$src'
				, '$dest'
				, now()
				, '$amount'
				, '$desc'
				, '$tan'
				, $approved_at
				, $approver
			)
	";

    $result = executeAddStatementOneRecord($SQL_STATEMENT);

    if ($result != -1) {
        return $result;
    } else {
        return false;
    }

}

function approvePendingTransaction($approver, $transaction_code)
{
	global $USER_TABLE_KEY;
	global $USER_TABLE_NAME;
	global $TRANSACTION_TABLE_KEY;
	global $TRANSACTION_TABLE_NAME;
	global $TRANSACTION_TABLE_AP_AT;
	global $TRANSACTION_TABLE_AP_BY;

	# Check if approver exists
	if (! RecordIsInTable($approver,$USER_TABLE_KEY, $USER_TABLE_NAME))
	{
		return false;
	}
	
	# Check if transaction code exists and is not approved 
	if (! RecordIsInTable($transaction_code,$TRANSACTION_TABLE_KEY, $TRANSACTION_TABLE_NAME))
	{
		return false; 
	}
	
	#Approve Transactions
	$SQL_STATEMENT	= "
		UPDATE $TRANSACTION_TABLE_NAME
		SET
			$TRANSACTION_TABLE_AP_AT 	= now()
			, $TRANSACTION_TABLE_AP_BY 	= '$approver' 
		
		WHERE
			$TRANSACTION_TABLE_KEY	= '$transaction_code'
	" ;
	//TODO: Set TAN to used (set timestamp)
	return executeSetStatement($SQL_STATEMENT) ; 
}

//tested
function approveUser($approver_id, $user_id, $role_filter)
{
    global $USER_ROLES;
    global $USER_STATUS;
    global $USER_TABLE_NAME;
    global $USER_TABLE_STATUS;
    global $USER_TABLE_APPROVER;
    global $USER_TABLE_KEY;
    global $USER_TABLE_ROLE;

    $role = $USER_ROLES[$role_filter];
    $new_status = $USER_STATUS['approved'];

    #Approve User
    $SQL_STATEMENT = "
		UPDATE $USER_TABLE_NAME
		SET
			$USER_TABLE_STATUS 		= '$new_status'
			, $USER_TABLE_APPROVER 	= '$approver_id' 
		
		WHERE
			$USER_TABLE_KEY			= '$user_id'
			AND $USER_TABLE_ROLE	= '$role'
	";

    $result = executeSetStatement($SQL_STATEMENT);

    if ($result != false && $result == 1) {
        return true;
    } else {
        return false;
    }
}

//tested
function approveEmployee($approver_id, $employee_id)
{
    return approveUser($approver_id, $employee_id, 'employee');
}

//tested
function approveClient($approver_id, $client_id)
{
    return approveUser($approver_id, $client_id, 'client');
}

//tested
function getAccountsForUser($user_id)
{

    //TODO: Check for client status? A: Server side check maybe, instead of db?!

    global $ACCOUNT_TABLE_NAME;
    global $ACCOUNT_TABLE_USER_ID;

    $SQL_STATEMENT = "
		SELECT *
		FROM $ACCOUNT_TABLE_NAME
		WHERE 
			$ACCOUNT_TABLE_USER_ID	= $user_id;
	";

    $result = executeSelectStatement($SQL_STATEMENT);

    if ($result != -1) {
        return $result;
    } else {
        return false;
    }
}

//tested
function addAccountForUser($user_id)
{
    return addAccountForUserWithBalance($user_id, 0);
}

//tested
function addAccountForUserWithBalance($user_id, $balance)
{

    global $ACCOUNT_TABLE_NAME;
    global $ACCOUNT_TABLE_USER_ID;
    global $ACCOUNT_TABLE_BALANCE;

    $SQL_STATEMENT = "
		INSERT
		INTO $ACCOUNT_TABLE_NAME
			( $ACCOUNT_TABLE_USER_ID, $ACCOUNT_TABLE_BALANCE )
		VALUES
			($user_id, $balance) ;
	";
    $result = executeSetStatement($SQL_STATEMENT);

    if ($result != -1) {
        return $result;
    } else {
        return false;
    }
}

//tested
function addUser($first_name, $last_name, $email, $password, $role_filter)
{

    global $USER_ROLES;
    global $USER_TABLE_NAME;
    global $USER_TABLE_FIRSTNAME;
    global $USER_TABLE_LASTNAME;
    global $USER_TABLE_EMAIL;
    global $USER_TABLE_ROLE;
    global $USER_TABLE_SALT;
    global $USER_TABLE_HASH;

    $role = $USER_ROLES[$role_filter];

    //TODO: Generate salt
    //TODO: Generate hash from password

    $SQL_STATEMENT = "
		INSERT INTO $USER_TABLE_NAME
			(
				$USER_TABLE_FIRSTNAME
				, $USER_TABLE_LASTNAME
				, $USER_TABLE_EMAIL
				, $USER_TABLE_ROLE
				, $USER_TABLE_SALT
				, $USER_TABLE_HASH
			)
		VALUES
			(
				'$first_name'
				, '$last_name'
				, '$email'
				, '$role'
				, 'somesalt'
				, '$password'
			)
	";

    $result = executeAddStatementOneRecord($SQL_STATEMENT);

    if ($result != -1) {
        return $result;
    } else {
        return false;
    }
}

//tested
function addClient($first_name, $last_name, $email, $password)
{
    return addUser($first_name, $last_name, $email, $password, 'client');
}

//tested
function addEmployee($first_name, $last_name, $email, $password)
{
    return addUser($first_name, $last_name, $email, $password, 'employee');
}

//tested
function insertTAN($tan, $account_id)
{
    // Inserts TAN in DB
    // Returns true if successful
    // Returns false in case of error

    global $TAN_TABLE_NAME;
    global $TAN_TABLE_KEY;
    global $TAN_TABLE_ACCOUNT_ID;

    $SQL_STATEMENT = "
		INSERT INTO $TAN_TABLE_NAME
			(
				$TAN_TABLE_KEY
				, $TAN_TABLE_ACCOUNT_ID
			)
		VALUES
			(
				'$tan'
				, '$account_id'
			)
	";

    $result = executeAddStatementOneRecord($SQL_STATEMENT);

    if ($result != -1) {
        return true;
    } else {
        return false;
    }
}

function getUser($user_mail, $user_password) {

	global $USER_TABLE_KEY;
	global $USER_TABLE_NAME;
	global $USER_TABLE_EMAIL;
	global $USER_TABLE_HASH;
	global $USER_TABLE_STATUS;

	$hash = ''; //TODO: Use hash

    $SQL_STATEMENT = "
		SELECT $USER_TABLE_KEY
		FROM $USER_TABLE_NAME
		WHERE
			$USER_TABLE_EMAIL = '$user_mail'
			AND
			$USER_TABLE_HASH = '$user_password'
		" ;

	$result = executeSelectStatementOneRecord($SQL_STATEMENT);

	if ($result != -1) {
		return getUserDetails($result[$USER_TABLE_KEY]);
    } else {
		return false;
	}
}

# need to test MN 
function getAccountListForClient($client_ID)
{
	global $BANKACCOUNTS_TABLE_NAME;
	global $BANKACCOUNTS_TABLE_KEY;
	global $BANKACCOUNTS_TABLE_OWNER ;
	global $BANKACCOUNTS_TABLE_AMOUNT ;
	global $USER_TABLE_NAME;
	global $USER_TABLE_KEY;
	
	$SQL_STATEMENT	= "
		SELECT
			b.$BANKACCOUNTS_TABLE_KEY
			,b.$BANKACCOUNTS_TABLE_AMOUNT
			,b.$BANKACCOUNTS_TABLE_OWNER
		FROM 
			$BANKACCOUNTS_TABLE_NAME	b
			,$USER_TABLE_NAME			u
		WHERE 
			u.$USER_TABLE_KEY 		= b.$BANKACCOUNTS_TABLE_OWNER
	" ;
	
	  ;
	 
	return executeSelectStatement($SQL_STATEMENT) ;
}


# need to test MN 
function getAllAccountDetails()
{
	global $BANKACCOUNTS_TABLE_NAME;
	
	$SQL_STATEMENT	= "
		SELECT *
		FROM $BANKACCOUNTS_TABLE_NAME
	" ;
	return executeSelectStatement($SQL_STATEMENT) ; 
}

function verify_transaction($account_id, $dest_code, $amount , $description , $tan_code )
{
	global $BANKACCOUNTS_TABLE_NAME;
	global $BANKACCOUNTS_TABLE_KEY;
	$var_res = array (
		"result"	=> false,
		"message"	=> "[Default] No test has been completed"
	) ; 
	
	$SQL_STATEMENT = "
		SELECT 	* 
		FROM  	$BANKACCOUNTS_TABLE_NAME
		WHERE	$BANKACCOUNTS_TABLE_KEY = $account_id
	" ; 
	
	$account_info 	= executeSelectStatement($SQL_STATEMENT) ;
	
	# checking account ID 
	if (sizeof($account_info) == 0 ){
		$var_res["message"]	= '[Account] account not found' ;
		return $var_res ; 
	}
	
	# Add check for Destination Account  
	# no check needed at this stage 
	
	# Add check for Description  
	# no check needed at this stage 
	
	if ( $amount > $account_info[0]["balance"] ){
		$var_res["message"]	= '[Funds] Insuffecient funds' ;
		return $var_res ; 
	}	

	$tan_ver 	= verifyTANCode($account_id, $tan_code);  
	if (sizeof($tan_ver) == 0 ){
		$var_res["message"]	= '[Tan Code] Invalid or used Tan code' ;
		return $var_res ; 
	}
 
	# Add check for TAN Codes   
	$var_res["message"]	= '[Success] Passed all tests' ;
	return $var_res ; 
	 	
}
