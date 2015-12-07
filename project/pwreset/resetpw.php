<?php
require_once __DIR__."/../resource_mappings.php";
require_once getPageAbsolute("util");
require_once getPageAbsolute("pw_hdr");

$logo_svg 	= getMedia('logo_svg');
$title		= 'GNB - Passwrod Reset' ;
$pwr_link	= getPageURL("resetpw_r");


if (isset($_GET["token"])){
	$token 	= santize_input($_GET["token"],SANITIZE_STRING_VAR) ;
}
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$title?></title>
    <link rel="stylesheet" type="text/css" href="../style/gnb.css">
    <script type="text/javascript" src="../js/index.js"></script>
    <link rel="icon" type="image/png" href="../media/gnb_icon.png" />
</head>
<div class="mainContainer">
    <img src="<?php echo $logo_svg ?>" alt="GNB Logo" class="logo_big">
    <div class="simple-container">
        <h1 class="title3">Please enter the following details.</h1>
		<form method="post" action="<?=$pwr_link?>">
			<div class="formRow">
                <div class="formLeftColumn">
                    <label for="email" class="simple-label">Enter your email address: </label>
                </div>
                <div class="formRightColumn">
                    <input type="email" id="email" name="email" placeholder="Email"><br>
                </div>
            </div>
			<div class="formRow">
                <div class="formLeftColumn">
                    <label for="password" class="simple-label">Enter your PIN: </label>
                </div>
                <div class="formRightColumn">
                    <input type="password" id="password" name="pin" placeholder="PIN"><br>
                </div>
            </div>
			<div class="formRow">
                <div class="formLeftColumn">
                    <label for="password" class="simple-label">Enter new password: </label>
                </div>
                <div class="formRightColumn">
                    <input type="password" id="password" name="password" placeholder="New Password"><br>
                </div>
            </div>
            <div class="formRow">
                <div class="formLeftColumn">
                    <label for="password_repeat" class="simple-label">Repeat new password: </label>
                </div>
                <div class="formRightColumn">
                    <input type="password" id="password_repeat" name="password_repeat" placeholder="New Password repeat"><br>
                </div>
            </div>
			<input type="hidden" id="token" name="token" value="<?=$token?>">
			<div class="button-container">
                <button type="submit" class="simpleButton">Reset Password</button>
            </div>
			<div class="formRow">
                <span id="error" class="error">
                <?php
                if (isset($_GET) && isset($_GET["msg"])) {
                    $msg = $_GET["msg"];
                    if (isset($reset_messages[$msg])) { echo $reset_messages[$msg]; }
                }
                ?></span><br>
            </div>
		</form>





