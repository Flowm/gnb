<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 18/10/15
 * Time: 09:33
 */

//IMPLEMENT SOME STUFF

include_once ('../main_include.php') ; 
include_once ('../resource_mappings.php') ; 
# need account ID 
#except Account Number 
foreach ($_POST as $var => $value ){
	$$var	= $value ; 
}

$target_dir 	= 	"../upload/";
$target_file 	= $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk 		= 1;
$FileType 		= pathinfo($target_file,PATHINFO_EXTENSION);

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}


$account_id = '10000001' ;  
$acc_info = getAccountDetails($account_id) ;
drawSingleRecordTable($acc_info[0],'Account ') ; 

$dest_code			= ( isset($dest_code) ? $dest_code : '' ) ; 
$amount				= ( isset($amount) ? $amount : '' ) ; 
$description		= ( isset($description) ? $description : '' ) ; 
$tan_code			= ( isset($tan_code) ? $tan_code : '' ) ; 

# Test PHP Page 
$thisPage			= 'new_transaction_multiple.php'
#$thisPage			= $frames["new_transaction_multiple"]
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
    <h2>Multiple Transaction page</h2><br>
    <h4>This form is to be used when performing multiple transactions at once for single transactions please click <a href="">here</a></h4>
    <h5>Note: Any Transaction over 10,000 will be need to be processed which may take up to 48 hours, only internal GNB transactions are supported.</h5> 
    <h5>Format of the file needs to be in the as follows (each transaction on a new line):<br>
    Destination Account ID,Amount,Description,TAN Code</h5>
 
	<form action="<?=$thisPage?>" method="post" enctype="multipart/form-data">
		Select transaction file to upload:
		<input type="file" name="TransactionsFile" id="TransactionsFile">
		<input type="submit" value="Upload file" name="submit">
	</form>
    <h4>For more about TAN Codes and how to use them please read instuctions <a href="">here</a> </h4>
</body>