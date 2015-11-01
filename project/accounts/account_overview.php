<?php

require_once __DIR__."/../resource_mappings.php";
require_once getpageabsolute("db_functions");
require_once getPageAbsolute("drawfunctions");

if (empty($_SESSION["user_id"]))
	die("User missing");
if (empty($_SESSION["account_id"]))
	die("Please choose an account");

$account_id = $_SESSION["account_id"];

$account_holder_info = getAccountOwnerFromID($account_id);
drawSingleRecordTable($account_holder_info,'Account Holder');

echo '<br>';



$account_info = getAccountDetails($account_id);
$account_header	= array(
	'id'			=> 'ID',
	'balance'		=> 'Balance',
) ; 	
drawSingleRecordTable($account_info,'Account',$account_header);

?>
