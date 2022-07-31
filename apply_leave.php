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
 $date_in = date("Y-m-d");

// if(isset($_POST['punch_in'])){
//     $time_in = date('h:i:s');
//     $date_i = 0;
//     $att = "SELECT attend_date from tbl_attendence where attendence_reg_id='$id'";
//     $re =mysqli_query($conn,$att); 
//     $row = mysqli_fetch_assoc($re);
//     if($row!=null){
//         $date_i = $row['attend_date'];
//     }

//     if($date_i != $date_in){
//         $sql = "INSERT INTO tbl_attendence(attendence_reg_id, time_in, attend_date, time_out, full_half_day) VALUES ('$id', '$time_in', '$date_in', '00:00:00', 'half_day')";
//         $result = mysqli_query($conn, $sql);
//         if($result){
//             $msg = encrypt('punchedin');
//             header('Location: attendence?success='.$msg); 
//         }
//     }

// }

// ///////////Punch out/////
// if(isset($_POST['punch_out'])){
//     $date_i = 0;
//     $att = "SELECT attend_date from tbl_attendence where attendence_reg_id='$id' order by attendence_id desc";
//     $re =mysqli_query($conn,$att); 
//     $row = mysqli_fetch_assoc($re);
//     if($row!=null){
//         $date_i = $row['attend_date'];
//     }
//     $time_out = date('h:i:s');
  
//     if($date_i == $date_in){
//         $sql = "UPDATE tbl_attendence SET time_out ='$time_out', full_half_day='full_day' where attendence_reg_id='$id' order by attendence_id desc limit 1";
//         $result = mysqli_query($conn, $sql);
//         if(mysqli_query($conn,$sql)){
//             $msg = encrypt('punchedout');
//             header('Location: attendence?success='.$msg); 
//         }
//     }

// }




$leave_sql = "select * from tbl_leave where leave_regid='$id'";
$leave_result = mysqli_query($conn, $leave_sql);
$leave_row = mysqli_fetch_assoc($leave_result);
$el = $leave_row['earned_leave'];
$cl = $leave_row['casual_leave'];
$sl = $leave_row['sick_leave'];
$hl = $leave_row['hospital_leave'];
$ml = $leave_row['maternity_leave'];



if(isset($_POST['apply_for_leave'])){
$applied_date = date("Y-m-d");
$leave_type = mysqli_real_escape_string($conn, $_POST['leave_type']);
$from_date = mysqli_real_escape_string($conn, $_POST['from_date']);
$to_date = mysqli_real_escape_string($conn, $_POST['to_date']);
$leave_reason = mysqli_real_escape_string($conn, $_POST['reason']);    

$leave_to_be_deducted = 'NULL';
$diff = strtotime($from_date) - strtotime($to_date);
$no_of_days = abs(round($diff / 86400));
$deduction = 0;

if($leave_type=='el'){
$leave_to_be_deducted = 'earned_leave';
   }elseif($leave_type=='cl'){
$leave_to_be_deducted = 'casual_leave';
   }elseif($leave_type=='sl'){
$leave_to_be_deducted = 'sick_leave';
   }elseif($leave_type=='hl'){
$leave_to_be_deducted = 'hospital_leave';
  }


$leave_data = "select * from tbl_leave where leave_regid='$id'";
$leave_result = mysqli_query($conn, $leave_data);
$leave_row = mysqli_fetch_assoc($leave_result);
$leave_balance = $leave_row[$leave_to_be_deducted];

    $no_of_days = $leave_balance-$no_of_days;
    if($no_of_days>0){
        $leave_remaining = ($leave_balance - $no_of_days)+1;

        $leave_deduct = "update tbl_leave set $leave_to_be_deducted='$leave_remaining' where leave_regid='$id'";
        mysqli_query($conn, $leave_deduct);


        $applied_leave = "INSERT INTO tbl_applied_leaves(applied_leaves_reg_id, leave_type, applied_date, from_date, to_date, leave_reason, leave_status) VALUES 
        ('$id', '$leave_type', '$applied_date', '$from_date', '$to_date', '$leave_reason', '0')"; 
        $result = mysqli_query($conn, $applied_leave);
        if($result){
            $msg = 'success';

            header('Location: apply_leave?success='.$msg); 
        }else{
            echo '';
        }
    }else{
        $msg = 'notapplied';
        header('Location: apply_leave?success='.$msg); 
    }
}

