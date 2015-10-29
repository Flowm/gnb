<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 17/10/15
 * Time: 01:22
 */

session_start();

require_once "resource_mappings.php";

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

$error_types = array(0=>'Invalid login credentials!',1=>'The account is currently blocked');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="style/error.css">
    <script type="text/javascript" src="js/index.js"></script>
</head>
<body>
    <h2>Welcome to the Goliath National Bank!</h2><br>
    <h4>Login with personal PIN</h4>
    <form method="post" action="authentication.php">
        <label for="user_input">Enter your username: </label><input type="text" name="username" id="user_input"><br>
        <label for="pw_input">Enter your password: </label><input type="password" name="password" id="pw_input"><br>
        <span id="error" class="error">
        <?php
        if (isset($_GET) && isset($_GET["error"])) {
            $error = $_GET["error"];
            if (isset($error_types[$error])) {
                echo $error_types[$error];
            }
        }
        ?></span><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
