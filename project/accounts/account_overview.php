<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 18/10/15
 * Time: 00:32
 
 
 */
include_once ('../main_include.php') ;
$user_id 	= $_SESSION["user_id"] ;  

if (isset($_POST["account"])){
	$account_id = $_POST["account"] ;
}


if ( empty($account_id) ){
	die("Please choose an account")  ; 
} 

$account_holder_info 	= getAccountOwnerFromID($account_id) ;
drawSingleRecordTable($account_holder_info,'Account Holder') ;

echo 	'<br>' ; 

$account_info			= getAccountDetails($account_id) ; 
drawSingleRecordTable($account_info,'Account') ;

	 
?>





