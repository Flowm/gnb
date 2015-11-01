<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 17/10/15
 * Time: 15:51
 */

require_once __DIR__."/../resource_mappings.php";

session_start();

if (!isset($_SESSION["username"]) || !isset($_SESSION["role"])) {
    header("Location:".getPageURL('home'));
    exit();
}
$role = $_SESSION["role"];
if ($role != "employee") {
    header("Location:".getPageURL('home'));
    exit();
}

$logo_svg = getMedia('logo_svg');

$page = getPageAbsolute('employee'); //static

$section = getSectionAbsolute('employee_home'); //static default
if (isset($_POST["section"])) {
    $section = getSectionAbsolute($_POST["section"]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GNB Employee</title>
    <link rel="stylesheet" type="text/css" href="../style/frame.css">
    <link rel="stylesheet" type="text/css" href="../style/gnb.css">
    <script type="text/javascript" src="../js/employee.js"></script>
    <script type="text/javascript" src="../js/postRequest.js"></script>
    <script type="text/javascript" src="../js/account.js"></script>
    <script type="text/javascript" src="../js/asyncRequest.js"></script>
</head>
<body>
<div class="nav-container nav-default">
    <div class="nav-bar">
        <ul class="nav-button-bar nav-left">
            <li class="nav-button nav-left" onclick="goToOverview()"><a class="nav-button-inner">Overview</a></li>
            <li class="nav-button nav-left" onclick="goToEmployeeArea()"><a class="nav-button-inner">Employee Area</a></li>
            <li class="nav-button nav-left" onclick="goToMyAccounts()"><a class="nav-button-inner">My Accounts</a></li>
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
            <h1 class="title4"><b>Welcome back, <?php echo $_SESSION["firstname"]." ".$_SESSION["lastname"] ?>!</b></h1>
        </div>
    </div>
    <hr class="hr-large">
    <?php
    //We keep the same "button-bar" and include the correct additional section, which contains the actual data
    if ($section != null) {
        include $section;
    }
    ?>
</div>
</body>
</html>
