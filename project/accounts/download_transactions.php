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
require_once getPageAbsolute("fpdf");

session_start();

if (empty($_SESSION["account_id"]))
	die("Please choose an account");

$account_id = $_SESSION["account_id"];
$transaction_data 	= DB::i()->getAccountTransactions($account_id) ; 
$headers			= array_keys($transaction_data[0]) ; 

$pdf = new FPDF();
$pdf->AddPage('L');
$cell_number	= 40 ; 

# Add image/header for GNB Bank 
$pdf->SetY(10); 
$pdf->SetFont('Arial','',20); 	//Set Font to Arial/Helvetica 20 pt font
$pdf->SetFont('Arial','',20); 	//Set Font to Arial/Helvetica 20 pt font
$pdf->SetTextColor(0,0,0); 		//Set Text Color to Black;
$pdf->Cell(0,30,"GNB Transaction record for Account #$account_id",0,1,'C');   

$pdf->Image('../media/gnb_logo.png',10,10,-300);

# Add transaction info 
$pdf->SetFont('Arial','B',10);

# getting number of records and setting summary line
$num_of_rec		= count($transaction_data) ;
$record_name 	= 'Transaction(s)' ;
$summary 		= $num_of_rec.' '.$record_name.' available' ; 

$header_width	= array (12, 35, 35, 15, 35, 35, 35,15, 30, 30 ) ;

# drawing headers and summary
$pdf->Cell(array_sum($header_width),7,$summary, 1); #,0, 'C', true) ;
$pdf->Ln() ; 

$pdf->SetFillColor(255,0,0);
$pdf->SetTextColor(255);
$pdf->SetDrawColor(128,0,0);
$pdf->SetLineWidth(.3);
$pdf->SetFont('','B',8);

for ( $i =0 ; $i < count($headers) ; $i++ ){ 
	$pdf->Cell($header_width[$i],7,$headers[$i], 1,0,'C',true ); #,0, 'C', true) ;
}
$pdf->Ln() ; 
	
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$pdf->SetFont('');	
$fill	= false ; 

# printing availble data 
for ( $i = 0 ; $i < $num_of_rec ; $i++ ){
	for ( $j =0 ; $j < count($headers) ; $j++ ){ 
		$pdf->Cell($header_width[$j],6,$transaction_data[$i]["$headers[$j]"],'LR',0,'L',$fill); 
		$fill	= !$fill ; 
	}
	$pdf->Ln() ; 
}
$pdf->Ln() ; 

$pdf->Output();

?>
