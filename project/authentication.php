<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 17/10/15
 * Time: 14:21
 */

//Check credentials: if ok proceed to the correct overview, otherwise return to login.php and print some error
//If authentication successful, set some session variables

//TODO: Is that the right position for that include?
include 'bankfunctions.php';

session_start();

$username = null;
$pw = null;
$pin = null;
if (isset($_POST["username"])) {
    $username = $_POST["username"];
}
if (isset($_POST["password"])) {
    $pw = $_POST["password"];
}
if (isset($_POST["pin"])) {
    $pin = $_POST["pin"];
}

if ($username == null || $pw == null || $pin == null) {
    header("Location:login.php?error=invalid");
    exit();
}

if (RecordInTable('Barney', 'first_name', 'user')) {
	header("Location:employee/employee.php");
    $_SESSION["username"] = $username;
    $_SESSION["role"] = "employee";
    exit();
}


//HARDCODED!!
if ($username == "ted" && $pw == "ted" && $pin == "1234") {
    header("Location:employee/employee.php");
    $_SESSION["username"] = $username;
    $_SESSION["role"] = "employee";
    exit();
}
else if ($username == "tum" && $pw == "tum" && $pin == "1234") {
    $_SESSION["username"] = $username;
    $_SESSION["role"] = "client";
    header("Location:client/client.php");
    exit();
}

header("Location:login.php?error=invalid");
