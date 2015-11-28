<?php

require_once __DIR__."/../resource_mappings.php";

//Worst case, an unauthenticated user is trying to access this page directly
if (!isset($_SESSION["username"]) || !isset($_SESSION["role"])) {
    include(getPageAbsolute('error'));
    exit();
}
//The user is logged in, but tries to access another page directly
else if (!isset($section)) {
    header("Location:".getPageURL('home'));
    exit();
}

require_once getpageabsolute("db_functions");
require_once getPageAbsolute("drawfunctions");

if (empty($_SESSION["user_id"]))
	die("User missing");
if (empty($_SESSION["account_id"])) {

	//$all_accounts = getAccountsForUser($_SESSION["user_id"]);

	//drawMultipleRecordTable($all_accounts, 'Accounts');
	return;
}

$account_id = $_SESSION["account_id"];

$account_holder_info = getAccountOwnerFromID($account_id);
drawSingleRecordTable($account_holder_info,'Account Holder');

echo '<br>';



$account_info = getAccountDetails($account_id);
$account_header	= array(
	'id'			=> 'ID :',
	'balance'		=> 'Balance :',
) ; 	
drawSingleRecordTable($account_info,'Account',$account_header);

?>
