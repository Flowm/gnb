<!DOCTYPE html>
<html><body>

<?php

require_once __DIR__."/db.php";


function bla()
{
	$db = DB::i();

	$result = $db->recordIsInTable('1', 'id', 'user');

	$result = $db->loginUser("barney.stinson@gnb.com", "ThisIsGonnaBeLegendarySoSuitUp");

	$result = $db->addEmployee("Firstname", "Lastname", "alexanderlill@yahoo.de", "asdasdasd");

	$result = $db->getUser($result);

	$result = $db->getEmployee(3);

	$result = $db->getUsersByName("barney");

	$result = $db->getClientsByName("bin");

	$result = $db->getPendingRequests();

	$result = $db->getPendingClientRequests();

	$result = $db->getPendingEmployeeRequests();

	$result = $db->getEmployeeStatus(2);

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

	/*
	$account_id = 10000001; //DB::i()->addAccountWithBalance(3, 555);
	$account = new account(array('id'=>$account_id));
	var_dump($account);

	$tans = $account->generateTANs();

	var_dump($tans);
	*/

	$result = $db->getAccountDetails(10000002);
	$result = $db->getAccountOwnerFromID(10000002);

	$result = $db->processTransaction(10000001, 10000002, 0.01, "bla", "AAAAAAAAAAAAAAC");

	$result = $db->checkTanAndSetUsed(10000001, "AAAAAAAAAAAAAAD");

	var_dump($result);
}

bla();

?>

</body></html>
