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
//Vertical privilege escalation attempt -> no go
$role = $_SESSION["role"];
if ($role != "employee") {
    include(getPageAbsolute('error'));
    exit();
}

require_once getPageAbsolute('user');
require_once getPageAbsolute('db_functions');

$transaction = null;
if (isset($_POST['transfer_id'])) {
    $search = getTransaction($_POST['transfer_id']);
    if ($search) {
        $transaction = new transaction($search);
    }
}

if ($transaction == null) {
    include getSectionAbsolute('transfer_details');
    exit();
}

$data = getAccountDetails($transaction->src);
$srcAccount = new account($data);
$data = getAccountOwnerFromID($srcAccount->id);
$data = getUser($data['User ID']);
$sender = new user($data);
$data = getAccountDetails($transaction->dst);
$dstAccount = new account($data);
$data = getAccountOwnerFromID($dstAccount->id);
$data = getUser($data['User ID']);
$receiver = new user($data);

$currency = '€';

?>

<button type="button" onclick="goToEmployeeArea('manage_transfer')" class="details-button">Back</button><br><br>

<?php
include getFrameAbsolute('transaction_view');
?>

<div class="button-container">
    <input type="hidden" id="<?php echo $transaction->id ?>" name="action_check" checked>
    <button type="button" class="simpleButton" onclick="approveTransfer()">Approve Transaction</button>
    <button type="button" class="simpleButton" onclick="rejectTransfer()">Reject Transaction</button>
</div>

