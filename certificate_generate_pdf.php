<?php

require_once('./include/connect.php');
session_start();


if(!isset($_SESSION['regid'])){
    header('Location: index');
    exit();
}

$id = $_SESSION['regid'];


$sql = "SELECT  R.uname, R.uaddress, R.aadharno, R.gender, R.dob, R.place, R.district FROM tbl_registration R JOIN tbl_login L WHERE L.usertype=0 AND L.login_id = R.regid ";
$result=mysqli_query($conn,$sql);


 include('pdf_mc_table.php');
 $pdf = new PDF_MC_TABLE();
 $pdf->AddPage();
 $pdf->SetFont('Arial','B',15);  
 $pdf->Image('indialogo.jpg',10,10,12);

 $pdf->Cell(176, 5, 'Employment Exchange Certificate' , 0, 0, 'C');



   $pdf->Ln();
   $pdf->Ln();
   $pdf->Ln(); 
 $row=mysqli_fetch_array($result);
 $pdf->SetFont('Arial','',12); 
 $pdf->Multicell(0,12,'Name : '. $row['uname'] ,0,1);
 $pdf->Multicell(0,12,'Address : '. $row['uaddress'],0,1);
 $pdf->Multicell(0,12,'Aadhar No: '. $row['aadharno'],0,1);
 $pdf->Multicell(0,12,'Gender : '. $row['gender'],0,1);
 $pdf->Multicell(0,12,'Date 0f Birth : '. $row['dob'],0,1);
 $pdf->Multicell(0,12,'Place : '. $row['place'],0,1);
 $pdf->Multicell(0,12,'District : '. $row['district'],0,1);

$pdf->Image('sign1.jpg',140,120,30);
// $currentdate = date("Y-m-d");
// echo $currentdate;


// function Footer() {
//   // Positionnement à 1,5 cm du bas
//   $this->SetY(-15);

//   // Police Arial italique 8
//   $this->SetFont('Arial','I',8);

//   // Date du jour
//   setlocale(LC_TIME, "");
//   date_default_timezone_set('Asia/Kolkata');
//   $date_du_jour = utf8_encode(strftime('%A %d %B %Y'));
//   $this->Cell(0,10,$date_du_jour,0,0,'L');
  
//   // Numéro de page
//   $this->Cell(0,10,'Page '.$this->PageNo().' sur {nb}',0,0,'R');
// }


 $pdf->Output();
?>




