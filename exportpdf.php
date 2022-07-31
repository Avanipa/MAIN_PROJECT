<?php 
session_start();
require_once('./include/connect.php');
include('pdf_mc_table.php');
if(isset($_POST['generatepdf']))
{
  $result = mysqli_query($conn,"SELECT * FROM `user_reg` join tbl_login on user_reg.login_id=tbl_login.login_id where tbl_login.role='user'");
  $pdf = new PDF_MC_TABLE();
  $pdf->AddPage();

  $pdf->SetFont('Arial', 'B', 15);
  $pdf->Cell(176, 5, 'User Details', 0, 0, 'C');
  $pdf->Ln();
  $pdf->Ln();
  $pdf->Ln();

  $pdf->SetFont('Arial','',10);
  
  $pdf->SetWidths(Array(11,20,25,30,20,20,20,30));

  $pdf->SetLineHeight(5);

  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(11,5,"Sl No",1,0);
  $pdf->Cell(20,5,"name",1,0);
  $pdf->Cell(25,5,"place",1,0);
  $pdf->Cell(30,5,"pin",1,0);
  $pdf->Cell(20,5,"gender",1,0);
  $pdf->Cell(20,5,"email",1,0);
  $pdf->Cell(20,5,"phone",1,0);


  $pdf->Ln();
  
  $pdf->SetFont('Arial','',10);	
  $i=1;
  foreach($result as $row) {
    $pdf->Row(Array(
        $i,
		$row['name'],
		$row['place'],
		$row['pin'],
		$row['gender'],
		$row['email'],
		$row['phone'],
	));
	$i++;
  }
  $pdf->Output();
}
?>