<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 17/10/15
 * Time: 16:04
 */

require_once __DIR__."/../resource_mappings.php";

$frameKey = 'manage_clients';
$frame = getFrameAbsolute('manage_clients'); //static default
if (isset($_POST["frame"])) {
    $frameKey = $_POST['frame'];
    $frame = getFrameAbsolute($frameKey);
}

//Used for presentation purposes
$client_frame_keys = array('manage_clients','client_details','client_transaction_details');
$transfer_frame_keys = array('manage_transfer','transfer_details');
$registration_frame_keys = array('manage_registration');

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
