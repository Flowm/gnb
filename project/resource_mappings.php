<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 19/10/15
 * Time: 21:24
 */

//THIS FILE CONTAINS THE MAPPINGS BETWEEN LOGICAL NAMES AND VIEWS

$root = realpath(dirname(__FILE__));

//PAGES (main pages of the client application)
$pages = array();
$pages["index"] = "$root/index.php";
$pages["home"] = "$root/index.php";
$pages["login"] = "$root/login.php";
$pages["logout"] = "$root/logout.php";
$pages["registration"] = "$root/registration.php";
$pages["employee"] = "$root/employee/employee.php";
$pages["client"] = "$root/client/client.php";

//SECTIONS (each section is triggered by a button)
$sections = array();
$sections["employee_overview"] = "$root/employee/employee_overview.php";
$sections["employee_home"] = "$root/employee/employee_overview.php";
$sections["employee_area"] = "$root/employee/employee_area.php";
$sections["client_overview"] = "$root/client/client_overview.php";
$sections["client_home"] = "$root/client/client_overview.php";
$sections["my_accounts"] = "$root/accounts/my_accounts.php";

//FRAMES (each frame is used inside a section, triggered by a submenu button)
$frames = array();
$frames["manage_clients"] = "$root/employee/manage_clients.php";
$frames["manage_client"] = "$root/employee/manage_client.php";
$frames["manage_registration"] = "$root/employee/manage_registration.php";
$frames["manage_transfer"] = "$root/employee/manage_transfer.php";
$frames["transfer_details"] = "$root/employee/transfer_details.php";
$frames["account_overview"] = "$root/accounts/account_overview.php";
$frames["account_home"] = "$root/accounts/account_overview.php";
$frames["new_transaction"] = "$root/accounts/new_transaction.php";
$frames["transaction_history"] = "$root/accounts/transaction_history.php";

?>
