<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 17/10/15
 * Time: 16:04
 */

global $frames;

$frame = $frames["manage_client"]; //static default
if (isset($_POST["frame"])) {
    $frame = $frames[$_POST["frame"]];
}
?>

<div>Show the existing clients and maybe their pending requests, who is currently and so on</div>

<div class="frameContainer">
    <div class="frameMenu">
        <ul>
            <li><a href="javascript:void(0)"
                   onclick="goToEmployeeArea('manage_client')">Client Overview</a></li>
            <li><a href="javascript:void(0)"
                   onclick="goToEmployeeArea('manage_transfer')">Pending transfer approvals</a></li>
            <li><a href="javascript:void(0)"
                   onclick="goToEmployeeArea('manage_registration')">New registration requests</a></li>
        </ul>
    </div>
    <div class="frameContent">
        <?php
        if ($frame != null) {
            include $frame;
        }
        ?>
    </div>
</div>