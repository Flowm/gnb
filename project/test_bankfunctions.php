<?php

include 'bankfunctions.php' ;

$TESTPREFIX = 'MYMAGICSTRING';

$USER1_FIRSTNAME	= $TESTPREFIX . ' FN1';
$USER1_LASTNAME		= $TESTPREFIX . ' LN1';
$USER1_EMAIL		= $TESTPREFIX . 'E1@example.com';
$USER1_ROLE			= 'employee';
$USER1_ID;
$USER1_ACCOUNTID;
$USER1_TESTTAN		= '1234567890ABCDE';
$USER1_PASSWORD		= 'supersecret';

$USER2_FIRSTNAME	= $TESTPREFIX . ' FN2';
$USER2_LASTNAME		= $TESTPREFIX . ' LN2';
$USER2_EMAIL		= $TESTPREFIX . 'E2@example.com';
$USER2_ROLE			= 'client';
$USER2_ID;
$USER2_ACCOUNTID;
$USER2_TESTTAN		= 'QWERTZUIOPASDFG';
$USER2_TESTTAN2		= 'TESTTANTESTTAN0';
$USER2_PASSWORD		= 'youwontguess';

print 'Running tests...<br>';

removeTestTransactions();
removeTestTANs();
removeTestAccounts();
removeTestUsers();


addTestUsers();
test('Checking users', checkForTestUsers(), true);
// Check if duplicate users are prohibited
test('Checking user requests', checkTestUserRequests(), 2);

approveTestUsers();
test('Checking approved users', checkForApprovedTestUsers(), true);
test('Checking user requests', checkTestUserRequests(), 0);

addTestAccounts();
test('Checking accounts', checkForTestAccounts(), true);

addTestTANs();
test('Adding TANs', checkForTestTANs(), true);
// Check if duplicate TANs are prohibited

addTestTransactions();
test('Checking transactions', checkForTestTransactions(), true);

approveTransactions();


removeTestTransactions();
removeTestTANs();
removeTestAccounts();
removeTestUsers();







function test($name, $testfunction, $expectedResult) {

	if ($testfunction == $expectedResult) {
		print "<br><br>SUCCESS @ $name<br><br>";
	} else {
		print "<br><br>  FAIL  @ $name<br><br>";
		die();
	}

}

function addTestUsers() {

	//function addEmployee($first_name, $last_name, $email, $password) 
	//function addClient($first_name, $last_name, $email, $password) 

	global $USER1_FIRSTNAME;
	global $USER1_LASTNAME;
	global $USER1_EMAIL;
	global $USER1_ROLE;
	global $USER1_ID;
	global $USER1_PASSWORD;

	global $USER2_FIRSTNAME;
	global $USER2_LASTNAME;
	global $USER2_EMAIL;
	global $USER2_ROLE;
	global $USER2_ID;
	global $USER2_PASSWORD;

	print 'Adding Users ' . $USER1_FIRSTNAME . ' and ' . $USER2_FIRSTNAME . '<br>';

	$result = addEmployee($USER1_FIRSTNAME, $USER1_LASTNAME, $USER1_EMAIL, $USER1_PASSWORD);

	if ($result != false) {
		$USER1_ID= $result;
		print "USER1_ID: $USER1_ID<br>";
	} else {
		print "Could not add USER1<br>";
	}


	$result = addClient($USER2_FIRSTNAME, $USER2_LASTNAME, $USER2_EMAIL, $USER2_PASSWORD);

	if ($result != false) {
		$USER2_ID= $result;
		print "USER2_ID: $USER2_ID<br>";
	} else {
		print "Could not add USER2<br>";
	}
}

function checkForTestUsers() {

	global $USER1_FIRSTNAME;
	global $USER1_LASTNAME;
	global $USER1_EMAIL;
	global $USER1_ROLE;
	global $USER1_ID;

	global $USER2_FIRSTNAME;
	global $USER2_LASTNAME;
	global $USER2_EMAIL;
	global $USER2_ROLE;
	global $USER2_ID;

	global $USER_TABLE_NAME;
	global $USER_TABLE_KEY;

	if (! RecordIsInTable($USER1_ID, $USER_TABLE_KEY, $USER_TABLE_NAME)) return false;
	if (! RecordIsInTable($USER2_ID, $USER_TABLE_KEY, $USER_TABLE_NAME)) return false;

	print_r(getEmployeeDetails($USER1_ID));
	print_r(getClientDetails($USER2_ID));

	return true;
}


