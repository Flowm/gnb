<?php
//THIS FILE CONTAINS THE MAPPINGS BETWEEN LOGICAL NAMES AND VIEWS/RESOURCES

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

// Detect base path and base url of application
$base_dir = __DIR__;
$protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
$domain = $_SERVER['HTTP_HOST'];
$doc_root = $_SERVER['DOCUMENT_ROOT'];
$url_dir = str_replace($doc_root, '', $base_dir);
$base_url = $protocol.$domain.$url_dir; //NORMAL USAGE
//$base_url = $protocol.$domain."/gnb/project"; //THIS IS STATIC AND ONLY FOR PHPSTORM, DUE TO IDE BUG
define ('BASE_DIR', $base_dir ."/");
define ('BASE_URL', $base_url ."/");

//PAGES (main pages of the client application)
$pages = array();
$pages["index"] 			= "index.php";
$pages["home"] 				= "index.php";
$pages["login"] 			= "login.php";
$pages["logout"] 			= "logout.php";
$pages["db_functions"] 		= "db.php";
$pages["awesome"] 			= "awesome_data.php";
$pages["error"] 			= "error_page.php";
$pages["util"] 				= "genericfunctions.php";
$pages["drawfunctions"] 	= "drawfunctions.php";
$pages["utilityfunctions"] 	= "genericfunctions.php";

$pages["uploads"] 		= "tmp/uploads/";
$pages["holder"] 		= "tmp/holder/";

$pages["forgotpw"] 		= "pwreset/forgotpw.php";
$pages["forgotpw_r"] 	= "pwreset/forgotpw_request.php";
$pages["resetpw"] 		= "pwreset/resetpw.php";
$pages["resetpw_r"] 	= "pwreset/resetpw_request.php";
$pages["pw_hdr"] 		= "pwreset/pwreset_header.php";

$pages["registration"] 	= "registration/registration.php";

$pages["user"] 			= "models/user.php";
$pages["account"] 		= "models/account.php";
$pages["transaction"] 	= "models/transaction.php";

$pages["employee"] 		= "employee/employee.php";

$pages["client"] 		= "client/client.php";

$pages["tran_pdf"] 		= "accounts/download_transactions.php";

$pages["gnb_style"] 	= "style/gnb.css";

$pages["scs"] 			= "resources/SmartCardSimulator.jar";

$pages["mail"] 				= "lib/gnbmailer/gnbmailer.php";
$pages["fpdf"] 				= "lib/fpdf/fpdf.php";
$pages["fpdf_protection"] 	= "lib/fpdf/fpdf_protection.php";
$pages["phpmailer"] 		= "lib/phpmailer/PHPMailerAutoload.php";
$pages["ctransact"] 		= "lib/ctransact/ctransact";
$pages["secimg"] 			= "lib/securimage/securimage.php";
$pages["secimg_show"] 		= "inc/secimg_show.php";
$pages["secimg_show_lib"] 	= "lib/securimage/securimage_show.php";

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
$frames["reg_default"] = "registration/registration_default.php";
$frames["reg_pin"] = "registration/registration_pin.php";
$frames["manage_clients"] = "employee/manage_clients.php";
$frames["client_details"] = "employee/client_details.php";
$frames["client_transaction_details"] = "employee/client_transaction_details.php";
$frames["manage_registration"] = "employee/manage_registration.php";
$frames["manage_blocked"] = "employee/manage_blocked.php";
$frames["manage_transfer"] = "employee/manage_transfer.php";
$frames["transfer_details"] = "employee/transfer_details.php";
$frames["transaction_view"] = "accounts/transaction_view.php";
$frames["account_overview"] = "accounts/account_overview.php";
$frames["account_home"] = "accounts/account_overview.php";
$frames["new_transaction"] = "accounts/new_transaction.php";
$frames["new_transaction_multiple"] = "accounts/new_transaction_multiple.php";
$frames["transaction_history"] = "accounts/transaction_history.php";
$frames["verify_transaction"] = "accounts/verify_transaction.php";

$media = array();
$media["logo_png"] 		= "media/gnb_logo.png";
$media["logo_svg"] 		= "media/gnb_logo.svg";
$media["logo_icn"] 		= "media/gnb_icon.png";
$media["capcha_reload"] = "media/capcha_reload.png";
$media["ceo_img"] 		= "media/gnb_bs_img1.jpg";
$media["pdf_download"] 	= "media/download_pdf.svg";


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

function getMediaAbsolute($name) {
    global $media;

    return getResource(BASE_DIR, $media, $name);
}

?>
