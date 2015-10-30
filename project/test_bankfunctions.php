<?php

require_once 'bankfunctions.php' ;

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

removeTestTransactions();
removeTestTANs();
removeTestAccounts();
removeTestUsers();


test('Adding test users', addTestUsers(), true);
test('Checking users', checkForTestUsers(), true);
// Check if duplicate users are prohibited
test('Checking user requests', checkTestUserRequests(), 2);

test('Approving test users', approveTestUsers(), true);
test('Checking approved users', checkForApprovedTestUsers(), true);
test('Checking user requests', checkTestUserRequests(), 0);

test('Adding test accounts', addTestAccounts(), true);
test('Checking accounts', checkForTestAccounts(), true);

addTestTANs();
test('Adding TANs', checkForTestTANs(), true);
// Check if duplicate TANs are prohibited

addTestTransactions();
test('Checking transactions', checkForTestTransactions(), true);

approveTransactions();

test('Checking overview functions', checkOverviewFunctions(), true);

//TODO: Test rollback @ processTransaction




removeTestTransactions();
removeTestTANs();
removeTestAccounts();
removeTestUsers();





function test($name, $testfunction, $expectedResult) {

	$style_success = "background-color: green; color: white";
	$style_fail    = "background-color: red; color: black";

	if ($testfunction == $expectedResult) {
		print "<div style=\"$style_success\">SUCCESS @ $name</div><br>";
	} else {
		print "<div style=\"$style_fail\">FAIL    @ $name</div><br>";
	}

}

function addTestUsers() {

	//function addEmployee($first_name, $last_name, $email, $password) 
	//function addClient($first_name, $last_name, $email, $password) 

	global $debug;

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

	if ($debug) print 'Adding Users ' . $USER1_FIRSTNAME . ' and ' . $USER2_FIRSTNAME . '<br>';

	$result = addEmployee($USER1_FIRSTNAME, $USER1_LASTNAME, $USER1_EMAIL, $USER1_PASSWORD);

	if ($result != false) {
		$USER1_ID= $result;
		if ($debug) print "USER1_ID: $USER1_ID<br>";
	} else {
		print "Could not add USER1<br>";
		return false;
	}


	$result = addClient($USER2_FIRSTNAME, $USER2_LASTNAME, $USER2_EMAIL, $USER2_PASSWORD);

	if ($result != false) {
		$USER2_ID= $result;
		if ($debug) print "USER2_ID: $USER2_ID<br>";
	} else {
		print "Could not add USER2<br>";
		return false;
	}

	return true;
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

	if (getEmployee($USER1_ID) == false) return false;
	if (getClient($USER2_ID) == false) return false;
	//FIXME: Notice undefined ofset ... if (getUser($USER1_ID, 'client') != false) return false;
	if (getUser($USER1_ID, 'employee') == false) return false;

	return true;
}

function checkTestUserRequests() {

	//function getPendingClientRequests()
	//function getPendingEmployeeRequests()

	global $debug;

	$numberOfRowsForClients = sizeof(getPendingClientRequests());
	if ($debug) print 'PendingClientRequests: ' . $numberOfRowsForClients . '<br>';

	$numberOfRowsForEmployees = sizeof(getPendingEmployeeRequests());
	if ($debug) print 'PendingEmployeeRequests: ' . $numberOfRowsForEmployees . '<br>';

	return $numberOfRowsForClients + $numberOfRowsForEmployees;
}

function approveTestUsers() {

	//function approveUser($approver_id, $user_id, $role_filter )

	global $debug;

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

	if ($debug) print 'Approving users ' . $USER1_ID . ' and ' . $USER2_ID . '<br>';

	if (approveEmployee(1, $USER1_ID) == false) {
		print 'Could not approve ' . $USER1_ID;
		return false;
	}

	if (approveClient(1, $USER2_ID) == false) {
		print 'Could not approve ' . $USER2_ID;
		return false;
	}

	return true;
}

function checkForApprovedTestUsers() {

	global $debug;

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

	$userinfo = getEmployee($USER1_ID);

	if ($userinfo['status'] == 1 && $userinfo['approved_by_user_id'] != NULL) {
		if ($debug) print 'User ' . $USER1_ID . ' approved!<br>';
	} else {
		print 'User ' . $USER1_ID . ' not approved!<br>';
		return false;
	}

	$userinfo = getClient($USER2_ID);

	if ($userinfo['status'] == 1 && $userinfo['approved_by_user_id'] != NULL) {
		if ($debug) print 'User ' . $USER2_ID . ' approved!<br>';
	} else {
		print 'User ' . $USER2_ID . ' not approved!<br>';
		return false;
	}

	return true;
}

function addTestAccounts() {

	//function addAccount($user_id) {
	//function addAccount($user_id, $balance){

	global $debug;

	global $USER1_ID;
	global $USER2_ID;

	$acc1 = addAccount($USER1_ID);
	$acc2 = addAccountWithBalance($USER2_ID, 5555.55);
	$acc3 = addAccountWithBalance($USER2_ID, 7777.77);

	if ($debug) print "Accounts: $acc1, $acc2, $acc3<br>";

	if ($acc1 < 10000000) return false;
	if ($acc2 < 10000000) return false;
	if ($acc3 < 10000000) return false;

	return true;
}

