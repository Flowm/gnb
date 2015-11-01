<?php

//Need these two lines because of the warning/errors popping up in the html
ini_set("display_errors",2);
ini_set("error_reporting",E_ALL|E_STRICT);

require_once "dbheader.php";
require_once "resource_mappings.php";
require_once getPageAbsolute("account");

$WELCOMECREDIT_DESCRIPTION = 'GNB Welcome Credit';
$MAGIC = 'SUITUP';



/************************************************
 * MISC FUNCTIONS
 ************************************************/

//tested
function recordIsInTable($record_value,$record_name,$table_name)
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


function loginUser($mail, $password) {

	global $USER_TABLE_KEY;
	global $USER_TABLE_NAME;
	global $USER_TABLE_EMAIL;
	global $USER_TABLE_SALT;
	global $USER_TABLE_HASH;
	global $USER_TABLE_STATUS;
	global $USER_STATUS;

	$SQL_STATEMENT_SALT = "
		SELECT $USER_TABLE_SALT
		FROM $USER_TABLE_NAME
		WHERE $USER_TABLE_EMAIL = '$mail'
	";

	$result = executeSelectStatementOneRecord($SQL_STATEMENT_SALT);

	if ($result == false) {
		return false;
	}

	$salt = $result[$USER_TABLE_SALT];
	$password_hash = getPasswordHash($password, $salt);

	$status = $USER_STATUS['approved'];

    $SQL_STATEMENT = "
		SELECT $USER_TABLE_KEY
		FROM $USER_TABLE_NAME
		WHERE
			$USER_TABLE_EMAIL = '$mail'
			AND
			$USER_TABLE_HASH = '$password_hash'
			AND
			$USER_TABLE_STATUS = '$status'
	" ;

	$result = executeSelectStatementOneRecord($SQL_STATEMENT);

	//TODO: Return specific error message (unapproved, rejected, blocked)

	if ($result != -1) {
		return getUser($result[$USER_TABLE_KEY]);
    } else {
		return false;
	}
}

function genRandString($length) {
	return substr(bin2hex(openssl_random_pseudo_bytes(round($length/2))), 0, $length);
}

function getPasswordHash($password, $salt) {
	global $MAGIC;

	return hash('sha512', $MAGIC . $password . $salt);
}

/************************************************
 * /MISC FUNCTIONS
 ************************************************/



/************************************************
 * USER FUNCTIONS
 ************************************************/

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

	$salt = genRandString(8);
	$password_hash = getPasswordHash($password, $salt);

    $SQL_STATEMENT = "
		INSERT INTO $USER_TABLE_NAME
			(
				$USER_TABLE_FIRSTNAME,
				$USER_TABLE_LASTNAME,
				$USER_TABLE_EMAIL,
				$USER_TABLE_ROLE,
				$USER_TABLE_SALT,
				$USER_TABLE_HASH
			)
		VALUES
			(
				'$first_name',
				'$last_name',
				'$email',
				'$role',
				'$salt',
				'$password_hash'
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
function getUser($user_ID, $filter = "")
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

	$where = "";
	if (isset($USER_ROLES[$filter])) {
		$role = $USER_ROLES[$filter] ;
		$where = "AND $USER_TABLE_ROLE = $role";
	}

	$SQL_STATEMENT	= "
	SELECT
		$USER_TABLE_KEY,
		$USER_TABLE_FIRSTNAME,
		$USER_TABLE_LASTNAME,
		$USER_TABLE_EMAIL,
		$USER_TABLE_STATUS,
		$USER_TABLE_ROLE,
		$USER_TABLE_APPROVER
	FROM $USER_TABLE_NAME
	WHERE
		$USER_TABLE_KEY 		= '$user_ID'
		$where
	" ;

	$result = executeSelectStatementOneRecord($SQL_STATEMENT) ;

	if ($result != -1) {
		return $result;
	} else {
		return false;
	}
}

//tested
function getClient($client_ID)
{
	return getUser($client_ID, 'client') ;
}

//tested
function getEmployee($employee_ID)
{
	return getUser($employee_ID, 'employee') ;
}

