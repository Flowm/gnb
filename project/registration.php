<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 17/10/15
 * Time: 01:22
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
    <h2>Welcome to the Goliath National Bank!</h2><br>
    <h4>In order to register correctly, you will need to fill in the form below</h4>
    <form method="post" action="registration_request.php">
        What are you? <input type="radio" name="type" value="client" checked>Client&nbsp;&nbsp;
        <input type="radio" name="type" value="employee">Employee<br>
        Enter a valid username: <input type="text" name="username"><br>
        Enter your email address: <input type="email" name="email"><br>
        Enter a valid password: <input type="password" name="password"><br>
        Repeat the password: <input type="password" name="password_repeat"><br>
        <?php
        $error = null;
        if (isset($_GET) && isset($_GET["error"])) {
            $error = $_GET["error"];
            if ($error == "invalid") {
                echo "<b><font color='red'>Invalid login credentials!</font></b><br>";
            }
        }
        ?>
        <input type="submit" value="Register">
    </form>
</body>
</html>
