<?php

//This is actually just for presentation purposes, since it is used by several other files.

?>

<div id="transaction_info">
    <p class="title4">Transaction</p>
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
            <td class='td-default'>$transaction->amount $currency</td>
            <td class='td-default'>$transaction->description</td>
            <td class='td-default'>$transaction->creation_date</td>
        </tr>";
        ?>
        </tbody>
    </table>
</div>

<div id="sender_info">
    <p class="title4">Sender</p>
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
    <p class="title4">Receiver</p>
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
