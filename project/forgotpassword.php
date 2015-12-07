<?php
require_once __DIR__."/resource_mappings.php";
$logo_svg = getMedia('logo_svg');
$title	= 'GNB - Brocode Recovery' ;
$error_types 	= array(
	0	=> "Sorry bro, you're not registered with us." ,
	1	=> "You have already been sent an email with instuctions"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$title?></title>
    <link rel="stylesheet" type="text/css" href="style/gnb.css">
    <script type="text/javascript" src="js/index.js"></script>
    <link rel="icon" type="image/png" href="media/gnb_icon.png" />
</head>
<div class="mainContainer">
    <img src="<?php echo $logo_svg ?>" alt="GNB Logo" class="logo_big">
    <div class="simple-container">
        <h1 class="title3">We are gonna need your bro-mail! to start your brocode recovery</h1>
		<p class="simple-text-index">A password recovery link will be sent to your email, instuctions will be provided in the email.</p>
        <form method="post" action="passwdrecovery.php">
            <div class="formRow">
                <div class="formLeftColumn">
                    <label for="user_input" class="simple-label">Enter your email here, bro:</label>
                </div>
				<div class="formRightColumn">
                    <input type="text" name="username" id="user_input" placeholder="Email"><br>
                </div>
            </div>
			<div class="button-container">
                <button type="submit" class="simpleButton">Submit</button>
            </div>
			<div class="formRow">
                <span id="error" class="error">
                <?php
                if (isset($_GET) && isset($_GET["error"])) {
                    $error = $_GET["error"];
                    if (isset($error_types[$error])) {
                        echo $error_types[$error];
                    }
                }
                ?></span><br>
            </div>
		</form>





