<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 17/10/15
 * Time: 15:51
 */

include "../resource_mappings.php"; //All view mappings are loaded in here

session_start();

global $pages;
global $sections;
global $frames;

if (!isset($_SESSION["username"]) || !isset($_SESSION["role"])) {
    header("Location:".$pages["home"]);
    exit();
}
$role = $_SESSION["role"];
if ($role != "employee") {
    header("Location:".$pages["home"]);
}

$page = $pages["employee"]; //static

$section = $sections["employee_home"]; //static default
if (isset($_POST["section"]) && isset($sections[$_POST["section"]])) {
    $section = $sections[$_POST["section"]];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GNB Employee</title>
    <link rel="stylesheet" type="text/css" href="../style/frame.css">
    <script type="text/javascript" src="../js/employee.js"></script>
    <script type="text/javascript" src="../js/postRequest.js"></script>
    <script type="text/javascript" src="../js/account.js"></script>
    <script type="text/javascript" src="../js/asyncRequest.js"></script>
</head>
<body>
<h2>Welcome to the Goliath National Bank!</h2><br>
<h4>Welcome back, <?php echo $_SESSION["username"]?>!</h4><br>
<div>
    <button type="button" onclick="goToOverview()">Overview</button>
    <button type="button" onclick="goToEmployeeArea()">Employee Area</button>
    <button type="button" onclick="goToMyAccounts()">My Accounts</button>
    <button type="button" onclick="logout()">Logout</button>
</div><br><hr><br>
<?php
//We keep the same "button-bar" and include the correct additional section, which contains the actual data
if ($section != null) {
    include $section;
}
?>
</body>
</html>
