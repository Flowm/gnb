<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 17/10/15
 * Time: 01:22
 */

session_start();
if (isset($_SESSION["username"]) && isset($_SESSION["role"])) {
    if ($_SESSION["role"] == "client") {
        header("Location:client/client.php");
        exit();
    }
    else if ($_SESSION["role"] == "employee") {
        header("Location:employee/employee.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <script type="text/javascript" src="js/index.js"></script>
</head>
<body>
    <h2>Welcome to the Goliath National Bank!</h2><br>
    <h4>Login with personal PIN</h4>
    <form method="post" action="authentication.php">
        <label for="user_input">Enter your username: </label><input type="text" name="username" id="user_input"><br>
        <label for="pw_input">Enter your password: </label><input type="password" name="password" id="pw_input"><br>
        <label for="pin_input">Enter your PIN: </label><input type="text" name="pin" id="pin_input"><br>
        <?php
        $error = null;
        if (isset($_GET) && isset($_GET["error"])) {
            $error = $_GET["error"];
            if ($error == "invalid") {
                echo "<b><font color='red'>Invalid login credentials!</font></b><br>";
            }
        }
        ?>
        <input type="submit" value="Login">
    </form>
</body>
</html>
