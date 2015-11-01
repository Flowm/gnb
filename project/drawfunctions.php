<?php

function drawMultipleRecordTable($data,$record_name = 'record(s)'){
	
	# getting number of headings ( data columns )
	$num_of_col		= count($data[0]) ;
	#$num_of_col		= 2;

	# getting number of recordsd
	$num_of_rec		= count($data) ;
	
	# Setting the summary line 
	$summary 		= $num_of_rec.' '.$record_name.' available' ; 

	
	echo '<table border="1">' ;
	
	# drawing headers and footer
	echo 	'<thead>'
		.	'<tr>'.'<th colspan="'.$num_of_col.'" style="text-align: left;"'
		.	'>'.$summary.'</th>' 
		.	'</tr>'.'</thead>' ;
		
	echo 	'<tfoot>'
		.	'<tr>'.'<th colspan="'.$num_of_col.'" style="text-align: left;"'
		.	'>'.$summary.'</th>' 
		.	'</tr>'.'</tfoot>' ;

	
	# drawing column titles
	echo 	'<tr>' ; 
	foreach( $data[0] as $title => $value){
		echo  '<th>'.$title.'</th>' ; }
	echo	'</tr>' ;
	
	# printing availble data 
	for ( $i = 0 ; $i < $num_of_rec ; $i++ ){
		echo 	'<tr>' ;
		foreach( $data[$i] as $title => $value){
			echo  '<th>'.$value.'</th>' ; 
			$j=0 ;
		}
		echo	'</tr>' ;
	}
	
	echo "</table>" ;
}

		
function drawSinglerecordTable($data,$record_name = 'Record'){
	
	# getting number of headings ( data columns )
	$num_of_col		= count($data) ;

	# getting number of records .. this should alwyas be 1 
	$num_of_rec		= count($data) ;
	
	# Setting the summary line 
	$summary 		= $record_name.' infromation' ; 

	
	echo '<table border="1">' ;
	
	# drawing headers
	echo 	'<thead>'
		.	'<tr>'.'<th colspan="2">'.$summary.'</th>' 
		.	'</tr>'.'</thead>' ;
	
	foreach( $data as $title => $value){
		echo 	'<tr>'  
			.	'<th>'.$title.'</th>' 
			.	'<td>'.$value.'</td>' 
			. 	'</tr>' ;
	}
	#var_dump($data) ;
	
	echo "</table>" ;
}

function drawSingleTransactionTable($transaction_res){
	
	# getting number of headings ( data columns )
	#$num_of_col		= count($data) ;

	# getting number of records .. this should alwyas be 1 
	#$num_of_rec		= count($data) ;
	
	# Setting the summary line 
	$summary 		= 'Transaction verification summary'  ; 

	$valid_style 	= 'background-color: green ;' ; 
	$invalid_style 	= 'background-color: red;' ; 
	
	echo '<table border="1">' ;
	
	# drawing headers
	echo 	'<thead>'."\n"
		.	'<tr>'.'<th colspan="3">'.$summary.'</th>'."\n" 
		.	'</tr>'.'</thead>'."\n" ;
	
	foreach( $transaction_res as $title => $value){
		
		$style 	= ($value[1] ? $valid_style : $invalid_style ) ; 
		
		
		echo 	'<tr style="'.$style.'">'."\n"  
			.	'<th>'.$title.'</th>'."\n" 
			.	'<td>'.$value[0].'</td>'."\n"
			.	'<td>'.	$value[2].'</td>'."\n"
			. 	'</tr>'."\n" ;
	}
	
	echo "</table>"."\n" ;
}


?>