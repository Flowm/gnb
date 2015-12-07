<?php 

define("REQ_SUCCESS",0);
define("REQ_INVALID",51);
define("TOKEN_EMPTY",10);
define("PIN_EMPTY",21);
define("PW_EMPTY",31);
define("PW_REPEAT_EMPTY",32);
define("PWS_DONT_MATCH",33);
define("PWS_WEAK",34);
define("USERNAME_EMPTY",34);
define("UNKNOWN_ERROR",99);

$reset_messages  	= array(
	REQ_SUCCESS		=> "Your password has been reset succesfully.",
	TOKEN_EMPTY		=> "No token found" ,
	USERNAME_EMPTY	=> "No email found" ,
	PIN_EMPTY		=> "PIN field is empty" ,
	REQ_INVALID		=> "Invalid Pin or invalid or expired Token.",
	PW_EMPTY		=> "Password field is empty" ,
	PW_REPEAT_EMPTY	=> "Repeat password field is empty" ,
	PWS_DONT_MATCH	=> "Passwords dont match" ,
	PWS_WEAK		=> "Your password must be between 8 and 20 characters long and must contain at least 1 number and 1 letter!" ,
	UNKNOWN_ERROR	=> "Other Unknown error.",
);

?>