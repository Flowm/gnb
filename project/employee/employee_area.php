<?php

require_once __DIR__."/../resource_mappings.php";
require_once getpageabsolute("utilityfunctions");

//Worst case, an unauthenticated user is trying to access this page directly
if (!isset($_SESSION["username"]) || !isset($_SESSION["role"])) {
    include(getPageAbsolute('error'));
    exit();
}
//The user is logged in, but tries to access another page directly
else if (!isset($section)) {
    header("Location:".getPageURL('home'));
    exit();
}
//Vertical privilege escalation attempt -> no go
$role = $_SESSION["role"];
if ($role != "employee") {
    include(getPageAbsolute('error'));
    exit();
}

$frameKey = 'manage_clients';
$frame = getFrameAbsolute('manage_clients'); //static default
if (isset($_POST["frame"])) {
    $frameKey = santize_input($_POST['frame'],SANITIZE_STRING_VAR);
    $frame = getFrameAbsolute($frameKey);
}

//Used for presentation purposes
$client_frame_keys = array('manage_clients','client_details','client_transaction_details');
$transfer_frame_keys = array('manage_transfer','transfer_details');
$registration_frame_keys = array('manage_registration');
$blocked_frame_keys = array('manage_blocked');

?>

<div class="frameContainer">
    <div class="frameMenu">
        <div class="menu-container">
            <ul class="menu-button-list">
                <li class="menu-button <?php
                if ($frameKey != null && in_array($frameKey, $client_frame_keys)) {
                    echo "menu-button-active";
                }
                ?>" onclick="goToEmployeeArea('manage_clients')">
                    <a class="menu-button-inner">Manage Clients</a>
                    <?php
                    if ($frameKey != null && in_array($frameKey, $client_frame_keys)) {
                        echo "<span class='menu-selected-arrow'></span>";
                    }
                    ?>
                </li>
                <li class="menu-button <?php
                if ($frameKey != null && in_array($frameKey, $transfer_frame_keys)) {
                    echo "menu-button-active";
                }
                ?>" onclick="goToEmployeeArea('manage_transfer')">
                    <a class="menu-button-inner">Pending transfer approvals</a>
                    <?php
                    if ($frameKey != null && in_array($frameKey, $transfer_frame_keys)){
                        echo "<span class='menu-selected-arrow'></span>";
                    }
                    ?>
                </li>
                <li class="menu-button <?php
                if ($frameKey != null && in_array($frameKey, $registration_frame_keys)) {
                    echo "menu-button-active";
                }
                ?>" onclick="goToEmployeeArea('manage_registration')">
                    <a class="menu-button-inner">New registration requests</a>
                    <?php
                    if ($frameKey != null && in_array($frameKey, $registration_frame_keys)) {
                        echo "<span class='menu-selected-arrow'></span>";
                    }
                    ?>
                </li>
                <li class="menu-button <?php
                if ($frameKey != null && in_array($frameKey, $blocked_frame_keys)) {
                    echo "menu-button-active";
                }
                ?>" onclick="goToEmployeeArea('manage_blocked')">
                    <a class="menu-button-inner">Blocked users</a>
                    <?php
                    if ($frameKey != null && in_array($frameKey, $blocked_frame_keys)) {
                        echo "<span class='menu-selected-arrow'></span>";
                    }
                    ?>
                </li>
            </ul>
        </div>
    </div>
    <div class="frameContent">
        <?php
        //INSERTING THE FRAME VIEW
        if ($frame != null) {
            include $frame;
        }
        ?>
    </div>
</div>
