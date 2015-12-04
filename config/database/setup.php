<?php

require_once __DIR__ . "/../../project/resource_mappings.php";
require_once getpageabsolute("db_functions");

main();

function main() {

	//function addEmployee($first_name, $last_name, $email, $password)
	$barney = DB::i()->addEmployee("Barney", "Stinson", "barney.stinson@gnb.com", "ThisIsGonnaBeLegendarySoSuitUp", "267391");
	$ted = DB::i()->addEmployee("Ted", "Mosby", "ted.mosby@gnb.com", "WhoSaysThat", "906293");

	print "BARNEY: $barney<br>";
	print "TED: $ted<br>";

	//function addClient($first_name, $last_name, $email, $password)
	$robin = DB::i()->addClient("Robin", "Scherbatsky", "robin@robinsparkles.com", "SandcastlesInTheSand", "906090", "TANs");
	$marshall = DB::i()->addClient("Marshall", "Eriksen", "marshall@theeriksens.com", "ILikeOlives", "198001", "SCS");

	print "ROBIN: $robin<br>";
	print "MARSHALL: $marshall<br>";

	$status = DB::i()->mapUserStatus('approved');
	$approver = DB::i()->FAKE_APPROVER_USER_ID;
	DB::i()->executeSetStatement("UPDATE " . DB::i()->USER_TABLE_NAME . "
									SET
										" . DB::i()->USER_TABLE_APPROVER . "= '$status',
										" . DB::i()->USER_TABLE_STATUS . "= '$approver'
									WHERE
										" . DB::i()->USER_TABLE_KEY . "= '$approver'");

	//function approveEmployee($employee_id, $approver_id)
	DB::i()->approveEmployee($ted, $barney);

	//function addAccountWithBalance($user_id, $balance)
	DB::i()->approveClient($robin, $ted);
	DB::i()->approveClient($marshall, $ted);


	$account_id_barney = DB::i()->addAccount($barney);
	$account_id_robin = DB::i()->addAccountWithBalance($robin, 15000);
	$account_id_marshall = DB::i()->addAccountWithBalance($marshall, 15000);

	print "ACC_BARNEY: $account_id_barney<br>";
	print "ACC_ROBIN: $account_id_robin<br>";
	print "ACC_MARSHALL: $account_id_marshall<br>";

    $account_robin = new account(array("id" => $account_id_robin));
	$tans_robin = $account_robin->generateTANs(100);
}

?>