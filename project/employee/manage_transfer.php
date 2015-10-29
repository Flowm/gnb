<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 18/10/15
 * Time: 11:02
 */

require_once __DIR__."/../resource_mappings.php";
require_once getpageabsolute("db_functions");
require_once getpageabsolute("user");

$data = getPendingTransactions();
$transactions = array();
if ($data != null) {
    foreach ($data as $t) {
        array_push($transactions, new transaction($t));
    }
}

//IN CASE WE HAVE NO PENDING TRANSFERS
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
        <th>Description</th>
        <th>Details</th>
    </tr>
    <?php
    foreach ($transactions as $transaction) {
        echo "<tr>
            <td><input type='checkbox' name='action_check' id='$transaction->id'></td>
            <td>$transaction->src</td>
            <td>$transaction->dst</td>
            <td>$transaction->creation_date</td>
            <td>$transaction->amount</td>
            <td>$transaction->description</td>
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
