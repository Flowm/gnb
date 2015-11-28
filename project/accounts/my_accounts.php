<?php

require_once __DIR__."/../resource_mappings.php";

//Worst case, an unauthenticated user is trying to access this page directly
if (!isset($_SESSION["username"]) || !isset($_SESSION["role"])) {
    include(getPageAbsolute('error'));
    exit();
}
//The user is logged in, but tries to access another page directly
else if (!isset($section)) {
    header("Location:".getPageURL('home'));
    exit();
}
//Since both clients and employees can access only their own accounts, we shouldn't worry about privilege escalation
$role = $_SESSION['role'];
if ($role != "client" && $role != "employee") {
    include(getPageAbsolute('error'));
    exit();
}

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

//In case the user doesn't have any accounts
if ($accounts_info == null || count($accounts_info) == 0) {
    echo "<div class='simple-container-no-bounds simple-text-centered'>
        <h1 class='title4'>Sorry buddy, but you don't have any active accounts at GNB!</h1></div>";
    return;
}

//Used for presentation purposes
$overview_frame_keys = array('account_overview');
$single_trans_frame_keys = array('new_transaction', 'verify_transaction');
$multiple_trans_frame_keys = array('new_transaction_multiple');
$history_frame_keys = array('transaction_history');

# Generate Account List
echo "<div class='simple-container-no-bounds simple-text-centered'>";
if (isset($_SESSION["account_id"])) {
	$selected = $_SESSION["account_id"];
	echo '<span class="simple-label">Selected account: </span>' ;
} else {
	echo '<span class="simple-label">Please select an account: </span>' ;
	$selected = "";
}
echo '<select class="select-bar" id="account_select" onchange="onSelectedAccount()">' ;
echo '<option selected disabled>Select Account</option>' ;
foreach($accounts_info as $acc){
	echo 	'<option value="'.$acc["id"].'" ' ;
	if ( $selected == $acc["id"] ){ echo "selected" ; }
	echo 	'>'.$acc["id"].'</option>' ;
}
echo	'</select></div>' ;

?>
<div class="frameContainer">
    <div class="frameMenu">
        <div class="menu-container">
            <ul class="menu-button-list">
                <li class="menu-button <?php
                if ($frameKey != null && in_array($frameKey, $overview_frame_keys)) {
                    echo "menu-button-active";
                }
                ?>" onclick="goToMyAccounts('account_overview', '<?=$selected?>')">
                    <a class="menu-button-inner">Account Overview</a>
                    <?php
                    if ($frameKey != null && in_array($frameKey, $overview_frame_keys)) {
                        echo "<span class='menu-selected-arrow'></span>";
                    }
                    ?>
                </li>
                <li class="menu-button <?php
                if ($frameKey != null && in_array($frameKey, $single_trans_frame_keys)) {
                    echo "menu-button-active";
                }
                ?>" onclick="goToMyAccounts('new_transaction', '<?=$selected?>')">
                    <a class="menu-button-inner">New transaction</a>
                    <?php
                    if ($frameKey != null && in_array($frameKey, $single_trans_frame_keys)) {
                        echo "<span class='menu-selected-arrow'></span>";
                    }
                    ?>
                </li>
                <li class="menu-button <?php
                if ($frameKey != null && in_array($frameKey, $multiple_trans_frame_keys)) {
                    echo "menu-button-active";
                }
                ?>" onclick="goToMyAccounts('new_transaction_multiple', '<?=$selected?>')">
                    <a class="menu-button-inner">New transaction (multiple)</a>
                    <?php
                    if ($frameKey != null && in_array($frameKey, $multiple_trans_frame_keys)) {
                        echo "<span class='menu-selected-arrow'></span>";
                    }
                    ?>
                </li>
                <li class="menu-button <?php
                if ($frameKey != null && in_array($frameKey, $history_frame_keys)) {
                    echo "menu-button-active";
                }
                ?>" onclick="goToMyAccounts('transaction_history', '<?=$selected?>')">
                    <a class="menu-button-inner">Transaction History</a>
                    <?php
                    if ($frameKey != null && in_array($frameKey, $history_frame_keys)) {
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
