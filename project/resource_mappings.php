<?php
//THIS FILE CONTAINS THE MAPPINGS BETWEEN LOGICAL NAMES AND VIEWS/RESOURCES

// Detect base path and base url of application
$base_dir = __DIR__;
$protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
$domain = $_SERVER['HTTP_HOST'];
$doc_root = $_SERVER['DOCUMENT_ROOT'];
$url_dir = str_replace($doc_root, '', $base_dir);
$base_url = $protocol.$domain.$url_dir; //NORMAL USAGE
//$base_url = $protocol.$domain."/gnb/project"; //THIS IS STATIC AND ONLY FOR PHPSTORM
define ('BASE_DIR', $base_dir ."/");
define ('BASE_URL', $base_url ."/");

//PAGES (main pages of the client application)
$pages = array();
$pages["index"] = "index.php";
$pages["home"] = "index.php";
$pages["login"] = "login.php";
$pages["logout"] = "logout.php";
$pages["registration"] = "registration.php";
$pages["employee"] = "employee/employee.php";
$pages["client"] = "client/client.php";
$pages["db_functions"] = "bankfunctions.php";
$pages["mail"] = "gnbmailer.php";
$pages["user"] = "models/user.php";
$pages["account"] = "models/account.php";
$pages["transaction"] = "models/transaction.php";
$pages["awesome"] = "awesome_data.php";

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

$media = array();
$media["logo_svg"] = "media/gnb_logo.svg";
$media["logo_icn"] = "media/gnb_icon.png";


function getResource($prefix, $resources, $target) {
    if (!isset($resources[$target])) {
        return null;
    }
    return $prefix.$resources[$target];
}

function getPageAbsolute($page) {
    global $pages;

    return getResource(BASE_DIR, $pages, $page);
}

function getPageURL($page) {
    global $pages;

    return getResource(BASE_URL, $pages, $page);
}

function getSectionAbsolute($section) {
    global $sections;

    return getResource(BASE_DIR, $sections, $section);
}

function getSectionURL($section) {
    global $sections;

    return getResource(BASE_URL, $sections, $section);
}

function getFrameAbsolute($frame) {
    global $frames;

    return getResource(BASE_DIR, $frames, $frame);
}

function getFrameURL($frame) {
    global $frames;

    return getResource(BASE_URL, $frames, $frame);
}

function getMedia($name) {
    global $media;

    return getResource(BASE_URL, $media, $name);
}

?>
