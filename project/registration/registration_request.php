<?php

/*Just process the received form, store the data inside the DB,
maybe return an error if the data already existed and finally return to the index */

require_once __DIR__."/../resource_mappings.php";
require_once getpageabsolute("db_functions");
require_once getPageAbsolute("mail");
require_once getpageabsolute("user");
require_once getPageAbsolute("util");

global $pages;

$error = "?error=";

//Getting form stuff
$type 			= check_post_input('type',SANITIZE_STRING_VAR);
$email 			= check_post_input('email',SANITIZE_STRING_EMAIL);
$firstname 		= check_post_input('firstname',SANITIZE_STRING_NAME);
$lastname 		= check_post_input('lastname', SANITIZE_STRING_NAME );
$password 		= (isset($_POST['password'])) ? $_POST['password'] : '';
$passwordRepeat = (isset($_POST['password'])) ? $_POST['password_repeat'] : '';
$banking 		= check_post_input('banking');

// Checking all of the conditions on server side as well.
if ($type == null
        || $email == null
        || $firstname == null
        || $lastname == null
        || $password == ''
        || $passwordRepeat == ''
        || $banking == '') {
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

$random_pin = generateRandomPIN();

//Checking the required banking option
if ($banking == 'email') {
    $auth_device = "TANs";
} else if ($banking == 'app') {
    $auth_device = "SCS";
} else {
    //We received a forged request, with invalid banking type
    $error = $error."6";
    header("Location:".getPageURL('registration').$error);
    exit();
}

$result = true;
//Checking the role
if ($type == 'client') {
    $result = DB::i()->addClient($firstname, $lastname, $email, $password, $random_pin, $auth_device);
} else if ($type == 'employee') {
    $result = DB::i()->addEmployee($firstname, $lastname, $email, $password, $random_pin);
} else {
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

$_SESSION['banking'] = $banking;
if ($auth_device == "SCS") {
    $_SESSION['pin'] = $random_pin;
}

$gnbmailer = new GNBMailer();
$gnbmailer->sendMail_Registration($email, "$firstname $lastname");

$logo_svg = getMedia('logo_svg'); //GNB logo

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../style/gnb.css">
    <link rel="icon" type="image/png" href="../media/gnb_icon.png" />
    <title>Registration</title>
</head>
<body>
<div class="mainContainer">
    <img src="<?php echo $logo_svg ?>" alt="GNB Logo" class="logo_big">
    <div class="simple-container">
        <?php
        $frame = null;
        if ($_SESSION['banking'] == 'email') {
            $frame = getFrameAbsolute('reg_default');
        }
        else if ($_SESSION['banking'] == 'app') {
            $frame = getFrameAbsolute('reg_pin');
        }
        include $frame;
        ?>
    </div>
    <div class="simple-container">
        <hr class="hr-thin">
        <h1 class="title4 simple-text-centered">
            This is gonna be LEGENDARY!!!
        </h1>
        <p class="simple-text simple-text-centered">
            <a href="../index.php">Return to Home page</a></p>
    </div>
    <div class="footerContainer">
        <hr class="hr-thin">
        <p class="simple-text simple-text-italic">This is not a real bank. All rights reserved.</p>
    </div>
</div>
</body>
</html>
