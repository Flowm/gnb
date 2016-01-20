<?php

require_once __DIR__."/../resource_mappings.php";

//Worst case, an unauthenticated user is trying to access this page directly
if (!isset($_SESSION["username"]) || !isset($_SESSION["role"])) {
    include(getPageAbsolute('error'));
    exit();
}
//The user is logged in, but tries to access another page directly
else if (!isset($frame)) {
    header("Location:".getPageURL('home'));
    exit();
}

$token = "";
if (!isset($_SESSION['token'])) {
    $token = md5(uniqid(rand(), TRUE));
    $_SESSION['token'] = $token;
} else {
    $token = $_SESSION['token'];
}

require_once getpageabsolute("db_functions");
require_once getPageAbsolute("user");
require_once getpageabsolute("util");
require_once getPageAbsolute("drawfunctions");

if (empty($_SESSION["account_id"]))
	die("Please choose an account");

$account_id = $_SESSION["account_id"];
$acc_info = DB::i()->getAccountDetails($account_id);

$account_header	= array(
	'id'			=> 'ID',
	'balance'		=> 'Balance',
) ;
drawSingleRecordTable($acc_info,'Account ',$account_header) ;

$error_types = array(0=>'Invalid user input!');

$dest_code = '';
$amount = '';
$description = '';
$tan_code = '';

if (isset($_POST["error"]) && $_POST["error"] == "error") {
    $dest_code = check_post_input("dest_code",SANITIZE_INT);
    $amount = check_post_input("amount",SANITIZE_DOUBLE);
    $description = check_post_input("description",SANITIZE_STRING_DESC);
    $tan_code = check_post_input("tan_code",SANITIZE_STRING_DESC);
    if ($dest_code == null) {
        $dest_code = '';
    }
    if ($amount == null) {
        $amount = '';
    }
    if ($description == null) {
        $description = '';
    }
    if ($tan_code == null) {
        $tan_code = '';
    }
}
?>

<br><h1 class="title2">Transaction page</h1>
<h1 class="simple-text">This form is used to perform a single transaction</h1>
<p class="simple-text">Note: All Transactions over 10,000 will require manual approval by an employee</p>
<form method="post" id="transactionForm" onsubmit="verifyTransaction()">
    <input type="hidden" name="token" value="<?=$token?>">
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
                <input type="number" step="0.01" min=0 id="amount" name="amount" value="<?=$amount?>" placeholder="Amount"><br>
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
        if (isset($error) && isset($error_types[$error])) {
            echo '<div class="formRow"><span id="error" class="error">';
            echo $error_types[$error];
            echo '</span><br></div>';
        }
        ?>
        <input type="hidden" name="account_id" value="<?=$account_id?>">
        <div class="button-container">
            <button type="button" onclick="verifyTransaction()" class="simpleButton">Submit</button>
        </div>
    </div>
</form>
