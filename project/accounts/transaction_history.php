<?php

require_once __DIR__."/../resource_mappings.php";

//Worst case, an unauthenticated user is trying to access this page directly
if (!isset($_SESSION["username"]) || !isset($_SESSION["role"])) {
    include(getPageAbsolute('error'));
    exit();
}
//The user is logged in, but tries to access another page directly
else if (!isset($frame)) {
    header("Location:".getPageURL('home'));
    exit();
}

require_once getpageabsolute("db_functions");
require_once getPageAbsolute("drawfunctions");

if (empty($_SESSION["account_id"]))
	die("Please choose an account");

$account_id = $_SESSION["account_id"];
$user_id = $_SESSION["user_id"];

$download_link		= getPageURL('tran_pdf') ;
$download_icon		= getMedia('pdf_download') ; 
$download_img_tag	= '<img src="'.$download_icon.'" alt="Download PDF"
					. width=16 height=16>' ; 
$pdf_download_link 	= '<a href="'.$download_link.'">'
					. $download_img_tag
					. '</a>' ; 

# showing transactions
$transaction_data 	= DB::i()->getAccountTransactionsWithNames($account_id) ; 


$headers		= array ('Status', 'Src/Dst #', 'Owner'
					,'Date/Time','Amount','Description') ;

#var_dump($transaction_data);
#exit() ; 
	
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
echo	'<th></th>';
foreach( $headers as $hdr ){
	echo  	'<th>'.$hdr.'</th>' ; 
}
echo		'</tr>' ;

# printing available data
for ( $i = 0 ; $i < $num_of_rec ; $i++ ){
	echo 	'<tr class="tbody-row-default">' ;
	
    $src_dest 		= null;
    $src_dest_name 	= null;
    $arrow_class 	= null;
    $arrow_pending 	= null;
    $desc 			= $transaction_data[$i]["description"] ;
    $t_status 		= DB::i()->mapTransactionStatus($transaction_data[$i]["status"]);
	$creation_date	= $transaction_data[$i]["ts"] ;
	$amount			= $transaction_data[$i]["amount"] ;
    
    if ($transaction_data[$i]["src_acc"] == $account_id) {
		
		$src_dest_name	= $transaction_data[$i]["dst_fname"].' '.$transaction_data[$i]["dst_lname"] ;
        $src_dest 		= $transaction_data[$i]["dst_acc"] ;
        $arrow_class 	= 'outgoing-transfer-arrow';
        $arrow_pending 	= 'outgoing-pending-arrow'; 
    }
    else if ($transaction_data[$i]["dst_acc"] == $account_id) {
		$src_dest_name	= $transaction_data[$i]["src_fname"].' '.$transaction_data[$i]["src_lname"] ;
        $src_dest 		= $transaction_data[$i]["src_acc"] ;
        $arrow_class 	= 'ingoing-transfer-arrow';
        $arrow_pending 	= 'ingoing-pending-arrow';
    }
    if ($t_status == 'approved') {
        echo "<td class='td-default'><span class='$arrow_class'></span></td>";
    }
    else {
        echo "<td class='td-default'><span class='$arrow_pending'></span></td>";
    }

    echo "<td class='td-default'>$t_status</td>";
    echo "<td class='td-default'>$src_dest</td>";
    echo "<td class='td-default'>$src_dest_name</td>";
    echo "<td class='td-default'>$creation_date</td>";
    echo "<td class='td-default'>$amount</td>";
    echo "<td class='td-default'>".wordwrap( $desc, 18, "<br>\n",true )."</td>";

	echo	'</tr>' ;
}
echo "</table>" ; 
?>
