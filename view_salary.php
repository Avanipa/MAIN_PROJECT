<?php

require_once('./include/connect.php');
session_start();
include 'encdec.php';

// For changing servertime zone to India
date_default_timezone_set("Asia/Kolkata");

if(!isset($_SESSION['regid'])){
    header('Location: index');
    exit();
}

$id = $_SESSION['regid'];

if(isset($_POST['submit'])){
    $month = $_POST['month'];
    $year = (int)substr($month, 0, 4);
    $month = substr($month, -2);
    $month_name = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $month = $month_name[(int)$month-1];
    // echo $month;
    // $sql = "select * from tbl_salary where salary_regid='$id' and month='$month' and year='$year'";
    $sql = "select S.salary, R.uname from tbl_salary S, tbl_registration R where S.salary_regid='$id' and S.month='$month' and S.year='$year'";
    $result = mysqli_query($conn, $sql);
    if($result){
        $row = mysqli_fetch_assoc($result);
        if($row != null){

            include('pdf_mc_table.php');
            $pdf = new PDF_MC_TABLE();
            $pdf->AddPage();
            $pdf->SetFont('Arial','B',15);  
            $pdf->Cell(200, 25, 'Employment Exchange', 0, 0, 'C');
            $pdf->Image('indialogo.jpg',100,0,12);
            $pdf->Ln();
            $pdf->SetFont('Arial','',12); 
            $pdf->Multicell(0,12,'Hello '.$row['uname'].',' ,0,1);
            $pdf->Multicell(0,12,'Please find the following salary details' ,0,1);
            
            $pdf->Multicell(0,8,'Month : '. $month ,0,1);
            $pdf->Multicell(0,8,'Year : '. $year ,0,1);
            $pdf->Multicell(0,8,'Salary : '. $row['salary'] ,0,1);
            $pdf->Ln(); 

            $pdf->Image('sign1.jpg',140,120,30);

            $pdf->Output();

        }else{
            header('Location: view_salary?nodata');
        }
    }else{
        header('Location: view_salary?nodata');
    }

}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Salary</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./assets/css/css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/jpg" href="./assets/images/logo.png" />
    <link rel="stylesheet" href="./assets/css/css/index.css">
    <link rel="stylesheet" href="./assets/css/css/account.css">
    <script src="./assets/jquery-3.6.0.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    <script src="./css/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container-fluid">
        <?php
require './account_sidebar.php';
?><br><br>
        <div class="row cardBox">
            <div class="col-12">

        <?php
            if(isset($_GET['nodata'])){
        ?>
                <center>
                    <div class="alert-warning">
                        No payslips found
                    </div>
                </center>
        <?php
            }
        ?>

                <center>
                    <h4>View Salary</h4>
                </center><br><br>

                <div style='display:flex; justify-content: space-around'>
                    <form method='post'>
                        <input type="month" name='month' required>
                        <input type="submit" name='submit' value='Generate Report' class='btn btn-outline-primary'>
                    </form>
                </div>

            </div>
        </div>

    </div>

    </div>

</body>


</html>