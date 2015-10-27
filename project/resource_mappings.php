<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 19/10/15
 * Time: 21:24
 */

//THIS FILE CONTAINS THE MAPPINGS BETWEEN LOGICAL NAMES AND VIEWS

$root = realpath(dirname(__FILE__));
$root = $root."/";
$absolute_url = "http://$_SERVER[HTTP_HOST]/gnb/project/";

//PAGES (main pages of the client application)
$pages = array();
$pages["index"] = "index.php";
$pages["home"] = "index.php";
$pages["login"] = "login.php";
$pages["logout"] = "logout.php";
$pages["registration"] = "registration.php";
$pages["employee"] = "employee/employee.php";
$pages["client"] = "client/client.php";
$pages["user"] = "user.php";
$pages["mail"] = "gnbmailer.php";
$pages["db_functions"] = "bankfunctions.php";

//SECTIONS (each section is triggered by a button)
$sections = array();
$sections["employee_overview"] = "employee/employee_overview.php";
$sections["employee_home"] = "employee/employee_overview.php";
$sections["employee_area"] = "employee/employee_area.php";
$sections["client_overview"] = "client/client_overview.php";
$sections["client_home"] = "client/client_overview.php";
$sections["my_accounts"] = "accounts/my_accounts.php";

//FRAMES (each frame is used inside a section, triggered by a submenu button)
$frames = array();
$frames["manage_clients"] = "employee/manage_clients.php";
$frames["manage_client"] = "employee/manage_client.php";
$frames["manage_registration"] = "employee/manage_registration.php";
$frames["manage_transfer"] = "employee/manage_transfer.php";
$frames["transfer_details"] = "employee/transfer_details.php";
$frames["account_overview"] = "accounts/account_overview.php";
$frames["account_home"] = "accounts/account_overview.php";
$frames["new_transaction"] = "accounts/new_transaction.php";
$frames["new_transaction_multiple"] = "accounts/new_transaction_multiple.php";
$frames["transaction_history"] = "accounts/transaction_history.php";


function getResource($prefix, $resources, $target) {
    if (!isset($resources[$target])) {
        return null;
    }
    return $prefix.$resources[$target];
}

function getPageAbsolute($page) {
    global $root;
    global $pages;

    return getResource($root, $pages, $page);
}

function getPageURL($page) {
    global $absolute_url;
    global $pages;

    return getResource($absolute_url, $pages, $page);
}

function getSectionAbsolute($section) {
    global $root;
    global $sections;

    return getResource($root, $sections, $section);
}

function getSectionURL($section) {
    global $absolute_url;
    global $sections;

    return getResource($absolute_url, $sections, $section);
}

function getFrameAbsolute($frame) {
    global $root;
    global $frames;

    return getResource($root, $frames, $frame);
}

function getFrameURL($frame) {
    global $absolute_url;
    global $frames;

    return getResource($absolute_url, $frames, $frame);
}

?>