function checkForTestAccounts() {

	global $debug;

	global $USER1_ID;
	global $USER2_ID;

	$USER1_data = getAccountsForUser($USER1_ID);
	if ($debug) var_dump($USER1_data);

	$USER2_data = getAccountsForUser($USER2_ID);
	if ($debug) var_dump($USER2_data);

	return (sizeof($USER1_data) == 1) && (sizeof($USER2_data) == 2);
}

function addTestTANs() {

	//function insertTAN($tan, $account_id) {

	global $debug;

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
		if ($debug) print "Inserted TAN $USER1_TESTTAN for account $USER1_ACCOUNTID<br>";
	} else {
		print "Could not insert TAN $USER1_TESTTAN for account $USER1_ACCOUNTID!<br>";
	}


    $data = getAccountsForUser($USER2_ID);

    $USER2_ACCOUNTID = $data[0]['id'];

    $result = insertTAN($USER2_TESTTAN, $USER2_ACCOUNTID);

    if ($result != false) {
        if ($debug) print "Inserted TAN $USER2_TESTTAN for account $USER2_ACCOUNTID<br>";
    } else {
        print "Could not insert TAN $USER2_TESTTAN for account $USER2_ACCOUNTID!<br>";
    }

    $data = getAccountsForUser($USER2_ID);

    $USER2_ACCOUNTID = $data[0]['id'];

    $result = insertTAN($USER2_TESTTAN2, $USER2_ACCOUNTID);

    if ($result != false) {
        if ($debug) print "Inserted TAN $USER2_TESTTAN2 for account $USER2_ACCOUNTID<br>";
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

	global $debug;

	if (verifyTANCode($accountid, $tan)) {
		if ($debug) print "Successfully verified TAN $tan for Account $accountid<br>";
		return true;
	} else {
		print "Could not verify TAN $tan for Account $accountid<br>";
		return false;
	}

}

function addTestTransactions() {

	//function processTransaction($src, $dest, $amount, $desc, $tan)

	global $debug;

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
		if ($debug) print "Added transaction NR $result (DESC: $DESC)<br>";
	} else {
		print "Could not add transaction with DESC: $DESC<br>";
	}

	$TEMP = $SRCACC;
	$SRCACC = $DSTACC;
	$DSTACC = $TEMP;

	$DESC   = $TESTPREFIX . '_DESC' . 2;
	$result = processTransaction($SRCACC, $DSTACC, 10000, $DESC, $USER2_TESTTAN);

    if ($result != false) {
		if ($debug) print "Added transaction NR $result (DESC: $DESC)<br>";
    } else {
		print "Could not add transaction with DESC: $DESC<br>";
    }

	$DESC   = $TESTPREFIX . '_DESC' . 3;
	$result = processTransaction($SRCACC, '123', 10000, $DESC, $USER2_TESTTAN2);

    if ($result == false) {
		if ($debug) print "Transaction NR $result (DESC: $DESC) successfully denied!<br>";
    } else {
		print "Could add transaction with duplicate TAN with DESC: $DESC<br>";
    }

	$DESC   = $TESTPREFIX . '_DESC' . 4;
	$result = processTransaction($SRCACC, $DSTACC, 10000, $DESC, $USER2_TESTTAN2);

    if ($result != false) {
		if ($debug) print "Added transaction NR $result (DESC: $DESC)<br>";
    } else {
		print "Could not add transaction with DESC: $DESC<br>";
    }
}

function checkForTestTransactions() {

	global $debug;

	global $USER1_ACCOUNTID;
	global $USER2_ACCOUNTID;

	//function getAccountTransactions($account_ID, $filter ='ALL')

	$trans1 = getAccountTransactions($USER1_ACCOUNTID);
	$trans2 = getAccountTransactions($USER2_ACCOUNTID);
	$trans3 = getAccountTransactions($USER2_ACCOUNTID, 'TO');
	$trans4 = getAccountTransactions($USER2_ACCOUNTID, 'FROM');

	if ($debug) {
		var_dump($trans1);
		print "<br><br>";
		var_dump($trans2);
		print "<br><br>";
		var_dump($trans3);
		print "<br><br>";
		var_dump($trans4);
	}

	if (sizeof($trans1) != 3) return false;
	if (sizeof($trans2) != 4) return false;

	if (sizeof($trans3) != 2) return false;
	if (sizeof($trans4) != 2) return false;

	$pend1 = getPendingTransactions();
	if (sizeof($pend1) != 2) return false;

	$mytrans = getTransaction($pend1[0]['id']);
	if (sizeof($mytrans) != 1) return false;

	return true;
}

function checkOverviewFunctions() {

	if (getNumberOfUsers() == 0) return false;
	if (getNumberOfAccounts() == 0) return false;
	if (getNumberOfTransactions() == 0) return false;
	if (getTotalAmountOfMoney() == 0) return false;

	return true;
}

function approveTransactions() {

	//function approvePendingTransaction($approver,$transaction_id)

	global $USER1_ID;

	$transactions = getPendingTransactions();

	$transaction1 = $transactions[0];
	$transaction_id1 = $transaction1['id'];

	$transaction2 = $transactions[1];
	$transaction_id2 = $transaction2['id'];
	
	if (! approvePendingTransaction($USER1_ID, $transaction_id1)) {return false;};
	if (! rejectPendingTransaction($USER1_ID, $transaction_id2)) {return false;};

	$transactions2 = getPendingTransactions();

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

	executeSetStatement('DELETE FROM transaction WHERE destination_account_id IN (
							SELECT id FROM account WHERE user_id IN (
								SELECT id FROM user WHERE first_name LIKE "%' . $TESTPREFIX . '%"))');
}


?>
