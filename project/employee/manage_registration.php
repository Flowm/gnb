<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 18/10/15
 * Time: 11:03
 */

include "../user.php";
include "../bankfunctions.php";
include "welcome_user.php";

//Handling approval/rejections first
if (isset($_POST['approved_registrations'])) {
    approveUserRegistrations($_POST['approved_registrations']);
}
elseif (isset($_POST['reject_registrations'])) {
    //STILL NEED TO IMPLEMENT IT
}

$data = getPendingRequests();
$newUsers = array();
if ($data != null) {
    foreach ($data as $u) {
        array_push($newUsers, new user($u));
    }
}

//IN CASE WE HAVE NO PENDING REQUESTS
if (count($newUsers) == 0) {
    echo "<p>There currently are no new registration requests</p>";
    exit();
}

?>

<p>There are <?php count($newUsers) ?> new registration requests awaiting your approval</p>

<table>
    <tr>
        <th></th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Role</th>
    </tr>
    <?php
    for ($i =0; $i < count($newUsers); $i++) {
        $user = $newUsers[$i];
        $role = array_search($user->role, $USER_ROLES);
        echo "<tr>
            <td><input type='checkbox' name='action_check' id='$user->id:$role'></td>
            <td>$user->firstname</td>
            <td>$user->lastname</td>
            <td>$user->email</td>";
        echo "<td>$role</td> <!-- We want an icon here -->
        </tr>";
    }
    ?>
</table>
<table>
    <tr>
        <td><input type="checkbox" id="selectAll_check" onclick="checkAllBoxes()">
            <label for="selectAll_check">Select/deselect all</label></td>
    </tr>
</table>

<p>What should be done with the selected registration requests?</p>
<button type="button" onclick="approveRegistration()">Approve</button>
<button type="button" onclick="rejectRegistration()">Reject</button>