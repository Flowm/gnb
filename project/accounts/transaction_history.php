<?php

require_once __DIR__."/../resource_mappings.php";
require_once getpageabsolute("db_functions");
require_once getPageAbsolute("drawfunctions");

if (empty($_SESSION["user_id"]))
	die("User missing");
if (empty($_SESSION["account_id"]))
	die("Please choose an account");

$account_id = $_SESSION["account_id"];
$user_id = $_SESSION["user_id"];

$download_link		= '../accounts/download_transactions.php' ;
$download_icon		= '../media/download_pdf.svg' ; 
$download_img_tag	= '<img src="'.$download_icon.'" alt="Download PDF"
					. width=16 height=16>' ; 
$pdf_download_link 	= '<a href="'.$download_link.'">'
					. $download_img_tag
					. '</a>' ; 

# showing transactions
$transaction_data 	= getAccountTransactions($account_id) ; 
$skip_headers		= array( 'id','approved_by_user_id','approved_at','destination_account_id','tan_id') ; 
$header_decode 		= array(
	'status'				=> 'Status',
	'source_account_id'		=> 'Source/Dest',
	'creation_timestamp'	=>  'Date/Time',
	'amount'				=>  'Amount',
	'description'			=> 'Description'
);
	
	
# getting number of headings ( data columns ) and columns
$num_of_col			= count($transaction_data[0]) ;
$num_of_rec			= count($transaction_data) ;
$summary 			= $num_of_rec.' transaction(s) available '; 

echo '<table class="table-default" style="white-space: nowrap">' ;
# drawing headers and footer
echo 	'<thead>'
	.	'<tr class="thead-row-default">'.'<th colspan="'.$num_of_col.'"'
	.	'class="th-default" style="text-align: left;"'.'>'
	.	'<div style="float:left;width:50%;">'.$summary.'</div>' 
	.	'<div style="float:right;width:50%;">'.$pdf_download_link.'</div>' 
	.	'</th></tr>'.'</thead>' ;
	
echo 	'<tfoot>'
	.	'<tr class="thead-row-default">'.'<th colspan="'.$num_of_col.'"'
	.	'class="th-default" style="text-align: left;"'.'>'
	.	'<div style="float:left;width:50%;">'.$summary.'</div>' 
	.	'<div style="float:right;width:50%;">'.$pdf_download_link.'</div>' 
	.	'</tr>'.'</tfoot>' ;


# drawing column titles
echo 	'<tr class="thead-row-default">' ; 
foreach( $transaction_data[0] as $title => $value){
	if ( in_array($title,$skip_headers) ) { continue ; }
	echo  	'<th>'
		.	(array_key_exists($title,$header_decode) ? $header_decode[$title] :$title)
		.'	</th>' ; }
echo		'</tr>' ;

# printing availble data 
for ( $i = 0 ; $i < $num_of_rec ; $i++ ){
	echo 	'<tr class="">' ;
	foreach( $transaction_data[$i] as $title => $value){
		if ( in_array($title,$skip_headers) ) { continue ; }
		$data	= $value ; 
		if ( $title	== 'status' ){ $value	= $TRANSACTION_STATUS[$value] ; }
		if ( $title	== 'source_account_id' ){
			if( $value == $account_id){
				$data	= $transaction_data[$i]["destination_account_id"] ; 
			}
		}
		if ( $title == 'amount') {
			if ($transaction_data[$i]['source_account_id'] == $account_id) {
				$data = number_format($data * -1.0, 2);
			}
		}
		if ( $title	== 'description' ){ $data	= wordwrap( $data, 18, "<br>\n",true ) ; }
		echo  '<td class="td-default">'.$data.'</td>' ; 
	}
	echo	'</tr>' ;
}
echo "</table>" ; 
?>
