<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 18/10/15
 * Time: 09:33
 */

//IMPLEMENT SOME STUFF

include_once ('../main_include.php') ; 
# need account ID 
#except Account Number 
foreach ($_POST as $var => $value ){
	$$var	= $value ; 
}


$account_id	= $_POST["account"] ; 

if ( empty($account_id) ){
	die("Please choose an account")  ; 
} 


$acc_info = getAccountDetails($account_id) ;
#var_dump($acc_info) ;
drawSingleRecordTable($acc_info,'Account ') ; 


$dest_code			= ( isset($dest_code) ? $dest_code : '' ) ; 
$amount				= ( isset($amount) ? $amount : '' ) ; 
$description		= ( isset($description) ? $description : '' ) ; 
$tan_code			= ( isset($tan_code) ? $tan_code : '' ) ; 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
    <h2>Transaction page</h2><br>
    <h4>This form is used to perform a single transaction for multiple transactions please click <a href="">here</a></h4>
    <h5>Note: Any Transaction over 10,000 will be need to be processed which may take up to 48 hours</h5> 
	<form method="post" action="verify_transaction.php">
        IBAN# <input type="text" name="dest_code"	value="<?=$dest_code?>"><br>
		Amount <input type="text" name="amount" value="<?=$amount?>"><br>
		Descrition <input type="text" name="description" value="<?=$description?>"><br>
		TAN Code <input type="text" name="tan_code" value="<?=$tan_code?>"><br>
        <?php
        $error = null;
        if (isset($_GET) && isset($_GET["error"])) {
            $error = $_GET["error"];
            if ($error == "invalid") {
                echo "<b><font color='red'>Invalid login credentials!</font></b><br>";
            }
        }
        ?>
        <input type="hidden" name="account_id" value="<?=$account_id?>">
        <input type="submit" value="Submit">
    </form>
    <h4>For more about TAN Codes and how to use them please read instuctions <a href="">here</a> </h4>
</body>