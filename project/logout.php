<?php

require_once __DIR__."/resource_mappings.php";

session_start();
session_destroy();
session_regenerate_id(true);
session_unset();

header("Location:".getPageUrl("index"));

?>
