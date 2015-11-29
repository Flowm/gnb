<?php

function checkPasswordStrength($pass) {
    $regExp= "#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[#.-_,$%&!]).*$#";
    if (preg_match($regExp, $pass)) {
        return true;
    }
    return false;
}

/*Just process the received form, store the data inside the DB,
maybe return an error if the data already existed and finally return to the index */

require_once "resource_mappings.php";
require_once getpageabsolute("db_functions");
require_once getPageAbsolute("mail");
require_once getpageabsolute("user");

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
if (!checkPasswordStrength($password)) {
    $error = $error."4";
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
else {
    //We received a forged request, with an invalid role
    $error = $error."5";
    header("Location:".getPageURL('registration').$error);
    exit();
}
if (!$result) {
    $error = $error."3";
    header("Location:".getPageURL('registration').$error);
    exit();
}
$gnbmailer = new GNBMailer();
$gnbmailer->sendMail_Registration($email, "$firstname $lastname");

$logo_svg = getMedia('logo_svg'); //GNB logo

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style/gnb.css">
    <link rel="icon" type="image/png" href="media/gnb_icon.png" />
    <title>Registration</title>
</head>
<body>
<div class="mainContainer">
    <img src="<?php echo $logo_svg ?>" alt="GNB Logo" class="logo_big">
    <div class="simpleContainer">
        <h1 class="title3">Welcome to the Goliath National Bank!</h1>
        <p class="simple-text-big simple-text-centered">
            Your request has been received and will be processed shortly.
            A confirmation email will be sent to you, once your registration has been approved.<br>
            Thank you for choosing the Goliath National Bank!
        </p>
        <h1 class="title4 simple-text-centered">
            This is gonna be LEGENDARY!!!
        </h1>
        <p class="simple-text simple-text-centered">
            <a href="index.php">Return to Home page</a></p>
    </div>
</div>
</body>
</html>
