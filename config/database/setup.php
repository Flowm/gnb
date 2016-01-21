<?php

require_once __DIR__ . "/resource_mappings.php";
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

	$smartcardtan = DB::i()->insertTAN(DB::i()->SCS_DEFAULT_TAN, $account_id_barney);
	print "SMARTCARDTAN: $smartcardtan<br>";

	print "ROBINTAN: " . DB::i()->insertTAN("01546170ae97cd6", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("017d0fa100215eb", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("0329bf3db000b22", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("03b058f670dd21c", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("06bd019ad85702d", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("0ff916e7abeee78", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("12b8424aace40eb", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("12bc2bcac3d5593", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("14910324123c95c", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("14bae2d2e58d39b", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("17e96f35791d029", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("1cd84a263a0a334", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("1db0835a74001ff", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("201910bad79b9eb", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("214479c63f99523", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("216a506ab79123d", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("22b5142ba5b96c5", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("236e6238dda382b", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("276a05282b1d0e5", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("29ba9eb149dd687", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("2a1678e4bd0669f", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("2c478b16f4254c3", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("2caaffb9dd9f4f6", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("2ee187b7f4c74da", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("315837abac93ae7", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("33116fb72e9ace0", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("345fdf9b283ab32", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("37139648a056872", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("39e3a194c3eddf6", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("44fe4087db71eb2", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("45f17cc28e3c886", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("49428971392be8e", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("4a999bdb84ee168", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("4faa91bfe0c2170", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("536d3a713e6a28f", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("5447cd078b31471", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("55d2bd203b296b2", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("57d2f221f291193", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("5ba095e89c3d809", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("5d593a2ad08ee54", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("608da2bde619309", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("63b6cdcd7b0cd86", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("66dc91ad7bc37e6", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("67e69bd1883d21e", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("6e052d946d1d195", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("715aa53da3278f1", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("72cd863489477be", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("76c8768fbe3ec3f", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("79874bf13b218c2", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("7adc3e895298dc2", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("7d7d7960a96500e", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("7deda289696c320", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("7e852863591babb", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("7e8bc1cd9450fa7", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("7eee1c17a008800", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("80f8b20fac23aa0", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("81e3c32c6387080", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("85bcee712f68a8e", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("863a90c10e1e38e", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("86a5f1133fba1d1", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("874cc30e9e460be", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("88c0dc6fab4b5c5", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("938720c9c1e3fe0", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("958873487df713f", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("979f0dad7493a51", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("97d89fa80221eba", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("996f265733c3b20", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("9b5e5bf807d42b3", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("a0bc78a692fa1b9", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("a53e22ab0c19ccf", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("a6c017916d7566a", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("a88c51f9cd8a350", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("aa6660b1c37fd9c", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("af9619cd9c58ecf", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("b02234114b651b6", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("b1626aff0bc2650", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("b4706997d463d5e", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("bd081786b8c219d", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("c133a18df98e7c1", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("c242d2bfdd76936", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("d43927965ac0e35", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("d4da7bc67398db3", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("d59241218e2735b", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("d7c0bb1b470c784", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("d86fbe104a9cc19", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("dbfc0e85e54a183", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("ddeecd393fb8b7c", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("dfc427caec5e227", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("e1b39c76a521bc6", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("e3b453dda1412b6", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("e477eef964cfbfa", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("e76fe6c1c763110", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("ec56b0b0aca1133", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("edf6dc0f2f26b8f", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("eea385789a7786f", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("eebbf972f262fca", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("f06183c32eb98ff", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("f072fd23b6c97d9", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("f5c06ee279fefd8", $account_id_robin) . "<br>";
	print "ROBINTAN: " . DB::i()->insertTAN("ffc45e8dc099a99", $account_id_robin) . "<br>";
}

?>
