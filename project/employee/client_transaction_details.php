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

require_once getpageabsolute("db_functions");
require_once getpageabsolute("user");

$transaction = null;
$client_id = null;
if (isset($_POST['client_id'])) {
    $client_id = $_POST['client_id'];
}
if (isset($_POST['transfer_id'])) {
    $search = getTransaction($_POST['transfer_id']);
    if ($search) {
        $transaction = new transaction($search);
    }
}
if ($transaction == null || $client_id == null) {
    header("Location:".getPageAbsolute('employee'));
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

?>

<button type="button" onclick="goToClientDetails('<?=$client_id?>')" class="details-button">Back</button><br><br>

<?php
include getFrameAbsolute('transaction_view');
?>

