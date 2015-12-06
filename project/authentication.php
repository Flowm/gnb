<?php

//Check credentials: if ok proceed to the correct overview, otherwise return to login.php and print some error
//If authentication successful, set some session variables

require_once "resource_mappings.php";
require_once getpageabsolute("db_functions");
require_once getpageabsolute("user");
require_once getPageAbsolute("util");
session_start();

$username = null;
$pw = null;
$error = '?error=0';
if (isset($_POST["username"])) {
    $username = santize_input($_POST["username"],SANITIZE_STRING_EMAIL);
}
if (isset($_POST["password"])) {
    $pw = santize_input($_POST["password"]);
}

if ($username == null || $pw == null) {
    header("Location:".getPageURL('login').$error);
    exit();
}

$result = DB::i()->loginUser($username, $pw);
if (!$result) {
    header("Location:".getPageURL('login').$error);
}
else {
    $user = new user($result);
    $role = DB::i()->mapUserRole($user->role);
    session_regenerate_id(true);
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
        //Cannot really happen
    }
    exit();
}

header("Location:".getPageURL('login').$error);

?>
