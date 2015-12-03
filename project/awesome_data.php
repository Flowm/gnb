<?php
include_once "resource_mappings.php";
include_once getPageAbsolute("db_functions");

$nClients = DB::i()->getNumberOfUsers();
$money = DB::i()->getTotalAmountOfMoney();
$currency = "€";

?>