<?php

	define("SANITIZE_INT", 0);
	define("SANITIZE_DOUBLE", 1);
	define("SANITIZE_TEXT_BASIC", 2);
	define("SANITIZE_TEXT_EXTENDED", 3);
	define("SANITIZE_TEXT_EMAIL", 3);
	define("SANITIZE_TEXT_NAME", 3);
	
	$allowed_types	= 	array(
		'integer'		=> array('integer','double'),
		'double'		=> array('integer','double'),
		'string'		=> array('integer','double','string'),
	) ;
	
	function santize_input($input, $check_type = SANITIZE_TEXT_EXTENDED)
	{
		global	$allowed_types ;

		echo '<h2>'.'Sanitising Input'.'</h2>' ;
		$input_type			= gettype($input) ;
		$sanitized_input	= $input ;
		$sanitized_input	= htmlspecialchars($sanitized_input) ;
		
		echo '<h2>'.'Input Sanitised'.'</h2>' ;
		print_r($allowed_types[$input_type]) ;
		
		echo '<h2>'.'Checking Input Type'.'</h2>' ;
		# If input type not good then return false 
		if ( ! in_array($input_type,$allowed_types[$input_type]) ){ return false ; }
		echo '<h2>'.'Input Type Checked'.'</h2>' ;
		
		if ( $check_type == SANITIZE_INT ){
			# removing all non digits
			echo '<h2>'.'Integer Test'.'</h2>' ;
			$sanitized_input 	= preg_replace( '/[^0-9]/', '', $sanitized_input );
			$sanitized_input 	= intval($sanitized_input );
			
		} elseif ( $check_type == SANITIZE_DOUBLE ) {
			echo '<h2>'.'Double Test'.'</h2>' ;
			# removing all non digits and '.'
			$sanitized_input 	= preg_replace( '/[^0-9\.]/', '', $sanitized_input );	
			$sanitized_input 	= doubleval($sanitized_input );
			
		} elseif ( $check_type == SANITIZE_TEXT_BASIC ) {
			echo '<h2>'.'Text Basic'.'</h2>' ;
			# replacing any non all non alphanumerics and '-'
			$sanitized_input 	= preg_replace( '/[^-a-zA-Z0-9\-]/', '', $sanitized_input );				
		
		} elseif ($check_type == SANITIZE_TEXT_EXTENDED) {
			echo '<h2>'.'Text Extended'.'</h2>' ;
			# nothing to do here 
			$sanitized_input	= $sanitized_input ;
			
		} elseif ( $check_type == SANITIZE_TEXT_EMAIL ) {
			echo '<h2>'.'Text Email'.'</h2>' ;
			# replacing any non all non alphanumerics and '-'
			$sanitized_input 	= preg_replace( '/[^A-z0-9_\-\+\.\s]/', '', $sanitized_input );				
			
		} elseif ($check_type == SANITIZE_TEXT_NAME) {
			echo '<h2>'.'Text Name'.'</h2>' ;
			# nothing to do here 
			$sanitized_input 	= preg_replace( '/[^-a-zA-Z0-9\-]/', '', $sanitized_input );
			
		} else {
			# Undefined Type return error 	
			echo '<h2>'.'Undefined Test'.'</h2>' ;
			return false  ; 
		}
		return $sanitized_input ;
	}
	# testing 
	#$input 		= 'tedfg2..stx,,123'   ; 
	#$input_t 	= gettype($input)   ; 
	#$check		= SANITIZE_DOUBLE ;
	# 
	#$title	= santize_input($input,$check )  ; 
	#$data_1 	= $input; 
	#$data_2 	= $title; 
	#
	#echo '<h2>Before:'.$data_1.'</h2>' ;
	#echo '<h2>After:'.$data_2.'</h2>' ;
	
	#print_r($allowed_types[$input_t]) ;


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