if(isset($_POST['report_generate'])){

    $report_month = $_POST['report_month'];
    $month = substr($report_month, -2);
    $year = (int)substr($report_month, 0, 4);
    $days_in_month = [30,28,31,30,31,30,31,31,30,31,30,31];
    $days = $days_in_month[(int)$month];


    include('pdf_mc_table.php');
    $pdf = new PDF_MC_TABLE();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',15);
    // $pdf->Image('logo1.jpg',50,50,12);  
    $pdf->Cell(176, 5, 'Employment Exchange', 0, 0, 'C');
    $pdf->Image('indialogo.jpg',10,10,12);
   
    
// $pdf->Image('http://chart.googleapis.com/chart?cht=p3&chd=t:60,40&chs=250x100&chl=Hello|World',60,30,90,0,'PNG');

    $pdf->Ln();
    $pdf->Ln();
    $pdf->Ln(); 

    $from = date($year.'-'.$month.'-01');
    $to = date($year.'-'.$month.'-'.$days);
    $sql = "SELECT leave_type, applied_date, from_date, to_date, leave_reason, leave_status FROM tbl_applied_leaves WHERE 
    applied_leaves_reg_id='$id' and (from_date>='$from' and to_date<='$to')";
    $result=mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)){
        $pdf->SetFont('Arial','',12); 
        $pdf->Multicell(0,12,'Leave Type : '. $row['leave_type'] ,0,1);
        $pdf->Multicell(0,12,'Applied Date : '. $row['applied_date'],0,1);
        $pdf->Multicell(0,12,'From Date : '. $row['from_date'],0,1);
        $pdf->Multicell(0,12,'To Date : '. $row['to_date'],0,1);
        $pdf->Multicell(0,12,'Leave Reason : '. $row['leave_reason'],0,1);
        $pdf->Multicell(0,12,'Status : '. $row['leave_status'],0,1);
        $pdf->Ln();
    }
    // $pdf->Image('logo1.jpg',10,10,12);
    $pdf->Output();

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

}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Apply for Leave</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./assets/css/css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/jpg" href="./assets/images/logo.png" />
    <link rel="stylesheet" href="./assets/css/css/index.css">
    <link rel="stylesheet" href="./assets/css/css/account.css">
    <script src="./assets/jquery-3.6.0.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

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
        if(isset($_GET['success'])){
        $msg = $_GET['success'];
        if($msg == 'success'){
    ?>
        <center><h5 class='alert-success' style='padding: 10px'> Successfully applied for leave</h5></center>  
    <?php

        }elseif($msg == 'notapplied'){
    ?>
        <center><h5 class='alert-success' style='padding: 10px'>Not enough leave to be taken</h5></center>  
    <?php
        }
    }
    ?>

                
 <center><h4>Your Leave Balance</h4></center>

     <center>
        <div class="leave_table">
                <table>
                        <tr class="head_back">
                            <th>Leaves</th>
                            <th>Total Leaves</th>
                            <th>Used Leaves</th>
                            <th>Remaining Leaves</th>
                        </tr>
                        <tr>
                            <th class="head_back">Earned Leaves</th>
                            <th class="data_back"><center>30</center></th>
                            <th class="data_back"><center><?php echo 30-$el ?></center></th>
                            <th class="data_back"><center><?php echo $el ?></center></th>
                        </tr>
                        <tr>
                            <th class="head_back">Casual Leaves</th>
                            <th class="data_back"><center>14</center></th>
                            <th class="data_back"><center><?php echo 14-$cl ?></center></th>
                            <th class="data_back"><center><?php echo $cl ?></center></th>
                        </tr>
                        <tr>
                            <th class="head_back">Sick Leaves</th>
                            <th class="data_back"><center>90</center></th>
                            <th class="data_back"><center><?php echo 90-$sl ?></center></th>
                            <th class="data_back"><center><?php echo $sl ?></center></th>
                        </tr>
                        <tr>
                            <th class="head_back">Hospital Leaves</th>
                            <th class="data_back"><center>90</center></th>
                            <th class="data_back"><center><?php echo 90-$hl ?></center></th>
                            <th class="data_back"><center><?php echo $hl ?></center></th>
                        </tr>
                        <?php
                            if($ml!=NULL){
                        ?>
                            <tr>
                                <th class="head_back">Maternity Leaves</th>
                                <th class="data_back"><center>180</center></th>
                                <th class="data_back"><center><?php echo 180-$ml ?></center></th>
                                <th class="data_back"><center><?php echo $ml ?></center></th>
                            </tr>
<?php
                            
 }
?>

                    </table>
</div><br><br>

<!-- modal pop ups when apply for leave button is clicked -->

<button type="submit" name='apply_for_leave' class='btn btn-outline-success' data-bs-toggle="modal" data-bs-target="#staticBackdrop"> Apply for Leave </button><br><br>
<div style='display:flex; justify-content: space-around'>
    <form method='post'>
        From: <input type="month" required name='report_month'>
        <input type="submit" name='report_generate' class="btn btn-outline-primary" value="Generate Report">
    </form>
</div>

                <!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Apply for leave</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
 </div>
<div class="modal-body">
    <form method="POST" id="form">
        <table>
        <tr>
        <td style="padding:5px 80px 5px 5px">Select leave Type</td>
        <td>
        <select name="leave_type" id="leave_type" class="leave_type_dropdown">
            <option value="NULL">Please select a Leave Type</option>
            <option value="el">Earned Leave</option>
            <option value="cl">Casual Leave</option>
            <option value="sl">Sick Leave</option>
            <option value="hl">Hospital Leave</option>
            <option value="ml">Maternity Leave</option>
        </select>
        <p id="leaveTypeError" style="display: none; padding: 0; margin:0;color: red;">*Leave Type should not be empty</p>
            </td>
        </tr>
         <tr>
        <td style="padding:5px 80px 5px 5px">From</td>
                                    <!-- <td><input name="from_date" id="from_date" type="date" min="2020-01-02" required></td> -->
        <td>
        <?php
        $leave_date = date('Y-m-d', strtotime($date_in . ' +1 day'));
        echo "<input name='from_date' id='from_date' type='date' min='$leave_date' required>";
        ?>  
        </td>
        </tr>
        <tr>
        <td style="padding:5px 80px 5px 5px">To</td>
        <td>
        <?php
            echo "<input name='to_date' id='to_date' type='date' min='$leave_date' required>";
        ?>
        </td>
        </tr>
        <tr>
        <td style="padding:5px 80px 5px 5px">Reason</td>
        <td><input  name="reason" id="reason" type="text" placeholder="Reason for Leave" required></td>
        </tr>
                                
        </table><br><br>
        <center><input type="submit" class="btn btn-outline-success" value="Apply Leave" name="apply_for_leave"></center>
                            
        </form>
        </div>
                       
        </div>
        </div>
        </div>

        </center>

        </div>
    </div>

    </div>

    </div>

</body>


</html>