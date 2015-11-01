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

$summary_message 	= '' ; 
$selected			= '' ; 
if (isset($_POST["account"])){
	$selected 	= $_POST["account"]; 
	$summary_message	= 'Current selected account is #'.$selected ; 
} else {
	$summary_message	= 'No account is currently selected' ; 
}
	
if (isset($_POST["account"])) {
    $selected = $_POST["account"];
}

$user_id	=	$_SESSION["user_id"] ; 
$accounts_info 	= getAccountsForUser( $user_id ) ;  

echo 	'<p>'.$summary_message.'</p><br>' ;


# Generate Account List 
echo 	'<span>Select a different account: </span>' ; 
echo 	'<select id="account_select" onchange="onSelectedAccount()">' ;
echo 	'<option selected disabled>Select Account</option>' ;
foreach($accounts_info as $acc){
	echo 	'<option value="'.$acc["id"].'" ' ; 
	if ( $selected == $acc["id"] ){ echo "selected" ; }   
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