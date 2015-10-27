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
$account_id = '10000001' ;  
list($n_cols, $acc_info) = getAccountDetails($account_id) ;
drawSingleRecordTable($acc_info,'Account #'.$account_id) ; 

$available_funds 	= 	$acc_info["balance"] ; 
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
        IBAN# <input type="text" name="dest_code"><br>
		Amount <input type="text" name="amount"><br>
		Descrition <input type="text" name="description"><br>
		TAN Code <input type="text" name="tan_code"><br>
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