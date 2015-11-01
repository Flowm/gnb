<?php

require_once __DIR__."/../resource_mappings.php";
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

?>

<!--<button type="button" onclick="goToEmployeeArea('manage_transfer')">Back</button><br>-->
<div id="transaction_info">
    <p class="simpleTextBig"><b>Transaction</b></p>
    <table class="table-default">
        <thead>
        <tr class="thead-row-default">
            <th class="th-default">ID</th>
            <th class="th-default">Amount</th>
            <th class="th-default">Description</th>
            <th class="th-default">Creation Date</th>
        </tr>
        </thead>
        <tbody>
        <?php
        echo "<tr class='tbody-row-default'>
            <td class='td-default'>$transaction->id</td>
            <td class='td-default'>$transaction->amount</td>
            <td class='td-default'>$transaction->description</td>
            <td class='td-default'>$transaction->creation_date</td>
        </tr>";
        ?>
        </tbody>
    </table>
</div>

<div id="sender_info">
    <p class="simpleTextBig"><b>Sender</b></p>
    <table class="table-default">
        <thead>
        <tr class="thead-row-default">
            <th class="th-default">ID</th>
            <th class="th-default">Name</th>
            <th class="th-default">Email</th>
            <th class="th-default">Account ID</th>
        </tr>
        </thead>
        <tbody>
        <?php
        echo "<tr class='tbody-row-default'>
            <td class='td-default'>$sender->id</td>
            <td class='td-default'>$sender->firstname $sender->lastname</td>
            <td class='td-default'>$sender->email</td>
            <td class='td-default'>$srcAccount->id</td>
        </tr>";
        ?>
        </tbody>
    </table>
</div>
<div id="receiver_info">
    <p class="simpleTextBig"><b>Receiver</b></p>
    <table class="table-default">
        <thead>
        <tr class="thead-row-default">
            <th class="th-default">ID</th>
            <th class="th-default">Name</th>
            <th class="th-default">Email</th>
            <th class="th-default">Account ID</th>
        </tr>
        </thead>
        <tbody>
        <?php
        echo "<tr class='tbody-row-default'>
            <td class='td-default'>$receiver->id</td>
            <td class='td-default'>$receiver->firstname $receiver->lastname</td>
            <td class='td-default'>$sender->email</td>
            <td class='td-default'>$dstAccount->id</td>
        </tr>"
        ?>
        </tbody>
    </table>
</div>

<div class="button-container">
    <input type="hidden" id="<?php echo $transaction->id ?>" name="action_check" checked>
    <button type="button" class="simpleButton" onclick="approveTransfer()">Approve Transaction</button>
    <button type="button" class="simpleButton" onclick="rejectTransfer()">Reject Transaction</button>
</div>

