<?php

require_once __DIR__."/../resource_mappings.php";
require_once getpageabsolute("db_functions");

$frame = getFrameAbsolute('account_home');
if (isset($_POST["frame"])) {
    $frame = getFrameAbsolute($_POST["frame"]);
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
        <ul>
            <li><a href="javascript:void(0)"
                   onclick="goToMyAccounts('account_overview','<?php echo $selected; ?>')">Account Overview</a></li>
            <li><a href="javascript:void(0)"
                   onclick="goToMyAccounts('new_transaction','<?php echo $selected; ?>')">New transaction</a></li>
			<li><a href="javascript:void(0)"
                   onclick="goToMyAccounts('new_transaction_multiple','<?php echo $selected; ?>')">New transaction (multiple)</a></li>
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
