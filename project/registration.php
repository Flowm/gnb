<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 17/10/15
 * Time: 01:22
 */

require_once __DIR__."/resource_mappings.php";

$error_types = array(0=>'All fields are mandatory!',
    1=>'The repeated password does not match the original one!',
    2=>'Invalid email address format',
    3=>'The email you entered is already associated to an account');

$logo_svg = getMedia('logo_svg');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="style/gnb.css">
    <script type="text/javascript" src="js/registration.js"></script>
</head>
<body>
<div class="mainContainer">
    <img src="<?php echo $logo_svg ?>" alt="GNB Logo" class="logo_big">
    <div class="simpleContainer">
        <h1 class="title2">In order to register correctly, you will need to fill in the form below</h1>
        <form method="post" action="registration_request.php" id="registrationForm">
            <div class="formRow">
                <div class="formLeftColumn">
                    <span class="simpleTextBig">What are you?</span>
                </div>
                <div class="formRightColumn">
                    <input type="radio" id="type1" name="type" value="client" checked>
                    <label for="type1"><span></span>Client</label>
                    <input type="radio" id="type2" name="type" value="employee">
                    <label for="type2"><span></span>Employee</label><br>
                </div>
            </div>
            <div class="formRow">
                <div class="formLeftColumn">
                    <label for="email" class="simpleLabel">Enter your email address: </label>
                </div>
                <div class="formRightColumn">
                    <input type="email" id="email" name="email" placeholder="Email"><br>
                </div>
            </div>
            <div class="formRow">
                <div class="formLeftColumn">
                    <label for="firstname" class="simpleLabel">Enter your first name: </label>
                </div>
                <div class="formRightColumn">
                    <input type="text" id="firstname" name="firstname" placeholder="Name"><br>
                </div>
            </div>
            <div class="formRow">
                <div class="formLeftColumn">
                    <label for="lastname" class="simpleLabel">Enter your last name: </label>
                </div>
                <div class="formRightColumn">
                    <input type="text" id="lastname" name="lastname" placeholder="Lastname"><br>
                </div>
            </div>
            <div class="formRow">
                <div class="formLeftColumn">
                    <label for="password" class="simpleLabel">Enter a valid password: </label>
                </div>
                <div class="formRightColumn">
                    <input type="password" id="password" name="password" placeholder="Password"><br>
                </div>
            </div>
            <div class="formRow">
                <div class="formLeftColumn">
                    <label for="password_repeat" class="simpleLabel">Repeat the password: </label>
                </div>
                <div class="formRightColumn">
                    <input type="password" id="password_repeat" name="password_repeat" placeholder="Password repeat"><br>
                </div>
            </div>
            <div class="formRow">
            <span id="error" class="error">
                <?php
                $error = null;
                if (isset($_GET) && isset($_GET["error"])) {
                    $error = $_GET["error"];
                    if (isset($error_types[$error])) {
                        echo $error_types[$error];
                    }
                }
                ?></span><br>
            </div>
            <div class="button-container">
                <button type="button" onclick="register()" class="simpleButton">Register</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
