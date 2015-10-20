<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 17/10/15
 * Time: 15:05
 */

include "../view_mappings.php"; //All view mappings are loaded in here

global $pages;
global $sections;
global $frames;

session_start();

if (!isset($_SESSION["username"]) || !isset($_SESSION["role"])) {
    header("Location:".$pages["home"]);
    exit();
}
$role = $_SESSION["role"];
if ($role != "client") {
    header("Location:".$pages["home"]);
}

$page = $pages["client"]; //static

$section = $sections["client_home"]; //static default
if (isset($_POST["section"])) {
    $section = $sections[$_POST["section"]];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <script type="text/javascript" src="../js/client.js"></script>
    <script type="text/javascript" src="../js/postRequest.js"></script>
    <script type="text/javascript" src="../js/account.js"></script>
</head>
<body>
<h2>Welcome to the Goliath National Bank!</h2><br>
<h4>Welcome back, <?php echo $_SESSION["username"] ?>!</h4><br>
<div>
    <button type="button" onclick="goToOverview()">Overview</button>
    <button type="button" onclick="goToMyAccounts()">My Accounts</button>
    <button type="button" onclick="logout()">Logout</button>
</div><br><hr><br>
<?php
//We keep the same "button-bar" and include the correct additional section, which contains the actual data
include $section;
?>
</body>
</html>