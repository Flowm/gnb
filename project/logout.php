<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 17/10/15
 * Time: 15:17
 */

session_start();
session_destroy();
session_regenerate_id(true);
session_unset();

header("Location:index.php");

?>