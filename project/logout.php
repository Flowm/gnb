<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 17/10/15
 * Time: 15:17
 */

//Do some stuff, like removing session variables

session_start();
session_destroy();

header("Location:index.php");

?>