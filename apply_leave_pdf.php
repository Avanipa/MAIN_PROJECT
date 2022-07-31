<?php

require_once('./include/connect.php');
session_start();

if(!isset($_SESSION['regid'])){
    header('Location:./index');
    exit();
}

$id = $_SESSION['regid'];

$sql = "SELECT  leave_type, applied_date, from_date, to_date, leave_reason FROM tbl_applied_leaves WHERE applied_leaves_reg_id='$id' order by applied_leaves_id desc limit 1)";
$result=mysqli_query($conn);




// include('pdf_mc_table.php');
// $pdf = new PDF_MC_TABLE();
// $pdf->AddPage();
// $pdf->SetFont('Arial','B',15);  
// $pdf->Cell(176, 5, 'Employment Exchange', 0, 0, 'C');


//   $pdf->Ln();
//   $pdf->Ln();
//   $pdf->Ln(); 
// $row=mysqli_fetch_array($result);
// $pdf->SetFont('Arial','',12); 
// $pdf->Multicell(0,12,'Leave Type : '. $row['leave_type'] ,0,1);
// $pdf->Multicell(0,12,'Applied Date : '. $row['applied_date'],0,1);
// $pdf->Multicell(0,12,'From Date : '. $row['from_date'],0,1);
// $pdf->Multicell(0,12,'To Date : '. $row['to_date'],0,1);
// $pdf->Multicell(0,12,'Leave Reason : '. $row['leave_reason'],0,1);


// $pdf->Output();
?>