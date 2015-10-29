<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 16/10/15
 * Time: 20:47
 */

include "resource_mappings.php";
include_once getPageAbsolute("awesome");

$logo_svg = getMedia('logo_svg');

$nClients = 123;
$money = 65536;
$currency = '€';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="style/gnb.css">
    <script type="text/javascript" src="js/index.js"></script>
    <script type="text/javascript" src="js/asyncRequest.js"></script>
    <link rel="icon" type="image/png" href="media/gnb_icon.png" />
</head>
<body>
<div class="mainContainer">
    <img src="<?php echo $logo_svg ?>" alt="GNB Logo" class="logo_big">
    <div class="simpleContainer">
        <h1 class="title1">
            Join our <span id="nClients"><?php echo $nClients ?></span> LEGENDARY clients!
        </h1>
        <h1 class="title2">
            We have <span id="awesomeAmount"><?php echo $money.$currency ?></span> ... and counting!
            <button type="button" id="refreshButton" onclick="refreshAwesomeData()">Check again!</button>
        </h1>
    </div>
    <div class="simpleContainer">
        <h1 class="title3">Join our awesome bank now:</h1>
        <p class="simpleText">Compared to all the other boring banks out there, we can provide you lots of awesome services!<br>
            Transfers have never been easier and setting up your account only takes seconds!<br>
            Either open a new account for free or join our banking staff, you will not regret it!
        </p>
    </div>
    <div class="buttonContainer">
        <button type="button" onclick="goToLoginPage()" class="simpleButton">Login</button>
        <button type="button" onclick="goToRegistrationPage()" class="simpleButton">Register</button>
    </div>
</div>
</body>
</html>
