<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 17/10/15
 * Time: 16:04
 */

require_once __DIR__."/../resource_mappings.php";

$frame = getFrameAbsolute('manage_clients'); //static default
if (isset($_POST["frame"])) {
    $frame = getFrameAbsolute($_POST['frame']);
}
?>

<div class="frameContainer">
    <div class="frameMenu">
        <div class="menu-container">
            <ul class="menu-button-list">
                <li class="menu-button" onclick="goToEmployeeArea('manage_clients')">
                    <a class="menu-button-inner">Manage Clients</a>
                </li>
                <li class="menu-button" onclick="goToEmployeeArea('manage_transfer')">
                    <a class="menu-button-inner">Pending transfer approvals</a>
                </li>
                <li class="menu-button" onclick="goToEmployeeArea('manage_registration')">
                    <a class="menu-button-inner">New registration requests</a>
                </li>
            </ul>
        </div>
        <!--<ul>
            <li><a href="javascript:void(0)"
                   onclick="goToEmployeeArea('manage_clients')">Manage Clients</a></li>
            <li><a href="javascript:void(0)"
                   onclick="goToEmployeeArea('manage_transfer')">Pending transfer approvals</a></li>
            <li><a href="javascript:void(0)"
                   onclick="goToEmployeeArea('manage_registration')">New registration requests</a></li>
        </ul>-->
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
