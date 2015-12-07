<?php

require_once "resource_mappings.php";
require_once getPageAbsolute("util");

$username = null;
$error = '?error=0';
if (isset($_POST["username"])) {
    $username = santize_input($_POST["username"],SANITIZE_STRING_EMAIL);
}
if (isset($_POST["password"])) {
    $pw = santize_input($_POST["password"]);
}

if ($username == null ) {
    header("Location:".getPageURL('login').$error);
    exit();
}

# Check if email is in database 
$username = 'mahmoud.shadid@gmail.com' ;

# Generate Hash for that user - maybe check if it has already been genereted


# send email to user 

 exit();


header("Location:".getPageURL('login').$error);

?>
