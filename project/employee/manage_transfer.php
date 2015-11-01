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

<p>There are <?php count($transactions) ?> pending transfer requests awaiting your approval</p>

<table>
    <tr>
        <th></th>
        <th>Source Account</th>
        <th>Destination Account</th>
        <th>Creation date</th>
        <th>Amount</th>
        <th></th>
    </tr>
    <?php
    foreach ($transactions as $transaction) {
        echo "<tr>
            <td><input type='checkbox' name='action_check' id='$transaction->id'></td>
            <td>$transaction->src</td>
            <td>$transaction->dst</td>
            <td>$transaction->creation_date</td>
            <td>$transaction->amount</td>
            <td><button type='button' onclick='goToTransferDetails($transaction->id)'>Details</button></td>
        </tr>";
    }
    ?>
</table>
<table>
    <tr>
        <td><input type="checkbox" id="selectAll_check" onclick="checkAllBoxes()">
            <label for="selectAll_check">Select/deselect all</label></td>
    </tr>
</table>


<p>What should be done with the selected transfer requests?</p>
<button type="button" onclick="approveTransfer()">Approve</button>
<button type="button" onclick="rejectTransfer()">Reject</button>
