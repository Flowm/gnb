<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 18/10/15
 * Time: 00:19
 */

include "../user.php";
include "../bankfunctions.php";

$user = null;
if (isset($_POST['client_id'])) {
    $search = getClientDetails($_POST['client_id']);
    $user = new user($search);
}

if ($user == null) {
    include getSectionAbsolute('manage_clients');
    exit();
}

$data = getAccountsForUser($user->id);
$user->setAccounts($data);
$accounts = $user->accounts;

$selected = (count($accounts) > 0) ? $accounts[0] : null;
?>

<button type="button" onclick="goToEmployeeArea('manage_clients')">Back</button><br>
<p>
    <?php
    echo "Client $user->id overview";
    ?>
</p>
<table id="client_info">
    <?php
    echo "<tr>
            <td>First name:</td>
            <td> $user->firstname </td>
        </tr>";
    echo "<tr>
            <td>Last name:</td>
            <td> $user->lastname </td>
        </tr>";
    echo "<tr>
            <td>Email:</td>
            <td> $user->email </td>
        </tr>";
    echo "<tr>
            <td>Role:</td>
            <td> $user->role </td>
        </tr>";
    echo "<tr>
            <td>Status:</td>
            <td> $user->status </td>
        </tr>";
    ?>
</table>
<br>
<p>Current selected account is <?php echo $selected->id ?></p>
<label for="account_select">Select a different account: </label><select id="account_select">
    <!-- Still need some onclick JS here-->
    <?php
    for ($i=0; $i<count($accounts); $i++) {
        $account = $accounts[$i];
        echo "<option value='$account->id' ";
        if ($selected != null && $selected == $account) {
            echo "selected ";
        }
        echo ">$account->id</option>";
    }
    ?>
</select><br>

<p>Here should be the account_overview frame, similar to the one found in the account section</p>
