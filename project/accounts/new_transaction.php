<?php

require_once __DIR__."/../resource_mappings.php";
require_once getpageabsolute("db_functions");
require_once getPageAbsolute("drawfunctions");

if (empty($_SESSION["user_id"]))
	die("User missing");
if (empty($_SESSION["account_id"]))
	die("Please choose an account");

$account_id = $_SESSION["account_id"];

$acc_info = getAccountDetails($account_id) ;
drawSingleRecordTable($acc_info,'Account ') ;

$dest_code		= ( isset($_POST["dest_code"]) ? $_POST["dest_code"] : '' );
$amount			= ( isset($_POST["amount"]) ? $_POST["amount"] : '' );
$description	= ( isset($_POST["description"]) ? $_POST["description"] : '' );
$tan_code		= ( isset($_POST["tan_code"]) ? $_POST["tan_code"] : '' );

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
    <h2>Transaction page</h2><br>
    <h4>This form is used to perform a single transaction for multiple transactions please click <a href="">here</a></h4>
    <h5>Note: Any Transaction over 10,000 will be need to be processed which may take up to 48 hours</h5>
    <form method="post" id="transactionForm">
        IBAN# <input type="text" name="dest_code" value="<?=$dest_code?>"><br>
        Amount <input type="text" name="amount" value="<?=$amount?>"><br>
        Descrition <input type="text" name="description" value="<?=$description?>"><br>
        TAN Code <input type="text" name="tan_code" value="<?=$tan_code?>"><br>
        <?php
        $error = null;
        if (isset($_GET) && isset($_GET["error"])) {
            $error = $_GET["error"];
            if ($error == "invalid") {
                echo "<b><font color='red'>Invalid login credentials!</font></b><br>";
            }
        }
        ?>
        <input type="hidden" name="account_id" value="<?=$account_id?>">
        <button type="button" onclick="verifyTransaction()">Submit</button>
    </form>
    <h4>For more about TAN Codes and how to use them please read instuctions <a href="">here</a> </h4>
</body>
