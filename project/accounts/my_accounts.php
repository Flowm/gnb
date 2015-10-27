<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 17/10/15
 * Time: 15:14
 */

$frame = getFrameAbsolute('account_home'); //static default
if (isset($_POST["frame"])) {
    $frame = getFrameAbsolute($_POST["frame"]);
}

$selected = "account1";
if (isset($_POST["account"])) {
    $selected = $_POST["account"];
}
?>

<p>Current selected account is account1</p><br>
<span>Select a different account: </span>
<select id="account_select" onchange="onSelectedAccount()">
    <option value="account1" <?php if($selected == "account1") {
        echo "selected";
    }?>>Account 1</option>
    <option value="account2" <?php if($selected == "account2") {
        echo "selected";
    }?>>Account 2</option>
</select><br>

<div class="frameContainer">
    <div class="frameMenu">
        <ul>
            <li><a href="javascript:void(0)"
                   onclick="goToMyAccounts('account_overview','<?php echo $selected; ?>')">Account Overview</a></li>
            <li><a href="javascript:void(0)"
                   onclick="goToMyAccounts('new_transaction','<?php echo $selected; ?>')">New transaction</a></li>
            <li><a href="javascript:void(0)"
                   onclick="goToMyAccounts('transaction_history','<?php echo $selected; ?>')">Transaction History</a></li>
        </ul>
    </div>
    <div class="frameContent">
        <?php
        if ($frame != null) {
            include $frame;
        }
        ?>
    </div>
</div>