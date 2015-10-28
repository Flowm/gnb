<?php


include_once ('../main_include.php') ; 



function printHeaders($hdrs){
	
	$headerString	= '<tr>'."\n" ;
	foreach ($hdrs as $header){
		$headerString .= '<th>'.$header.'</th>'."\n" ;
	}
	$headerString	.= '</tr>'."\n" ;
	return $headerString ; 
}
function printData($data,$style){
	
	$dataString	= '<tr style="'.$style.'">'."\n" ;
	foreach ($data as $d){
		$dataString .= '<td>'.$d.'</td>'."\n" ;
	}
	$dataString	.= '</tr>'."\n" ;
	return $dataString ; 
}

$validStyle    	= 'background-color: green ;' ;
$invalidStyle  	= 'background-color: red;' ;
$uncheckedStyle = 'background-color: grey;' ;



# Check that user is allowed to transfer money for said account 
# not needed for phase 1 

# get input from $_POST only for testing 
#foreach ($_POST as $var => $value ){
#	$$var	= $value ; 
#}

# Test format is destination_account, ammount, description, tan_code
#$transaction_array 	= array('IBAN213213123', 500, 'Test Transaction I', '0252b28e2933542' ) ;

# Testing values 
$account_id=10000001 ;

$tran_set 	= array (
	0		=> array('IBAN213213123', 500, 'Test Transaction I', 'A345GHJKL127JOT' ),
	1		=> array('10000009', 250, 'Test Transaction II', 'A345GHJ67ZT7JOT' ),
	2		=> array('10000009', 50, 'Test Transaction III', 'A345GHJKL127JOT' ),
	3		=> array('IBAN213213123', 99, 'Test Transaction IV', 'A345GHJ67ZT7JOT' ),
	4		=> array('IBAN213213123', 250, 'Test Transaction V', '6ccbca603cd52a5' )
) ; 

# End of testing values 


# Need to inlclude mechanism for getting info from file 

$tran_headers			= array ( 'Destination', 'Amount', 'Description', 'TAN Code') ;
$validTANCodes			= array() ; 
$failedTransactionFound	= false ; 
$summary				= "[This information as accurate as of ".date("l jS \of F Y h:i:s A")."]" ; 

#echo 	

echo 	'<table>'  
	.	printHeaders($tran_headers) ; 
	
for ($i = 0 ; $i < sizeof($tran_set) ; $i++ ){
	
	# if one transaction fails stop checking for the rest 
	if ( $failedTransactionFound ){
		$style	= $uncheckedStyle ; 
	}
	else {
		$transaction_res = verify_transaction($account_id, $tran_set[$i][0], $tran_set[$i][1], $tran_set[$i][2], $tran_set[$i][3]) ;
		
		if ($transaction_res["result"] == false ){
			$failedTransactionFound		= true ; 
			$style 						= $invalidStyle ;	
			$summary					= $transaction_res["message"]; 
		} 
		else {
			$tempIndex		= $tran_set[$i][3] ;
			if (array_key_exists($tempIndex, $validTANCodes)){
				# If TAN Code has already been used in another transactions then fail
				$style 					= $invalidStyle ;
				$failedTransactionFound	= true ;
				$summary	= '[TAN Code]TAN Code already used on Transaction#'.$validTANCodes["$tempIndex"] ; 
			} 
			else {
				$style 								= $validStyle ;
				$validTANCodes[$tran_set[$i][3]]	= $i+1 ; 
			}			
			# Adding TAN Code to table to prevent multiple valid TAN Codes 
		}	
	}
	# This might need to change depending on CSS deployment 
	echo	printData($tran_set[$i], $style) ;  
}

if (empty($summary)){
		$summary	= 'All transcations are verfied based on data on '.date("l jS \of F Y h:i:s A") ; 
}

echo 	'<thead>
	.	<tr>'.'<th colspan="4">'.$summary.'</th>'.'</tr>
	.	<tr>'.'<th colspan="4">'
	.	"[This information as accurate as of ".date("l jS \of F Y h:i:s A")."]"
	.	'</th>'.'</tr>
	.	</thead>' 
	.	'</table>' ;

?>