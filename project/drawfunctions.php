<?php

function drawMultipleRecordTable($data,$record_name = 'record(s)'){
	
	# getting number of headings ( data columns )
	$num_of_col		= count($data[0]) ;
	#$num_of_col		= 2;

	# getting number of recordsd
	$num_of_rec		= count($data) ;
	
	# Setting the summary line 
	$summary 		= $num_of_rec.' '.$record_name.' available' ; 

	
	echo '<table class="table-default">' ;
	
	# drawing headers and footer
	echo 	'<thead>'
		.	'<tr>'.'<th colspan="'.$num_of_col.'" class="th-default"'
		.	'style="text-align: left;"'
		.	'>'.$summary.'</th>' 
		.	'</tr>'.'</thead>' ;
		
	echo 	'<tfoot>'
		.	'<tr>'.'<th colspan="'.$num_of_col.'" class="th-default"'
		.	'style="text-align: left;"'
		.	'>'.$summary.'</th>' 
		.	'</tr>'.'</tfoot>' ;

	
	# drawing column titles
	echo 	'<tr>' ; 
	foreach( $data[0] as $title => $value){
		echo  '<th class="th-default">'.$title.'</th>' ; }
	echo	'</tr>' ;
	
	# printing availble data 
	for ( $i = 0 ; $i < $num_of_rec ; $i++ ){
		echo 	'<tr>' ;
		foreach( $data[$i] as $title => $value){
			echo  '<th class="th-default">'.$value.'</th>' ; 
			$j=0 ;
		}
		echo	'</tr>' ;
	}
	echo "</table>" ;
}

		
function drawSinglerecordTable($data,$record_name = 'Record',$headers=array()){
	
	# getting number of headings ( data columns )
	$num_of_col		= count($data) ;

	# getting number of records .. this should alwyas be 1 
	$num_of_rec		= count($data) ;
	
	# Setting the summary line 
	$summary 		= $record_name.' information' ;

	
	echo 	'<h1 class="title4">'.$summary.'</h1>' ;
	#echo 	'<thead>'
	#	.	'<tr class="thead-row-default">'
	#	.	'<th colspan="2">'.$summary.'</th>' 
	#	.	'</tr>'.'</thead>' ;
	
	echo '<table>' ;
	foreach( $data as $title => $value){
		echo 	'<tr">'  
			.	'<td><b>'
			.	(array_key_exists($title,$headers) ? $headers[$title] :$title) 
			.	' </b></td>'
			.	'<td> '.$value.'</td>'
			. 	'</tr>' ;
			
	}
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
	
	echo '<table class="table-default">' ;
	
	# drawing headers
	echo 	'<thead>'."\n"
		.	'<tr class="thead-row-default">'
		.	'<th class="th-default" colspan="3">'.$summary.'</th>'."\n" 
		.	'</tr>'.'</thead>'."\n" ;
	
	foreach( $transaction_res as $title => $value){
		
		#$style 	= ($value[1] ? $valid_style : $invalid_style ) ; 
		echo 	'<tr class="thead-row-default">'."\n"  
			.	'<th class="th-default">'.$title.'</th>'."\n" 
			.	'<td class="td-default">'.$value[0].'</td>'."\n"
			.	'<td class="td-default">'.	$value[2].'</td>'."\n"
			. 	'</tr>'."\n" ;
	}
	
	echo "</table>"."\n" ;
}


?>