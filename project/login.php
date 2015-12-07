<?php

session_start();

require_once __DIR__."/resource_mappings.php";

if (isset($_SESSION["username"]) && isset($_SESSION["role"])) {
    if ($_SESSION["role"] == "client") {
        header("Location:".getPageURL('client'));
        exit();
    }
    else if ($_SESSION["role"] == "employee") {
        header("Location:".getPageURL('employee'));
        exit();
    }
}

session_destroy();
session_regenerate_id(true);
session_unset();

$error_types = array(0=>'Invalid login credentials!',1=>'The account is currently blocked');

$logo_svg 		= getMedia('logo_svg');
$forgotpwr_link	= getPageURL('forgotpw');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GNB - Login</title>
    <link rel="stylesheet" type="text/css" href="style/gnb.css">
    <script type="text/javascript" src="js/index.js"></script>
    <link rel="icon" type="image/png" href="media/gnb_icon.png" />
</head>
<body>
<div class="mainContainer">
    <img src="<?php echo $logo_svg ?>" alt="GNB Logo" class="logo_big">
    <div class="simple-container">
        <h1 class="title3">We are gonna need your bro-dentials!</h1>
        <form method="post" action="authentication.php">
            <div class="formRow">
                <div class="formLeftColumn">
                    <label for="user_input" class="simple-label">Enter your email here, bro:</label>
                </div>
                <div class="formRightColumn">
                    <input type="text" name="username" id="user_input" placeholder="Email"><br>
                </div>
            </div>
            <div class="formRow">
                <div class="formLeftColumn">
                    <label for="pw_input" class="simple-label">Enter your password here, bro:</label>
                </div>
                <div class="formRightColumn">
                    <input type="password" name="password" id="pw_input" placeholder="Password"><br>
                </div>
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
            <div class="button-container">
                <button type="submit" class="simpleButton">Suit up!</button>
            </div>
        </form>
        <form method="post" action="<?=$forgotpwr_link?>">
			<div class="button-container">
				<button type="submit" class="simpleButton">Forgot your brocode?</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