function checkTestUserRequests() {

	//function getPendingClientRequests()
	//function getPendingEmployeeRequests()

	$numberOfRowsForClients = sizeof(getPendingClientRequests());
	print 'PendingClientRequests: ' . $numberOfRowsForClients . '<br>';

	$numberOfRowsForEmployees = sizeof(getPendingEmployeeRequests());
	print 'PendingEmployeeRequests: ' . $numberOfRowsForEmployees . '<br>';

	return $numberOfRowsForClients + $numberOfRowsForEmployees;
}




function approveTestUsers() {

	//function approveUser($approver_id, $user_id, $role_filter )

	global $USER1_FIRSTNAME;
	global $USER1_LASTNAME;
	global $USER1_EMAIL;
	global $USER1_ROLE;
	global $USER1_ID;

	global $USER2_FIRSTNAME;
	global $USER2_LASTNAME;
	global $USER2_EMAIL;
	global $USER2_ROLE;
	global $USER2_ID;

	print 'Approving users ' . $USER1_ID . ' and ' . $USER2_ID . '<br>';

	if (approveEmployee(1, $USER1_ID) == false) die('Could not approve ' . $USER1_ID);
	if (approveClient(1, $USER2_ID) == false) die('Could not approve ' . $USER2_ID);
}

function checkForApprovedTestUsers() {

	global $USER1_FIRSTNAME;
	global $USER1_LASTNAME;
	global $USER1_EMAIL;
	global $USER1_ROLE;
	global $USER1_ID;

	global $USER2_FIRSTNAME;
	global $USER2_LASTNAME;
	global $USER2_EMAIL;
	global $USER2_ROLE;
	global $USER2_ID;

	global $USER_TABLE_NAME;
	global $USER_TABLE_KEY;

	$userinfo = getEmployeeDetails($USER1_ID);

	if ($userinfo['status'] == 1 && $userinfo['approved_by_user_id'] != NULL) {
		print 'User ' . $USER1_ID . ' approved!<br>';
	} else {
		print 'User ' . $USER1_ID . ' not approved!<br>';
		return false;
	}

	$userinfo = getClientDetails($USER2_ID);

	if ($userinfo['status'] == 1 && $userinfo['approved_by_user_id'] != NULL) {
		print 'User ' . $USER2_ID . ' approved!<br>';
	} else {
		print 'User ' . $USER2_ID . ' not approved!<br>';
		return false;
	}

	return true;
}

function addTestAccounts() {

	//function addAccountForUser($user_id) {
	//function addAccountForUser($user_id, $balance){

	global $USER1_ID;
	global $USER2_ID;

	addAccountForUser($USER1_ID);
	addAccountForUserWithBalance($USER2_ID, 5555.55);
	addAccountForUserWithBalance($USER2_ID, 7777.77);

	return true;
}

function checkForTestAccounts() {

	global $USER1_ID;
	global $USER2_ID;

	$USER1_data = getAccountsForUser($USER1_ID);
	var_dump($USER1_data);

	$USER2_data = getAccountsForUser($USER2_ID);
	var_dump($USER2_data);

	return (sizeof($USER1_data) == 1) && (sizeof($USER2_data) == 2);
}

function addTestTANs() {

	//function insertTAN($tan, $account_id) {

	global $USER1_ID;
	global $USER1_ACCOUNTID;
	global $USER1_TESTTAN;

	global $USER2_ID;
	global $USER2_ACCOUNTID;
	global $USER2_TESTTAN;
	global $USER2_TESTTAN2;

	$data = getAccountsForUser($USER1_ID);

	$USER1_ACCOUNTID = $data[0]['id'];

	$result = insertTAN($USER1_TESTTAN, $USER1_ACCOUNTID);

	if ($result != false) {
		print "Inserted TAN $USER1_TESTTAN for account $USER1_ACCOUNTID<br>";
	} else {
		print "Could not insert TAN $USER1_TESTTAN for account $USER1_ACCOUNTID!<br>";
	}


    $data = getAccountsForUser($USER2_ID);

    $USER2_ACCOUNTID = $data[0]['id'];

    $result = insertTAN($USER2_TESTTAN, $USER2_ACCOUNTID);

    if ($result != false) {
        print "Inserted TAN $USER2_TESTTAN for account $USER2_ACCOUNTID<br>";
    } else {
        print "Could not insert TAN $USER2_TESTTAN for account $USER2_ACCOUNTID!<br>";
    }

    $data = getAccountsForUser($USER2_ID);

    $USER2_ACCOUNTID = $data[0]['id'];

    $result = insertTAN($USER2_TESTTAN2, $USER2_ACCOUNTID);

    if ($result != false) {
        print "Inserted TAN $USER2_TESTTAN2 for account $USER2_ACCOUNTID<br>";
    } else {
        print "Could not insert TAN $USER2_TESTTAN2 for account $USER2_ACCOUNTID!<br>";
    }
}

