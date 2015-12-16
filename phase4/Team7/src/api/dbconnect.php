<?php
global $db;
$db = mysqli_connect("localhost", "root", "crazypassword", "Banking");
//$db = mysqli_connect("localhost", "root", "root", "Banking");
if(mysqli_connect_errno())
{
    exit("Connecting to database failed: ".mysqli_connect_error());
}
?>