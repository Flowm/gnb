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
        <ul>
            <li><a href="javascript:void(0)"
                   onclick="goToEmployeeArea('manage_clients')">Manage Clients</a></li>
            <li><a href="javascript:void(0)"
                   onclick="goToEmployeeArea('manage_transfer')">Pending transfer approvals</a></li>
            <li><a href="javascript:void(0)"
                   onclick="goToEmployeeArea('manage_registration')">New registration requests</a></li>
        </ul>
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
