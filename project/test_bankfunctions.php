<?php

include 'bankfunctions.php' ;

$TESTPREFIX = 'MYMAGICSTRING';

$USER1_FIRSTNAME	= $TESTPREFIX . ' FN1';
$USER1_LASTNAME		= $TESTPREFIX . ' LN1';
$USER1_EMAIL		= $TESTPREFIX . 'E1@example.com';
$USER1_ROLE			= 'employee';
$USER1_ID;

$USER2_FIRSTNAME	= $TESTPREFIX . ' FN2';
$USER2_LASTNAME		= $TESTPREFIX . ' LN2';
$USER2_EMAIL		= $TESTPREFIX . 'E2@example.com';
$USER2_ROLE			= 'client';
$USER2_ID;

print 'Running tests...<br>';

removeTestAccounts();
removeTestUsers();

addTestUsers();
if (! checkForTestUsers()) die('Test users not found!1!');

approveTestUsers();
if (! checkForApprovedTestUsers()) die('Test users not approved!');

addTestAccounts();
if (! checkForTestAccounts()) die('Test accounts not found!');



die();




removeTestAccounts();
removeTestUsers();



function addTestUsers() {

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

	print 'Adding Users ' . $USER1_FIRSTNAME . ' and ' . $USER2_FIRSTNAME . '<br>';

	//function addUser($first_name, $last_name, $email, $role_filter) 
	$USER1_ID = addUser($USER1_FIRSTNAME, $USER1_LASTNAME, $USER1_EMAIL, $USER1_ROLE);
	print $USER1_ID . '<br>';

	$USER2_ID = addUser($USER2_FIRSTNAME, $USER2_LASTNAME, $USER2_EMAIL, $USER2_ROLE);
	print $USER2_ID . '<br>';
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

function approveTestUsers() {

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

	//function approveUser($approver_id, $user_id, $role_filter )
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

	global $USER1_ID;
	global $USER2_ID;

//function addAccountForClient($client_id) {
//function addAccountForClient($client_id, $balance){

	addAccountForClient($USER1_ID);
	addAccountForClientWithBalance($USER2_ID, 5555.55);

	return true;

}

function checkForTestAccounts() {

	global $USER1_ID;
	global $USER2_ID;

	list($numberOfRows, $data) = getAccountsForClient($USER1_ID);
	print 'Rows: ' . $numberOfRows . ' Data: ';
	var_dump($data);

	list($numberOfRows, $data) = getAccountsForClient($USER2_ID);
	print 'Rows: ' . $numberOfRows . ' Data: ';
	var_dump($data);

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

?>
