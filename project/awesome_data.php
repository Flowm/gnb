<?php
include_once "resource_mappings.php";
include_once getPageAbsolute("db_functions");

$nClients = getNumberOfUsers();
$money = getTotalAmountOfMoney();
$currency = "€";

?>