<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 17/10/15
 * Time: 01:22
 */

$error_types = array(0=>'All fields are mandatory!',
    1=>'The repeated password does not match the original one!',
    2=>'Invalid email address format',
    3=>'The email you entered is already associated to an account');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="style/error.css">
    <script type="text/javascript" src="js/registration.js"></script>
</head>
<body>
    <h2>Welcome to the Goliath National Bank!</h2><br>
    <h4>In order to register correctly, you will need to fill in the form below</h4>
    <form method="post" action="registration_request.php" id="registrationForm">
        <label for="type1">What are you? </label>
        <input type="radio" id="type1" name="type" value="client" checked>Client&nbsp;
        <label for="type2"></label>
        <input type="radio" id="type2" name="type" value="employee">Employee<br>
        <label for="email">Enter your email address: </label>
        <input type="email" id="email" name="email"><br>
        <label for="firstname">Enter your first name: </label>
        <input type="text" id="firstname" name="firstname"><br>
        <label for="lastname">Enter your last name: </label>
        <input type="text" id="lastname" name="lastname"><br>
        <label for="password">Enter a valid password: </label>
        <input type="password" id="password" name="password"><br>
        <label for="password_repeat">Repeat the password: </label>
        <input type="password" id="password_repeat" name="password_repeat"><br>
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
        <button type="button" onclick="register()">Register</button>
    </form>
</body>
</html>
