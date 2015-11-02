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
$account_header	= array(
	'id'			=> 'ID',
	'balance'		=> 'Balance',
) ;
drawSingleRecordTable($acc_info,'Account ',$account_header) ;

$dest_code		= ( isset($_POST["dest_code"]) ? $_POST["dest_code"] : '' );
$amount			= ( isset($_POST["amount"]) ? $_POST["amount"] : '' );
$description	= ( isset($_POST["description"]) ? $_POST["description"] : '' );
$tan_code		= ( isset($_POST["tan_code"]) ? $_POST["tan_code"] : '' );

?>

<br><h1 class="title2">Transaction page</h1>
<h1 class="simple-text">This form is used to perform a single transaction</h1>
<h1 class="simple-text">Note: Any Transaction over 10,000 will be need to be processed which may take up to 48 hours</h1><br>
<form method="post" id="transactionForm" onsubmit="verifyTransaction()">
    <div class="transaction-container">
        <div class="formRow">
            <div class="formLeftColumn">
                <label for="dest_code" class="simple-label">IBAN# </label>
            </div>
            <div class="formRightColumn">
                <input type="text" id="dest_code" name="dest_code" value="<?=$dest_code?>" placeholder="IBAN#"><br>
            </div>
        </div>
        <div class="formRow">
            <div class="formLeftColumn">
                <label for="amount" class="simple-label">Amount </label>
            </div>
            <div class="formRightColumn">
                <input type="text" id="amount" name="amount" value="<?=$amount?>" placeholder="Amount"><br>
            </div>
        </div>
        <div class="formRow">
            <div class="formLeftColumn">
                <label for="description" class="simple-label">Description </label>
            </div>
            <div class="formRightColumn">
                <input type="text" id="description" name="description" value="<?=$description?>" placeholder="Description"><br>
            </div>
        </div>
        <div class="formRow">
            <div class="formLeftColumn">
                <label for="tan_code" class="simple-label">TAN Code </label>
            </div>
            <div class="formRightColumn">
                <input type="text" id="tan_code" name="tan_code" value="<?=$tan_code?>" placeholder="TAN"><br>
            </div>
        </div>

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
        <div class="button-container">
            <button type="button" onclick="verifyTransaction()" class="simpleButton">Submit</button>
        </div>
    </div>
</form>
