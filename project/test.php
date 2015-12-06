<!DOCTYPE html>
<html><body>

<?php

require_once __DIR__."/db.php";

function dump($name, $function)
{
	echo "$name ";
	echo var_dump($function) . "<br/>";
}


function bla()
{
	$db = DB::i();
/*
	$result = $db->recordIsInTable('1', 'id', 'user');

	$result = $db->loginUser("barney.stinson@gnb.com", "ThisIsGonnaBeLegendarySoSuitUp");

	$result = $db->addEmployee("Firstname", "Lastname", "alexanderlill@yahoo.de", "asdasdasd", "111111");

	$result = $db->getUser($result);

	$result = $db->getEmployee(3);

	dump("getUsersByName", $db->getUsersByName("barney"));

	$result = $db->getClientsByName("bin");

	dump("getUnapprovedUsers", $db->getUnapprovedUsers());

	dump("getRejectedUsers", $db->getRejectedUsers());

	dump("getBlockedUsers", $db->getBlockedUsers());

	dump("getEmployeeStatus", $db->getEmployeeStatus(2));

	$result = $db->getAccountDetails(10000002);

	$result = $db->getAccountsForUser(3);

	$result = $db->getAccountOwnerFromID(10000002);

	$result = $db->addAccount(3);

	$result = $db->addAccountWithBalance(3, 44);

	$result = $db->getTransaction(15);

	$result = $db->getAccountTransactions(10000001, "FROM");

	$result = $db->verifyTransaction(10000001, 10000002, 0.00, "bla", "ff26cb836af1b3a");

	$result = $db->getPendingTransactions();

	$result= $db->approvePendingTransaction(2, 5);
	$result= $db->rejectPendingTransaction(1, 6);

	$result = $db->getPendingTransactions();

	echo "getNumberOfUsers" . $db->getNumberOfUsers() . "<br/>";
	echo "getNumberOfAccounts" . $db->getNumberOfAccounts() . "<br/>";
	echo "getNumberOfTransactions" . $db->getNumberOfTransactions() . "<br/>";
	echo "getTotalAmountOfMoney" . $db->getTotalAmountOfMoney() . "<br/>";

	$result = $db->insertTAN("AAAAAAAAAAAAAAE", 10000001);

	
	$account_id = 10000001; //DB::i()->addAccountWithBalance(3, 555);
	$account = new account(array('id'=>$account_id));
	var_dump($account);

	$tans = $account->generateTANs();

	var_dump($tans);
	

	$result = $db->getAccountDetails(10000002);
	$result = $db->getAccountOwnerFromID(10000002);

	$result = $db->processTransaction(10000001, 10000002, 0.01, "bla", "AAAAAAAAAAAAAAC");

	$result = $db->checkTanAndSetUsed(10000001, "AAAAAAAAAAAAAAD");

	$result = $db->mapAuthenticationDevice(0);
*/

	//dump("addFailedLoginAttempt", $db->addFailedLoginAttempt(1));
	//dump("getNumberOfFailedLoginAttempts", $db->getNumberOfFailedLoginAttempts(1));

/*
	dump("approveUser", $db->approveUser(1, 2));
	dump("getBlockedUsers", $db->getBlockedUsers());
	dump("handleSuccessfulLogin", $db->handleSuccessfulLogin(1));

	dump("getNumberOfFailedLoginAttempts", $db->getNumberOfFailedLoginAttempts(1));

	dump("handleFailedLogin", $db->handleFailedLogin(1));
	dump("getNumberOfFailedLoginAttempts", $db->getNumberOfFailedLoginAttempts(1));
	dump("getBlockedUsers", $db->getBlockedUsers());

	dump("handleFailedLogin", $db->handleFailedLogin(1));
	dump("getNumberOfFailedLoginAttempts", $db->getNumberOfFailedLoginAttempts(1));
	dump("getBlockedUsers", $db->getBlockedUsers());

	dump("handleFailedLogin", $db->handleFailedLogin(1));
	dump("getNumberOfFailedLoginAttempts", $db->getNumberOfFailedLoginAttempts(1));
	dump("getBlockedUsers", $db->getBlockedUsers());

	dump("handleFailedLogin", $db->handleFailedLogin(1));
	dump("getNumberOfFailedLoginAttempts", $db->getNumberOfFailedLoginAttempts(1));
	dump("getBlockedUsers", $db->getBlockedUsers());

	dump("handleFailedLogin", $db->handleFailedLogin(1));
	dump("getNumberOfFailedLoginAttempts", $db->getNumberOfFailedLoginAttempts(1));
	dump("getBlockedUsers", $db->getBlockedUsers());

	dump("handleFailedLogin", $db->handleFailedLogin(1));
	dump("getNumberOfFailedLoginAttempts", $db->getNumberOfFailedLoginAttempts(1));
	dump("getBlockedUsers", $db->getBlockedUsers());
*/

/*
	dump("getBlockedUsers", $db->getBlockedUsers());
	dump("approveUser", $db->approveUser(1, 2));
	dump("getBlockedUsers", $db->getBlockedUsers());

	dump("loginUser", $db->loginUser("barney.stinson@gnb.com", "ThisIsGonnaBeLegendarySoSuitUp"));
*/

/*
	dump("loginUser", $db->loginUser("barney.stinson@gnb.com", "asd"));
	dump("getBlockedUsers", $db->getBlockedUsers());

	dump("loginUser", $db->loginUser("barney.stinson@gnb.com", "asd"));
	dump("getBlockedUsers", $db->getBlockedUsers());

	dump("loginUser", $db->loginUser("barney.stinson@gnb.com", "asd"));
	dump("getBlockedUsers", $db->getBlockedUsers());

	dump("loginUser", $db->loginUser("barney.stinson@gnb.com", "asd"));
	dump("getBlockedUsers", $db->getBlockedUsers());

	dump("loginUser", $db->loginUser("barney.stinson@gnb.com", "asd"));
	dump("getBlockedUsers", $db->getBlockedUsers());
*/

	//dump("setLastTANTime", $db->setLastTANTime(10000003, 678));

	//dump("setPasswordResetHash", $db->setPasswordResetHash(3, "HASHHASHHASH"));
	//dump("resetPassword", $db->resetPassword(3, "asdasdasd", "HASHHASHHASHaaaaaaaaa"));
	dump("resetPassword", $db->resetPassword(3, "asdasdasd", "HASHHASHHASH"));


}

bla();

?>

</body></html>
