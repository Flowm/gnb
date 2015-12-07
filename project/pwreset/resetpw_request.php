<?php

require_once __DIR__."/../resource_mappings.php";
require_once getpageabsolute("db_functions");
require_once getpageabsolute("user");
require_once getPageAbsolute("util");
require_once getPageAbsolute("pw_hdr");
session_start();

function msg_header($msg_no,$token=''){
	if ( empty($token)){
		header("Location:".getPageURL('resetpw').'?msg='.$msg_no);
	} else {
		header("Location:".getPageURL('resetpw').'?token='.$token.'&msg='.$msg_no);
	}	
}
$email		= null ; 
$pin 		= null ;
$pw 		= null ;
$pw_repeat 	= null ;


if (isset($_POST["token"]) ) {
    $token = santize_input($_POST["token"],SANITIZE_STRING_VAR);
}
if ( empty($token)){
	msg_header(TOKEN_EMPTY) ;
    exit();
}
if (isset($_POST["email"]) ) {
    $email = santize_input($_POST["email"],SANITIZE_STRING_EMAIL);
}
if ( empty($email)){
	msg_header(USERNAME_EMPTY) ;
    exit();
}

if (isset($_POST["pin"]) ) {
    $pin = santize_input($_POST["pin"],SANITIZE_STRING_DESC);
}
 
if ( empty($pin)){
	msg_header(PIN_EMPTY,$token) ;
    exit();
}

if (isset($_POST["password"]) ){ 
	$pw 	= $_POST["password"]; 
}
if (empty($pw)){
	msg_header(PW_EMPTY,$token) ;
    exit();
}
 
if (isset($_POST["password_repeat"])){ 
	$pw_repeat  	= $_POST["password_repeat"];
} 

if (empty($pw_repeat)){
	msg_header(PW_REPEAT_EMPTY,$token) ;
    exit();
}

if ($pw != $pw_repeat ){
	msg_header(PWS_DONT_MATCH,$token) ;
    exit();
}
if ( ! checkPasswordStrength($pw)){
	msg_header(PWS_WEAK,$token) ;
    exit();
}

if ( DB::i()->resetPassword($email,$pw, $token, $pin) === false ){
	msg_header(REQ_INVALID,$token) ;
    exit();
} else {
	msg_header(REQ_SUCCESS) ;
    exit();
}

msg_header(UNKNOWN_ERROR,$token) ;
exit()

?>
