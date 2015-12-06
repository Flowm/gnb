<?php

require_once __DIR__."/../resource_mappings.php";
require_once getpageabsolute("utilityfunctions");

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
    $client_id = santize_input($_POST['client_id'],SANITIZE_INT);
}
if (isset($_POST['transfer_id'])) {
	$transfer_id	= santize_input($_POST['transfer_id'],SANITIZE_INT);
    $search = DB::i()->getTransaction($transfer_id);
    if ($search) {
        $transaction = new transaction($search);
    }
}
if ($transaction == null || $client_id == null) {
    header("Location:".getPageAbsolute('employee'));
    exit();
}

$data = DB::i()->getAccountDetails($transaction->src);
$srcAccount = new account($data);
$data = DB::i()->getAccountOwnerFromID($srcAccount->id);
$data = DB::i()->getUser($data['User ID']);
$sender = new user($data);
$data = DB::i()->getAccountDetails($transaction->dst);
$dstAccount = new account($data);
$data = DB::i()->getAccountOwnerFromID($dstAccount->id);
$data = DB::i()->getUser($data['User ID']);
$receiver = new user($data);

?>

<button type="button" onclick="goToClientDetails('<?=$client_id?>')" class="details-button">Back</button><br><br>

<?php
include getFrameAbsolute('transaction_view');
?>