function getClientsByName($name)
{
	return getUsersByName($name, 'client');
}

function getEmployeesByName($name)
{
	return getUsersByName($name, 'employee');
}

function getUsersByName($name, $role_filter)
{
	global $USER_TABLE_NAME;
	global $USER_TABLE_KEY;
	global $USER_TABLE_ROLE;
	global $USER_TABLE_FIRSTNAME;
	global $USER_TABLE_LASTNAME;
	global $USER_TABLE_EMAIL;
	global $USER_TABLE_STATUS;
	global $USER_TABLE_APPROVER;
	global $USER_ROLES;

	$role = $USER_ROLES[$role_filter];

	$SQL_STATEMENT	= "
	SELECT
		$USER_TABLE_KEY,
		$USER_TABLE_FIRSTNAME,
		$USER_TABLE_LASTNAME,
		$USER_TABLE_EMAIL,
		$USER_TABLE_STATUS,
		$USER_TABLE_ROLE,
		$USER_TABLE_APPROVER
	FROM $USER_TABLE_NAME
	WHERE
		(
			$USER_TABLE_FIRSTNAME LIKE '%$name%'
			OR
			$USER_TABLE_LASTNAME LIKE '%$name%'
		) AND
			$USER_TABLE_ROLE = '$role'
	";

    $result = executeSelectStatement($SQL_STATEMENT);

    if ($result != -1) {
        return $result;
    } else {
        return false;
    }
}

