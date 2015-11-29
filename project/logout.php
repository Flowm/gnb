<?php

session_start();
session_destroy();
session_regenerate_id(true);
session_unset();

header("Location:index.php");

?>
