<?php

require_once __DIR__."/../resource_mappings.php";
require_once getpageabsolute("util");

session_start();

if (!isset($_SESSION["username"]) || !isset($_SESSION["role"])) {
    include(getPageAbsolute('error'));
    exit();
}
$role = $_SESSION["role"];
if ($role != "employee") {
    include(getPageAbsolute('error'));
    exit();
}
if (!isset($_SESSION["timeout"])) {
    $_SESSION['timeout'] = time();
} elseif (time() > $_SESSION['timeout'] + 60 * 20){
    include(getPageAbsolute('logout'));
    exit();
}

$logo_svg = getMedia('logo_svg'); //GNB logo

$page = getPageAbsolute('employee'); //static

$sectionKey = 'employee_overview';
$section = getSectionAbsolute('employee_home'); //static default
if (isset($_POST["section"])) {
    $sectionKey = santize_input($_POST["section"],SANITIZE_STRING_VAR);
    $section = getSectionAbsolute($sectionKey);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GNB - Employee</title>
    <link rel="stylesheet" type="text/css" href="../style/frame.css">
    <link rel="stylesheet" type="text/css" href="../style/gnb.css">
    <script type="text/javascript" src="../js/employee.js"></script>
    <script type="text/javascript" src="../js/postRequest.js"></script>
    <script type="text/javascript" src="../js/account.js"></script>
    <script type="text/javascript" src="../js/asyncRequest.js"></script>
    <link rel="icon" type="image/png" href="../media/gnb_icon.png" />
</head>
<body>
<div class="nav-container nav-default">
    <div class="nav-bar">
        <ul class="nav-button-bar nav-left">
            <li class="nav-button <?php
                if ($sectionKey != null && $sectionKey == 'employee_overview') {
                    echo "nav-button-active";
                }
                else {
                    echo "nav-button";
                } ?> nav-left" onclick="goToOverview()"><a class="nav-button-inner">Overview</a></li>
            <li class="nav-button <?php
            if ($sectionKey != null && $sectionKey == 'employee_area') {
                echo "nav-button-active";
            }
            else {
                echo "nav-button";
            } ?> nav-left" onclick="goToEmployeeArea()"><a class="nav-button-inner">Employee Area</a></li>
            <li class="nav-button <?php
            if ($sectionKey != null && $sectionKey == 'my_accounts') {
                echo "nav-button-active";
            }
            else {
                echo "nav-button";
            } ?> nav-left" onclick="goToMyAccounts()"><a class="nav-button-inner">My Accounts</a></li>
        </ul>
        <ul class="nav-button-bar nav-right">
            <li class="nav-button nav-right" onclick="logout()"><a class="nav-button-inner">Logout</a></li>
        </ul>
    </div>
</div>
<div class="nav-placeholder"></div>
<div class="mainContainer">
    <div class="headerContainer">
        <div class="logoContainer">
            <img src="<?php echo $logo_svg ?>" alt="GNB Logo" class="logo_small">
        </div>
        <div class="welcome-header">
            <h1 class="title2"><b>Welcome back, <?php echo $_SESSION["firstname"]." ".$_SESSION["lastname"] ?>!</b></h1>
        </div>
    </div>
    <hr class="hr-large">
    <?php
    //We keep the same "button-bar" and include the correct additional section, which contains the actual data
    if ($section != null) {
        include $section;
    }
    ?>
    <div class="footerContainer">
        <hr class="hr-thin">
        <p class="simple-text simple-text-italic">This is not a real bank. All rights reserved.</p>
    </div>
</div>
</body>
</html>
