<?php

require_once __DIR__."/../resource_mappings.php" ;
require_once getPageAbsolute("util") ;
require_once getPageAbsolute("db_functions") ;
require_once getPageAbsolute("mail") ;

function msg_header($msg_no = 9){
	header("Location:".getPageURL('forgotpw').'?msg='.$msg_no);
}


$messages 	= array(
	0	=> "Invalid or empty email entered.",
	1	=> "Sorry bro, you're not registered with us." ,
	2	=> "Token generation failed, please email our IT department." ,
	#3	=> "You have already been sent an email with instuctions",
	4	=> "Email sent, please check your email." ,
	9	=> "Unknown error occured"
);

if (isset($_POST["username"])){
	$username	= santize_input($_POST["username"],SANITIZE_STRING_EMAIL) ;
}

if ($username == null ){
	msg_header(0);
	exit();
}


# create Hash for token
$token		= DB::i()->genRandString(128) ;

# Set Hash in DB Table
if  ( DB::i()->setPasswordResetHash($username,$token) === false ){
	# even if this funtion fails customer still gets a success message
	msg_header(4);
	exit() ;
}

# Set URL and send email to user
$reset_url	= getPageURL("resetpw").'?token='.$token ;
$cust_name	= 'Legend' ;
$gnbmailer 	= new GNBMailer();
$gnbmailer->sendMail_PasswordReset($username, $cust_name, $reset_url) ;
msg_header(4);
exit();

?>
