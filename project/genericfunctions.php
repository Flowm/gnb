<?php

	define("SANITIZE_INT", 0);
	define("SANITIZE_DOUBLE", 1);
	define("SANITIZE_STRING_NAME", 2);
	define("SANITIZE_STRING_EMAIL", 3);
	define("SANITIZE_STRING_DESC", 4);
	
	function santize_input($input, $check_type = SANITIZE_STRING_DESC)
	{
		$sanitized_input	= htmlspecialchars($input) ;
		
		$sanitization	= array (
			'result'	=> false, 
			'input'		=> '',
			'message'	=> 'Generic Sanitization failed',
		) ;
		
		if ( $check_type == SANITIZE_INT ){
			$sanitized_input	= filter_var($sanitized_input, FILTER_SANITIZE_NUMBER_INT);
			if ( $sanitized_input !== false ){
				$sanitization['result']		= true ; 
				$sanitization['input']		= $sanitized_input ; 
				$sanitization['message']	= 'Integer sanitizated succesfully' ; 
			}
			
		} elseif ( $check_type == SANITIZE_DOUBLE ) {
			$sanitized_input	= filter_var($sanitized_input, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION );
			# Removing multiple '.' in the number 
			# check if double has a decimal point
			
			$first_dec	= strpos($sanitized_input,'.') ;
			$last_dec	= strrpos($sanitized_input,'.') ;
			$str_length	= strlen($sanitized_input) ;
			
			# check if double has more than 1 decimal point and remove if needed
			if (  $first_dec !== false ){
				if ( $first_dec != $last_dec ){
					$first_part			= substr($sanitized_input,0,$first_dec+1) ; 
					$last_part			= substr($sanitized_input,$first_dec+2) ;
					$last_part			= str_replace('.','',$last_part) ;
					$sanitized_input	= $first_part.$last_part ;
				}
			}
			
			if ( $sanitized_input !== false ){
				$sanitization['result']		= true ; 
				$sanitization['input']		= $sanitized_input ; 
				$sanitization['message']	= 'Double sanitizated succesfully' ; 
			}
		} elseif ( $check_type == SANITIZE_STRING_NAME ) {
			$sanitized_input	= filter_var($sanitized_input, FILTER_SANITIZE_STRING);
			$sanitized_input 	= preg_replace( '/[^-a-zA-Z0-9\-]/', '', $sanitized_input );		if ( $sanitized_input !== false ){
				$sanitization['result']		= true ; 
				$sanitization['input']		= $sanitized_input ; 
				$sanitization['message']	= 'Name sanitizated succesfully' ; 
			}
		} elseif ( $check_type == SANITIZE_STRING_EMAIL ) {
			$sanitized_input	= filter_var($sanitized_input, FILTER_SANITIZE_EMAIL);
			$sanitized_input 	= preg_replace( '/[^A-z0-9_\-\+\.\s]/', '', $sanitized_input );
			if ( $sanitized_input !== false ){
				$sanitization['result']		= true ; 
				$sanitization['input']		= $sanitized_input ; 
				$sanitization['message']	= 'Email sanitizated succesfully' ; 
			}							
		} elseif ($check_type == SANITIZE_STRING_DESC) {
			$sanitized_input	= filter_var($sanitized_input, FILTER_SANITIZE_STRING);
			if ( $sanitized_input !== false ){
				$sanitization['result']		= true ; 
				$sanitization['input']		= $sanitized_input ; 
				$sanitization['message']	= 'String sanitizated succesfully' ; 
			}										
		} else {
			$sanitization['result']		= true ; 
			$sanitization['input']		= $sanitized_input ; 
			$sanitization['message']	= 'String sanitizated succesfully' ; 
		}
		return $sanitization  ;
	}
	
function base64ToBase10($base64Str) {
	$alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";

	$result = 0;
	foreach(str_split($base64Str) as $letter) {
		$result = ($result*64) + strpos($alphabet,$letter);
	}
	return $result;
}

function verifyAppGeneratedTAN($tan, $pin, $iban, $amount) {
    $salt = substr($tan,-5);

    $timestamp = base64ToBase10($salt);

    $data = $pin . $iban . $amount . $salt;
    $hash = hash('sha256',$data, true);

    $hash = base64_encode($hash);

    $result = substr($hash,0,10) . $salt;
    if ($result == $tan) {
        return $timestamp;
    }
    else {
        return null;
    }
}

function generateRandomPIN() {
    $random_pin = "";
    for ($i = 0; $i < 6; $i++) {
        $random_pin .= mt_rand(0,9);
    }
    return $random_pin;
}

?>