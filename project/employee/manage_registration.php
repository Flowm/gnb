<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 18/10/15
 * Time: 11:03
 */
?>

<p>Here we can show a list of new registrations (both clients and employees), which need to be approved</p>

<p>There are 3 new registration requests awaiting your approval</p>

<table>
    <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Action</th>
    </tr>
    <tr>
        <td>ted</td>
        <td>mosby</td>
        <td>noob@gnb.com</td>
        <td>Employee</td> <!-- We want an icon here -->
        <td><input type="checkbox" name="action_check" id="<?php echo 'ID00045'; ?>"></td>
    </tr>
    <tr>
        <td>barney</td>
        <td>stinson</td>
        <td>trololo@gnb.com</td>
        <td>Client</td> <!-- We want an icon here -->
        <td><input type="checkbox" name="action_check" id="<?php echo 'ID00046'; ?>"></td>
    </tr>
</table>
<p>What should be done with the selected requests?</p>
<button type="button" onclick="approveRegistration()">Approve</button>
<button type="button" onclick="rejectRegistration()">Reject</button>