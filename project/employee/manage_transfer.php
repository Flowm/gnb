<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 18/10/15
 * Time: 11:02
 */

require_once __DIR__."/../resource_mappings.php";
require_once getpageabsolute("user");
require_once getpageabsolute("db_functions");

//Handling approval/rejections first
if (isset($_POST['action']) && isset($_POST['transactions'])) {
    $action = $_POST['action'];
    $requests = $_POST['transactions'];
    $approver_id = $_SESSION['user_id'];

    if ($action == 'approveTransfer') {
        transaction::approveTransactions($requests, $approver_id);
    }
    else if ($action == 'rejectTransfer') {
        transaction::rejectTransactions($requests, $approver_id);
    }
}

//Once request operations have been handled, be get the list of pending transactions and display it
$data = getPendingTransactions();
$transactions = array();
if ($data != null) {
    foreach ($data as $t) {
        array_push($transactions, new transaction($t));
    }
}

//In case we have no pending transactions
if (count($transactions) == 0) {
    echo "<p>There currently are no pending transfer requests</p>";
    exit();
}

?>

<p class="simple-label">There are <?php count($transactions) ?> pending transfer requests awaiting your approval</p><br>

<table class="table-default">
    <thead>
    <tr class="thead-row-default">
        <th class='th-default'></th>
        <th class='th-default'>Source Account</th>
        <th class='th-default'>Destination Account</th>
        <th class='th-default'>Creation date</th>
        <th class='th-default'>Amount</th>
        <th class='th-default'></th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($transactions as $transaction) {
        echo "<tr class='tbody-row-default'>
            <td class='td-default'><input type='checkbox' name='action_check' id='$transaction->id'>
                <label for='$transaction->id'><span></span></label></td>
            <td class='td-default'>$transaction->src</td>
            <td class='td-default'>$transaction->dst</td>
            <td class='td-default'>$transaction->creation_date</td>
            <td class='td-default'>$transaction->amount</td>
            <td class='td-default'>
            <button type='button' class='details-button' onclick='goToTransferDetails($transaction->id)'>Details</button>
            </td>
        </tr>";
    }
    ?>
    </tbody>
</table>
<div class="select-all-container">
    <input type="checkbox" id="selectAll_check" onclick="checkAllBoxes()">
    <label for="selectAll_check"><span></span>Select/deselect all</label>
</div>

<p class="simple-text-big simple-text-centered">What should be done with the selected transfer requests?</p>
<div class="button-container">
    <button type="button" class="simpleButton" onclick="approveTransfer()">Approve</button>
    <button type="button" class="simpleButton" onclick="rejectTransfer()">Reject</button>
</div>
