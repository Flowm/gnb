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

<button type="button" onclick="goToEmployeeArea('manage_transfer')">Back</button><br>
<div id="transaction_info">
    <span><b>Transaction</b></span>
    <table>
        <tr>
            <th>ID</th>
            <th>Amount</th>
            <th>Description</th>
            <th>Creation Date</th>
        </tr>
        <?php
        echo "<tr>
            <td>$transaction->id</td>
            <td>$transaction->amount</td>
            <td>$transaction->description</td>
            <td>$transaction->creation_date</td>
        </tr>";
        ?>
    </table>
</div>

<div id="sender_info">
    <span><b>Sender</b></span>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Account ID</th>
        </tr>
    </table>
    <?php
    echo "<tr>
        <td>$sender->id</td>
        <td>$sender->firstname $sender->lastname</td>
        <td>$sender->email</td>
        <td>$srcAccount->id</td>
    </tr>";
    ?>
</div>
<div id="receiver_info">
    <span><b>Receiver</b></span>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Account ID</th>
        </tr>
    </table>
    <?php
    echo "<tr>
        <td>$receiver->id</td>
        <td>$receiver->firstname $receiver->lastname</td>
        <td>$sender->email</td>
        <td>$dstAccount->id</td>
    </tr>"
    ?>
</div>

<div class="buttonContainer">
    <input type="hidden" id="<?php echo $transaction->id ?>" name="action_check" checked>
    <button type="button" class="simpleButton" onclick="approveTransfer()">Approve Transaction</button>
    <button type="button" class="simpleButton" onclick="rejectTransfer()">Reject Transaction</button>
</div>

