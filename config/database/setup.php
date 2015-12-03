<?php

require_once __DIR__ . "/../../project/resource_mappings.php";
require_once getpageabsolute("db_functions");

main();

function main() {

	//executeSetStatement("DROP DATABASE gnbdb ;");
	//executeSetStatement("source " . __DIR__ . "/gnbdb_create.sql ;");


	//function addEmployee($first_name, $last_name, $email, $password)
	$barney = DB::i()->addEmployee("Barney", "Stinson", "barney.stinson@gnb.com", "ThisIsGonnaBeLegendarySoSuitUp");
	$ted = DB::i()->addEmployee("Ted", "Mosby", "ted.mosby@gnb.com", "WhoSaysThat");

	print "BARNEY: $barney<br>";
	print "TED: $ted<br>";

	//function addClient($first_name, $last_name, $email, $password)
	$robin = DB::i()->addClient("Robin", "Scherbatsky", "robin@robinsparkles.com", "SandcastlesInTheSand");

	print "ROBIN: $robin<br>";


	//function approveEmployee($employee_id, $approver_id)
	DB::i()->approveEmployee($barney, $barney);
	DB::i()->approveEmployee($ted, $barney);

	//function addAccountWithBalance($user_id, $balance)
	DB::i()->approveClient($robin, $ted);


	$account_id_barney = DB::i()->addAccount($barney);
	$account_id_robin = DB::i()->addAccountWithBalance($robin, 15000);

	print "ACC_BARNEY: $account_id_barney<br>";
	print "ACC_ROBIN: $account_id_robin<br>";

    $account_robin = new account(array("id" => $account_id_robin));
	$tans_robin = $account_robin->generateTANs(100);
}
