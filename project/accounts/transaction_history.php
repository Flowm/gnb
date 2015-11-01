<?php
/**
 * Created by PhpStorm.
 * User: lorenzodonini
 * Date: 18/10/15
 * Time: 00:32
 */
 

include_once ('../main_include.php') ;
$account_id	= $_POST["account"] ; 
$user_id = $_SESSION["user_id"] ;  

if ( empty($account_id) ){
	die("Please choose an account")  ; 
} 

$download_link		= '../accounts/download_transactions.php' ;
$download_icon		= '../media/download_pdf.svg' ; 
$download_img_tag	= '<img src="'.$download_icon.'" alt="Download PDF"
					. width=16 height=16>' ; 
$pdf_download_link 	= '<a href="'.$download_link.'">'
					. $download_img_tag
					. '</a>' ; 

# showing transactions
$transaction_data 	= getAccountTransactions($account_id) ; 
$skip_headers		= array('approved_by_user_id','approved_at') ; 

# getting number of headings ( data columns ) and columns
$num_of_col			= count($transaction_data[0]) ;
$num_of_rec			= count($transaction_data) ;
$summary 			= $num_of_rec.' transaction(s) available '; 

echo '<table border="1" style="white-space: nowrap">' ;
# drawing headers and footer
echo 	'<thead>'
	.	'<tr>'.'<th colspan="'.$num_of_col.'" style="text-align: left;"'.'>'
	.	'<div style="float:left;width:50%;">'.$summary.'</div>' 
	.	'<div style="float:right;width:50%;">'.$pdf_download_link.'</div>' 
	.	'</th></tr>'.'</thead>' ;
	
echo 	'<tfoot>'
	.	'<tr>'.'<th colspan="'.$num_of_col.'" style="text-align: left;"'.'>'
	.	'<div style="float:left;width:50%;">'.$summary.'</div>' 
	.	'<div style="float:right;width:50%;">'.$pdf_download_link.'</div>' 
	.	'</tr>'.'</tfoot>' ;


# drawing column titles
echo 	'<tr>' ; 
foreach( $transaction_data[0] as $title => $value){
	if ( in_array($title,$skip_headers) ) { continue ; }
	echo  '<th>'.$title.'</th>' ; }
echo	'</tr>' ;

# printing availble data 
for ( $i = 0 ; $i < $num_of_rec ; $i++ ){
	echo 	'<tr>' ;
	foreach( $transaction_data[$i] as $title => $value){
		if ( in_array($title,$skip_headers) ) { continue ; }
		if ( $title	== 'status' ){ $value	= $TRANSACTION_STATUS[$value] ; }
		echo  '<th>'.$value.'</th>' ; 
	}
	echo	'</tr>' ;
}

echo "</table>" ; 
?>

