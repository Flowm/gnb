<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 18/10/15
 * Time: 00:32
 */
?>

<p>To perform a transaction you can either fill in the form below or upload a CSV or TXT file
    containing the same information, using the following format:<br>
    <b>RECEIVER_IBAN;TRANSFER_AMOUNT;DESCRIPTION;TAN;</b><br>
    All fields are mandatory!
</p><br>
<form method="post" action="do_transaction.php" enctype="multipart/form-data">
    <input type="file" name="transactionFile"><br>
    <input type="submit" name="upload" value="Upload">
</form>
<br>
<form method="post" action="do_transaction.php" id="transactionForm">
    IBAN of the receiver: <input type="text" name="receiver"><br>
    Transfer amount: <input type="number" name="amount"><br>
    Description: <textarea name="description" form="transactionForm"></textarea><br>
    Enter a TAN code: <input type="text" name="tan"><br>
    <input type="submit" name="transfer" value="Submit">
</form>

<b>*Remember that a TAN code is unique an may be used only once</b>