function checkForTestTANs() {

	global $USER1_ACCOUNTID;
	global $USER1_TESTTAN;

	global $USER2_ACCOUNTID;
	global $USER2_TESTTAN;
	global $USER2_TESTTAN2;

	if (!checkTAN($USER1_ACCOUNTID, $USER1_TESTTAN)) return false;
	if (!checkTAN($USER2_ACCOUNTID, $USER2_TESTTAN)) return false;
	if (!checkTAN($USER2_ACCOUNTID, $USER2_TESTTAN2)) return false;

	return true;
}

function checkTAN($accountid, $tan) {

	//function verifyTANCode($account_id,$tan_code)

	if (verifyTANCode($accountid, $tan)) {
		print "Successfully verified TAN $tan for Account $accountid<br>";
		return true;
	} else {
		print "Could not verify TAN $tan for Account $accountid<br>";
		return false;
	}

}

function addTestTransactions() {

	//function processTransaction($src, $dest, $amount, $desc, $tan)

	global $USER1_ID;
	global $USER2_ID;
	global $USER1_TESTTAN;
	global $USER2_TESTTAN;
	global $USER2_TESTTAN2;
	global $TESTPREFIX;

	$SRCACCOUNT = getAccountsForUser($USER1_ID);
	$SRCACC = $SRCACCOUNT[0]['id'];

	$DSTACCOUNT = getAccountsForUser($USER2_ID);
	$DSTACC = $DSTACCOUNT[0]['id'];

	$DESC   = $TESTPREFIX . '_DESC' . 1;
	$result = processTransaction($SRCACC, $DSTACC, 1000, $DESC, $USER1_TESTTAN);

	if ($result != false) {
		print "Added transaction NR $result (DESC: $DESC)<br>";
	} else {
		print "Could not add transaction with DESC: $DESC<br>";
	}

	$TEMP = $SRCACC;
	$SRCACC = $DSTACC;
	$DSTACC = $TEMP;

	$DESC   = $TESTPREFIX . '_DESC' . 2;
	$result = processTransaction($SRCACC, $DSTACC, 10000, $DESC, $USER2_TESTTAN);

    if ($result != false) {
		print "Added transaction NR $result (DESC: $DESC)<br>";
    } else {
		print "Could not add transaction with DESC: $DESC<br>";
    }

	$DESC   = $TESTPREFIX . '_DESC' . 3;
	$result = processTransaction($SRCACC, $DSTACC, 10000, $DESC, $USER2_TESTTAN2);

    if ($result != false) {
		print "Added transaction NR $result (DESC: $DESC)<br>";
    } else {
		print "Could not add transaction with DESC: $DESC<br>";
    }
}

function checkForTestTransactions() {

	global $USER1_ACCOUNTID;
	global $USER2_ACCOUNTID;

	//function getAccountTransactions($account_ID, $filter ='ALL')
	if (sizeof(getAccountTransactions($USER1_ACCOUNTID)) != 3) {return false;};
	if (sizeof(getAccountTransactions($USER2_ACCOUNTID)) != 3) {return false;};

	if (sizeof(getAccountTransactions($USER2_ACCOUNTID, 'TO')) != 1) {return false;};
	if (sizeof(getAccountTransactions($USER2_ACCOUNTID, 'FROM')) != 2) {return false;};

	if (sizeof(getPendingTransactions()) != 2) {return false;};

	return true;
}

function approveTransactions() {

	//function approvePendingTransaction($approver,$transaction_code)

	global $USER1_ID;

	$transactions = getPendingTransactions();
	$transaction = $transactions[0];
	$transaction_id = $transaction['id'];
	
	if (! approvePendingTransaction($USER1_ID, $transaction_id)) {return false;};

	return true;

}

function removeTestUsers() {

	global $TESTPREFIX;

	executeSetStatement('DELETE FROM user WHERE first_name LIKE "%' . $TESTPREFIX . '%"');
}

function removeTestAccounts() {

	global $TESTPREFIX;

	executeSetStatement('DELETE FROM account WHERE user_id IN (
						 SELECT id FROM user WHERE first_name LIKE "%' . $TESTPREFIX . '%")');
}

function removeTestTANs() {

	executeSetStatement('DELETE FROM tan WHERE account_id IN (
						 SELECT ACC.id FROM account AS ACC JOIN user AS U ON ACC.user_id = U.id
							WHERE U.first_name LIKE "%MYMAGICSTRING%");');
}

function removeTestTransactions() {

	global $TESTPREFIX;

	executeSetStatement('DELETE FROM transaction WHERE description LIKE "%' . $TESTPREFIX . '%"');
}


?>
