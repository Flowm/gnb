<?php

require_once __DIR__."/../resource_mappings.php";
require_once getpageabsolute("db_functions");

$frameKey = 'account_overview';
$frame = getFrameAbsolute('account_home'); //static default
if (isset($_POST["frame"])) {
    $frameKey = $_POST["frame"];
    $frame = getFrameAbsolute($frameKey);
}

if (empty($_SESSION["user_id"]))
	die("User missing");
$accounts_info = getAccountsForUser($_SESSION["user_id"]);

if (isset($_POST["account"])) {
	foreach($accounts_info as $acc) {
		if ( $_POST["account"] == $acc["id"] ) {
			$_SESSION["account_id"] = $_POST["account"];
			break;
		}
	}
}
# Generate Account List
if (isset($_SESSION["account_id"])) {
	$selected = $_SESSION["account_id"];
	echo '<span>Select a different account: </span>' ;
} else {
	echo '<span>Please select an account: </span>' ;
}
echo '<select id="account_select" onchange="onSelectedAccount()">' ;
echo '<option selected disabled>Select Account</option>' ;
foreach($accounts_info as $acc){
	echo 	'<option value="'.$acc["id"].'" ' ;
	if ( $_SESSION["account_id"] == $acc["id"] ){ echo "selected" ; }
	echo 	'>'.$acc["id"].'</option>' ;
}
echo	'</select><br>' ;

?>
<div class="frameContainer">
    <div class="frameMenu">
        <div class="menu-container">
            <ul class="menu-button-list">
                <li class="menu-button <?php
                if ($frameKey != null && $frameKey == 'account_overview') {
                    echo "menu-button-active";
                }
                ?>" onclick="goToMyAccounts('account_overview', '<?=$selected?>')">
                    <a class="menu-button-inner">Account Overview</a>
                    <?php
                    if ($frameKey != null && $frameKey == 'account_overview') {
                        echo "<span class='menu-selected-arrow'></span>";
                    }
                    ?>
                </li>
                <li class="menu-button <?php
                if ($frameKey != null && $frameKey == 'new_transaction') {
                    echo "menu-button-active";
                }
                ?>" onclick="goToMyAccounts('new_transaction', '<?=$selected?>')">
                    <a class="menu-button-inner">New transaction</a>
                    <?php
                    if ($frameKey != null && $frameKey == 'new_transaction') {
                        echo "<span class='menu-selected-arrow'></span>";
                    }
                    ?>
                </li>
                <li class="menu-button <?php
                if ($frameKey != null && $frameKey == 'new_transaction_multiple') {
                    echo "menu-button-active";
                }
                ?>" onclick="goToMyAccounts('new_transaction_multiple', '<?=$selected?>')">
                    <a class="menu-button-inner">New transaction (multiple)</a>
                    <?php
                    if ($frameKey != null && $frameKey == 'new_transaction_multiple') {
                        echo "<span class='menu-selected-arrow'></span>";
                    }
                    ?>
                </li>
                <li class="menu-button <?php
                if ($frameKey != null && $frameKey == 'transaction_history') {
                    echo "menu-button-active";
                }
                ?>" onclick="goToMyAccounts('transaction_history', '<?=$selected?>')">
                    <a class="menu-button-inner">Transaction History</a>
                    <?php
                    if ($frameKey != null && $frameKey == 'transaction_history') {
                        echo "<span class='menu-selected-arrow'></span>";
                    }
                    ?>
                </li>
            </ul>
        </div>
    </div>
    <div class="frameContent">
        <?php
        //INSERTING THE FRAME VIEW
        if ($frame != null) {
            include $frame;
        }
        ?>
    </div>
</div>