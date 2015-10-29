<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 17/10/15
 * Time: 14:21
 */

//Check credentials: if ok proceed to the correct overview, otherwise return to login.php and print some error
//If authentication successful, set some session variables

require_once "resource_mappings.php";
require_once getpageabsolute("db_functions");
require_once getpageabsolute("user");

session_start();

$username = null;
$pw = null;
$error = '?error=0';
if (isset($_POST["username"])) {
    $username = $_POST["username"];
}
if (isset($_POST["password"])) {
    $pw = $_POST["password"];
}

if ($username == null || $pw == null) {
    header("Location:".getPageURL('login').$error);
    exit();
}

//HARDCODED!! NEED LOGIN FUNCTION TO ACTUALLY DO THIS RIGHT
if ($username == "ted" && $pw == "ted") {
    header("Location:".getPageURL('employee'));
    $_SESSION["username"] = $username;
    $_SESSION["role"] = "employee";
    exit();
}

$result = loginUser($username, $pw);
if (!$result) {
    header("Location:".getPageURL('login').$error);
}
else {
    $user = new user($result);
    $role = array_search($user->role, $USER_ROLES);
    $_SESSION["username"] = $user->email;
    $_SESSION["role"] = $role;
    $_SESSION["user_id"] = $user->id;
    $_SESSION["firstname"] = $user->firstname;
    $_SESSION["lastname"] = $user->lastname;
    if ($role == 'client') {
        header("Location:".getPageURL('client'));
    }
    elseif ($role == 'employee') {
        header("Location:".getPageURL('employee'));
    }
    else {
        //TODO: ERROR? WTF?
    }
    exit();
}

header("Location:".getPageURL('login').$error);

?>