//tested
function getPendingRequests($filter = "")
{
    global $USER_STATUS;
    global $USER_ROLES;
    global $USER_TABLE_NAME;
    global $USER_TABLE_ROLE;
    global $USER_TABLE_STATUS;

	$status = $USER_STATUS['unapproved'];

    $where = "";
    if (isset($USER_ROLES[$filter])) {
        $role = $USER_ROLES[$filter] ;
        $where = "AND $USER_TABLE_ROLE = $role";
    }

    $SQL_STATEMENT = "
    SELECT *
    FROM $USER_TABLE_NAME
    WHERE
        $USER_TABLE_STATUS	    = '$status'
        $where
    ";

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
function approveEmployee($employee_id, $approver_id)
{
    return approveUser($employee_id, $approver_id, 'employee');
}

//tested
function approveClient($client_id, $approver_id)
{
    return approveUser($client_id, $approver_id, 'client');
}

//tested
function rejectEmployee($employee_id, $rejector_id)
{
	return rejectUser($employee_id, $rejector_id, 'employee');
}

//tested
function rejectClient($client_id, $rejector_id)
{
	return rejectUser($client_id, $rejector_id, 'client');
}

//tested
function blockEmployee($employee_id, $blocker_id)
{
	return blockUser($employee_id, $blocker_id, 'employee');
}

//tested
function blockClient($client_id, $blocker_id)
{
	return blockUser($client_id, $blocker_id, 'client');
}

//tested
function approveUser($user_id, $approver_id, $role_filter)
{
	global $USER_STATUS;

	$new_status = $USER_STATUS['approved'];
	return changeUserStatus($user_id, $new_status, $approver_id, $role_filter);
}

//tested
function rejectUser($user_id, $rejector_id, $role_filter)
{
	global $USER_STATUS;

	$new_status = $USER_STATUS['rejected'];
	return changeUserStatus($user_id, $new_status, $rejector_id, $role_filter);
}

//tested
function blockUser($user_id, $blocker_id, $role_filter)
{
	global $USER_STATUS;

	$new_status = $USER_STATUS['blocked'];
	return changeUserStatus($user_id, $new_status, $blocker_id, $role_filter);
}

//tested
function changeUserStatus($user_id, $new_status, $processor_id, $role_filter)
{
    global $USER_ROLES;
    global $USER_STATUS;
    global $USER_TABLE_NAME;
    global $USER_TABLE_STATUS;
    global $USER_TABLE_APPROVER;
    global $USER_TABLE_KEY;
    global $USER_TABLE_ROLE;

    $role = $USER_ROLES[$role_filter];

	//TODO: Check if approver is approved employee

    $SQL_STATEMENT = "
		UPDATE $USER_TABLE_NAME
		SET
			$USER_TABLE_STATUS 		= '$new_status',
			$USER_TABLE_APPROVER 	= '$processor_id' 
		WHERE
			$USER_TABLE_KEY			= '$user_id'
			AND $USER_TABLE_ROLE	= '$role'
			AND $USER_TABLE_STATUS != '$new_status'
	";

    $result = executeSetStatement($SQL_STATEMENT);

    if ($result != -1 && $result == 1) {
        return true;
    } else {
        return false;
    }
}

/************************************************
 * /USER FUNCTIONS
 ************************************************/



/************************************************
 * ACCOUNT FUNCTIONS
 ************************************************/

# need to test MN 
function getAccountDetails($account_ID)
{
	global $ACCOUNTOVERVIEW_TABLE_NAME;
	global $ACCOUNTOVERVIEW_TABLE_KEY;
	global $ACCOUNTOVERVIEW_TABLE_BALANCE ;
	
	$SQL_STATEMENT	= "
		SELECT 
			$ACCOUNTOVERVIEW_TABLE_KEY,
			$ACCOUNTOVERVIEW_TABLE_BALANCE
		FROM $ACCOUNTOVERVIEW_TABLE_NAME
		WHERE $ACCOUNTOVERVIEW_TABLE_KEY 	= '$account_ID'
	" ;
	
	$result	= executeSelectStatementOneRecord($SQL_STATEMENT) ;
	return $result ; 
}

//tested
function getAccountsForUser($user_id)
{
    //TODO: Check for client status? A: Server side check maybe, instead of db?!

	global $ACCOUNTOVERVIEW_TABLE_KEY;
    global $ACCOUNTOVERVIEW_TABLE_NAME;
	global $ACCOUNTOVERVIEW_TABLE_USER_ID;
	global $ACCOUNTOVERVIEW_TABLE_BALANCE;

    $SQL_STATEMENT = "
		SELECT
			$ACCOUNTOVERVIEW_TABLE_KEY,
			$ACCOUNTOVERVIEW_TABLE_BALANCE
		FROM $ACCOUNTOVERVIEW_TABLE_NAME
		WHERE
			$ACCOUNTOVERVIEW_TABLE_USER_ID	= '$user_id';
	";

    $result = executeSelectStatement($SQL_STATEMENT);

    if ($result != -1) {
        return $result;
    } else {
        return false;
    }
}

// needs to be tested
function getAccountOwnerFromID($account_id){

	global $USER_TABLE_NAME;
	global $USER_TABLE_KEY;
	global $ACCOUNT_TABLE_NAME;
	global $ACCOUNT_TABLE_KEY;
	global $ACCOUNT_TABLE_USER_ID;
	
	$SQL_STATEMENT	= "
		SELECT
			CONCAT(u.FIRST_NAME,' ',u.LAST_NAME )		\"Name\",
			u.$USER_TABLE_KEY							\"User ID\"
		FROM 
			$ACCOUNT_TABLE_NAME		b
			,$USER_TABLE_NAME		u
		WHERE 
			u.$USER_TABLE_KEY 				= b.$ACCOUNT_TABLE_USER_ID 
			AND b.$ACCOUNT_TABLE_KEY		= '$account_id'
	" ;
	
	$result 	= executeSelectStatementOneRecord($SQL_STATEMENT);
	return $result ;
}

//tested
function addAccount($user_id)
{
    global $ACCOUNT_TABLE_NAME;
    global $ACCOUNT_TABLE_USER_ID;

    $SQL_STATEMENT = "
		INSERT
		INTO $ACCOUNT_TABLE_NAME
			( $ACCOUNT_TABLE_USER_ID )
		VALUES
			( $user_id ) ;
	";

    $result = executeAddStatementOneRecord($SQL_STATEMENT);

	if ($result != -1) {
        return $result;
    } else {
        return false;
    }
}

//tested
function addAccountWithBalance($user_id, $balance)
{
	global $WELCOMECREDIT_DESCRIPTION;
	global $FAKE_APPROVER_USER_ID;

	$new_account_id = addAccount($user_id);

	if ($new_account_id != false) {

		$accounts_admin = getAccountsForUser($FAKE_APPROVER_USER_ID);
		$account_admin = $accounts_admin[0];
		$src_account = $account_admin['id'];

		$account = new account($account_admin);

		$tans = $account->generateTANs(1);
		$tan = $tans[0];

		$result = processTransaction($src_account, $new_account_id, $balance, $WELCOMECREDIT_DESCRIPTION, $tan);

		if ($balance >= 10000) {
			approvePendingTransaction($FAKE_APPROVER_USER_ID, $result);
		}

		return $new_account_id;
	}

	executeSetStatement("ROLLBACK");
	return false;
}

/************************************************
 * /ACCOUNT FUNCTIONS
 ************************************************/



/************************************************
 * TAN FUNCTIONS
 ************************************************/

//tested
function insertTAN($tan, $account_id)
{
    global $TAN_TABLE_NAME;
    global $TAN_TABLE_KEY;
    global $TAN_TABLE_ACCOUNT_ID;

	if (strlen($tan) > 15) {
		return false;
	}
	
    $SQL_STATEMENT = "
		INSERT INTO $TAN_TABLE_NAME
			(
				$TAN_TABLE_KEY,
				$TAN_TABLE_ACCOUNT_ID
			)
		VALUES
			(
				'$tan',
				'$account_id'
			)
	";

    $result = executeAddStatementOneRecord($SQL_STATEMENT);

    if ($result != -1) {
        return true;
    } else {
        return false;
    }
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

/************************************************
 * /TAN FUNCTIONS
 ************************************************/



/************************************************
 * TRANSACTION FUNCTIONS
 ************************************************/

function getTransaction($transaction_id)
{
	global $TRANSACTION_TABLE_NAME;
	global $TRANSACTION_TABLE_KEY;

	$SQL_STATEMENT = "
		SELECT *
		FROM $TRANSACTION_TABLE_NAME
		WHERE
			$TRANSACTION_TABLE_KEY = '$transaction_id'
	";

	$result = executeSelectStatementOneRecord($SQL_STATEMENT);

	if ($result != -1) {
		return $result;
	} else {
		return false;
	}
}

function getAccountTransactions($account_ID, $filter = "ALL")
{
    global $TRANSACTION_TABLE_TO;
    global $TRANSACTION_TABLE_FROM;
    global $TRANSACTION_TABLE_NAME;

    $where = "";
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

# need to test MN 
function verifyTransaction($account_id, $dest_code, $amount, $description, $tan_code)
{
	global $ACCOUNTOVERVIEW_TABLE_NAME;
	global $ACCOUNTOVERVIEW_TABLE_KEY;

	$var_res = array (
		"result"	=> false,
		"message"	=> "[Default] No test has been completed"
	) ; 
	
	$SQL_STATEMENT = "
		SELECT 	* 
		FROM  	$ACCOUNTOVERVIEW_TABLE_NAME
		WHERE	$ACCOUNTOVERVIEW_TABLE_KEY = '$account_id'
	" ; 
	
	$account_info 	= executeSelectStatement($SQL_STATEMENT) ;
	
	# checking account ID 
	if (sizeof($account_info) == 0 ){
		$var_res["message"]	= '[Account] Account not found' ;
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
	$var_res["result"] = true;
	$var_res["message"]	= '[Success] Passed all tests' ;

	return $var_res ; 
}

//tested onld version
function getPendingTransactions()
{
	global $TRANSACTION_STATUS;
	global $TRANSACTION_TABLE_NAME;
	global $TRANSACTION_TABLE_AP_AT;
	global $TRANSACTION_TABLE_AP_BY;
	global $TRANSACTION_TABLE_STATUS;

	$status = $TRANSACTION_STATUS['unapproved'];

    $SQL_STATEMENT	= "
		SELECT *
		FROM $TRANSACTION_TABLE_NAME
		WHERE
			$TRANSACTION_TABLE_STATUS	= '$status'
	" ;

    $result = executeSelectStatement($SQL_STATEMENT) ;

    if ($result != -1) {
        return $result;
    } else {
        return false;
    }
}

//tested older version
function processTransaction($source, $destination, $amount, $description, $tan)
{
	global $TAN_TABLE_NAME;
	global $TAN_TABLE_KEY;
	global $TAN_TABLE_USED_TS;
	global $TAN_TABLE_ACCOUNT_ID;

	global $TRANSACTION_STATUS;

    global $TRANSACTION_TABLE_NAME;
    global $TRANSACTION_TABLE_FROM;
    global $TRANSACTION_TABLE_TO;
    global $TRANSACTION_TABLE_C_TS;
    global $TRANSACTION_TABLE_AMOUNT;
    global $TRANSACTION_TABLE_DESC;
    global $TRANSACTION_TABLE_TAN;
    global $TRANSACTION_TABLE_AP_AT;
	global $TRANSACTION_TABLE_AP_BY;
	global $TRANSACTION_TABLE_STATUS;

	global $ACCOUNTOVERVIEW_TABLE_KEY;
	global $ACCOUNTOVERVIEW_TABLE_NAME;
	global $ACCOUNTOVERVIEW_TABLE_BALANCE;

    global $FAKE_APPROVER_USER_ID;

	$approved_at	= 'NULL';
	$approved_by	= 'NULL';
	$status			= '0';

	if ($amount < 0) {
		return false;
	}	

	if ($amount < 10000) {
		$approved_at	= 'now()';
		$approved_by	= $FAKE_APPROVER_USER_ID;
		$status			= $TRANSACTION_STATUS['approved'];
	}

	executeSetStatement("START TRANSACTION;");

	$SQL_STATEMENT_CHECK_BALANCE = "
		SELECT
			$ACCOUNTOVERVIEW_TABLE_KEY
		FROM
			$ACCOUNTOVERVIEW_TABLE_NAME
		WHERE
			$ACCOUNTOVERVIEW_TABLE_KEY = '$source'
			AND
			$ACCOUNTOVERVIEW_TABLE_BALANCE >= '$amount'
	";

	$sufficient_balance = executeSelectStatement($SQL_STATEMENT_CHECK_BALANCE);

	if (sizeof($sufficient_balance) == 0) {
		return false;
	}

	$SQL_STATEMENT_SET_TAN_USED = "
		UPDATE
			$TAN_TABLE_NAME
		SET
			$TAN_TABLE_USED_TS = now()
		WHERE
			$TAN_TABLE_KEY ='$tan'
			AND $TAN_TABLE_ACCOUNT_ID ='$source'
			AND $TAN_TABLE_USED_TS IS NULL
	";

	$affected_rows = executeSetStatement($SQL_STATEMENT_SET_TAN_USED);

	if ($affected_rows == 1) {

		$SQL_STATEMENT_ADD_TRANSACTION = "
			
			INSERT INTO $TRANSACTION_TABLE_NAME
				(
					$TRANSACTION_TABLE_FROM,
					$TRANSACTION_TABLE_TO,
					$TRANSACTION_TABLE_C_TS,
					$TRANSACTION_TABLE_AMOUNT,
					$TRANSACTION_TABLE_DESC,
					$TRANSACTION_TABLE_TAN,
					$TRANSACTION_TABLE_AP_AT,
					$TRANSACTION_TABLE_AP_BY,
					$TRANSACTION_TABLE_STATUS
				)
			VALUES
				(
					'$source',
					'$destination a',
					now(),
					'$amount',
					'$description',
					'$tan',
					$approved_at,
					$approved_by,
					$status
				)
		";

		$result = executeAddStatementOneRecord($SQL_STATEMENT_ADD_TRANSACTION);

		if ($result != -1) {
			executeSetStatement("COMMIT");
			return $result;
		}
	}

	executeSetStatement("ROLLBACK");
}

function approvePendingTransaction($processor, $transaction_id)
{
	global $TRANSACTION_STATUS;

	$status = $TRANSACTION_STATUS['approved'];
	return processPendingTransaction($transaction_id, $processor, $status);
}

function rejectPendingTransaction($processor, $transaction_id)
{
	global $TRANSACTION_STATUS;

	$status = $TRANSACTION_STATUS['rejected'];
	return processPendingTransaction($transaction_id, $processor, $status);
}

function processPendingTransaction($transaction_id, $processor, $status)
{
	global $TRANSACTION_STATUS;

	global $TRANSACTION_TABLE_KEY;
	global $TRANSACTION_TABLE_NAME;
	global $TRANSACTION_TABLE_AP_AT;
	global $TRANSACTION_TABLE_AP_BY;
	global $TRANSACTION_TABLE_STATUS;

	$old_status = $TRANSACTION_STATUS['unapproved'];

	//TODO: Check if approver is approved employee
	
	$SQL_STATEMENT	= "
		UPDATE $TRANSACTION_TABLE_NAME
		SET
			$TRANSACTION_TABLE_AP_AT 	= now(),
			$TRANSACTION_TABLE_AP_BY 	= '$processor',
			$TRANSACTION_TABLE_STATUS	= $status
		WHERE
			$TRANSACTION_TABLE_KEY	= '$transaction_id asd'
			AND $TRANSACTION_TABLE_STATUS = '$old_status'
	" ;

	$result = executeSetStatement($SQL_STATEMENT) ;

	if ($result != -1 && $result == 1) {
		return true;
	} else {
		return false;
	}
}

/************************************************
 * /TRANSACTION FUNCTIONS
 ************************************************/



/************************************************
 * OVERVIEW FUNCTIONS
 ************************************************/

//tested
function getNumberOfUsers()
{
	global $USER_TABLE_KEY;
	global $USER_TABLE_NAME;

	$col = 'count';

	$SQL_STATEMENT = "
		SELECT
			count($USER_TABLE_KEY) as $col
		FROM
			$USER_TABLE_NAME
	" ;

	$result = executeSelectStatementOneRecord($SQL_STATEMENT);

	if ($result != -1) {
		return $result[$col];
	} else {
		return 0;
	}
}

function getNumberOfAccounts()
{
	global $ACCOUNT_TABLE_KEY;
	global $ACCOUNT_TABLE_NAME;

	$col = 'count';

	$SQL_STATEMENT = "
		SELECT
			count($ACCOUNT_TABLE_KEY) as $col
		FROM
			$ACCOUNT_TABLE_NAME
	" ;

	$result = executeSelectStatementOneRecord($SQL_STATEMENT);

	if ($result != -1) {
		return $result[$col];
	} else {
		return 0;
	}
}

function getNumberOfTransactions()
{
	global $TRANSACTION_TABLE_KEY;
	global $TRANSACTION_TABLE_NAME;

	$col = 'count';

	$SQL_STATEMENT = "
		SELECT
			count($TRANSACTION_TABLE_KEY) as $col
		FROM
			$TRANSACTION_TABLE_NAME
	" ;

	$result = executeSelectStatementOneRecord($SQL_STATEMENT);

	if ($result != -1) {
		return $result[$col];
	} else {
		return 0;
	}
}

function getTotalAmountOfMoney()
{
	global $ACCOUNTOVERVIEW_TABLE_NAME;
	global $ACCOUNTOVERVIEW_TABLE_BALANCE;
	global $ACCOUNTOVERVIEW_TABLE_USER_ID;
	global $FAKE_APPROVER_USER_ID;

	$col = 'sum';

	$SQL_STATEMENT = "
		SELECT
			sum($ACCOUNTOVERVIEW_TABLE_BALANCE) as $col
		FROM
			$ACCOUNTOVERVIEW_TABLE_NAME
		WHERE
			$ACCOUNTOVERVIEW_TABLE_USER_ID != '$FAKE_APPROVER_USER_ID'
	" ;

	$result = executeSelectStatementOneRecord($SQL_STATEMENT);

	if ($result != -1) {
		return $result['sum'];
	} else {
		return 0;
	}
}

/************************************************
 * /OVERVIEW FUNCTIONS
 ************************************************/
