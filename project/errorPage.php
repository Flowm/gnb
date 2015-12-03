<?php

require_once __DIR__."/resource_mappings.php";

header("HTTP/1.1 404 Not Found");

$logo_svg = getMedia('logo_svg'); //GNB logo
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GNB - 404</title>
    <link rel="stylesheet" type="text/css" href="<?= getPageURL('gnb_style') ?>">
    <link rel="icon" type="image/png" href="media/gnb_icon.png" />
</head>
<body>
<div class="mainContainer">
    <div class="headerContainer">
        <div class="logoContainer">
            <img src="<?php echo $logo_svg ?>" alt="GNB Logo" class="logo_small">
        </div>
        <div class="welcome-header">
            <h1 class="title2"><b>Sometimes we search for one thing ...<br>... but discover another!</b></h1>
        </div>
    </div>
</div>
</body>
</html>
