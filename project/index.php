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
    <!--<div class="simple-container">
        <h1 class="title1">
            Join our <span id="nClients"><?php echo $nClients ?></span> LEGENDARY clients!
        </h1>
        <h1 class="title3">
            We have <span id="awesomeAmount"><?php echo $money.$currency ?></span> ... and counting!
            <button type="button" id="refreshButton" onclick="refreshAwesomeData()">Check again!</button>
        </h1>
    </div>-->
    <div class="headerContainer">
        <div class="index-text-container">
            <h1 class="title1">
                Join our <span id="nClients"><?php echo $nClients ?></span> LEGENDARY clients!
            </h1>
            <h1 class="title3">
                We have <span id="awesomeAmount"><?php echo $money.$currency ?></span> ... and counting!
                <button type="button" id="refreshButton" onclick="refreshAwesomeData()">Check again!</button>
            </h1>
            <h1 class="title4">Join our awesome bank now!</h1>
            <p class="simple-text-index">Reasons why you should join us:</p>
            <ul class="simple-text-index">
                <li class="bank-list">Creating a bank account has never been easier.</li>
                <li class="bank-list">Banking is generally not cool? Well, we made it cool anyway.</li>
                <li class="bank-list">How much does having an account at GNB cost? Absolutely nothing.</li>
                <li class="bank-list">You usually get champagne for christmas? What a joke! You will get free champagne at any of our weekly parties. Members only!</li>
                <li class="bank-list">Need a loan? Well, you won't get any. But we will give you money. For free.</li>
                <li class="bank-list">Our employees are the coolest people in the world. True story.</li>
            </ul>
        </div>
        <div class="img-container">
            <img src="<?= getMedia('ceo_img') ?>" class="index_img" alt="Our awesome CEO">
        </div>
    </div><br>
    <div class="button-container">
        <button type="button" onclick="goToLoginPage()" class="simpleButton">Login</button>
        <button type="button" onclick="goToRegistrationPage()" class="simpleButton">Register</button>
    </div>
</div>
</body>
</html>
