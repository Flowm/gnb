<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 17/10/15
 * Time: 16:09
 */

/*Just process the received form, store the data inside the DB,
maybe return an error if the data already existed and finally return to the index */

include "resource_mappings.php";

global $pages;

$error = "?error=";
if (!isset($_POST['type'])
    || !isset($_POST['email'])
    || !isset($_POST['firstname'])
    || !isset($_POST['lastname'])
    || !isset($_POST['password'])
    || !isset($_POST['password_repeat'])) {
    $error = $error."0";
    header("Location:".getPageURL('registration').$error);
    exit();
}

//Don't want to include this for naught
include "bankfunctions.php";
include "user.php";

//Getting form stuff
$type = $_POST['type'];
$email = $_POST['email'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$password = $_POST['password'];
$passwordRepeat = $_POST['password_repeat'];

/*Checking all of the conditions on server side as well.
I know this is security-related, but it just pains me to see someone
inserting an invalid email or similar flaws in Phase 1 already. */
if ($type == ''
        || $email == ''
        || $firstname == ''
        || $lastname == ''
        || $password == ''
        || $passwordRepeat == '') {
    $error = $error."0";
    header("Location:".getPageURL('registration').$error);
    exit();
}
if ($password != $passwordRepeat) {
    $error = $error."1";
    header("Location:".getPageURL('registration').$error);
    exit();
}
if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
    $error = $error."2";
    header("Location:".getPageURL('registration').$error);
    exit();
}

$result = true;
if ($type == 'client') {
    $result = addClient($firstname, $lastname, $email, $password);
}
else if ($type == 'employee') {
    $result = addEmployee($firstname, $lastname, $email, $password);
}
if (!$result) {
    $error = $error."3";
    header("Location:".getPageURL('registration').$error);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
    <h2>Welcome to the Goliath National Bank!</h2><br>
    <p>You request has been received and will be processed shortly.
        Upon approval, you will receive a confirmation email.<br>
        Thank you for choosing the Goliath National Bank!</p>
    <a href="index.php">Return to Home page</a>
</body>
</html>
