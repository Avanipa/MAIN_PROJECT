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

// For displaying names of staffs and officers
$display_sql = "select R.regid, R.uname from tbl_registration R join tbl_login L where (L.usertype=2 or L.usertype=3) AND L.login_id=R.regid";
$display_result = mysqli_query($conn, $display_sql);

// Attendence checker function return attend date of each requested id's
function attendenceChecker($conn, $attendence_checker_id){
    $attendence_checker_sql = "select * from tbl_attendence where attendence_reg_id='$attendence_checker_id' order by attendence_id desc limit 1";
    $attendence_checker_result = mysqli_query($conn, $attendence_checker_sql);
    $attendence_checker_row = mysqli_fetch_assoc($attendence_checker_result);
    $today = date('Y-m-d');
    if($attendence_checker_row){
        if($attendence_checker_row['attend_date'] == $today){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

// On pressing Present button
if(isset($_POST['present'])){
    $attendance_id = $_POST['regid'];
    // echo $attendance_id;
    $day = date('Y-m-d');
    $attendance_sql = "insert into tbl_attendence(attendence_reg_id, time_in, attend_date, time_out, full_half_day) values
    ('$attendance_id', '09:00:00', '$day', '17:00:00', 'full_day')";
    $attendence_result = mysqli_query($conn, $attendance_sql);

    if($attendence_result){
        header('Location: attendence?success_present');
    }else{
        echo mysqli_error($conn);
    }
}

// On pressing Absent button
if(isset($_POST['absent'])){
    $attendance_id = $_POST['regid'];
    // echo $attendance_id;
    $day = date('Y-m-d');
    $attendance_sql = "insert into tbl_attendence(attendence_reg_id, time_in, attend_date, time_out, full_half_day) values
    ('$attendance_id', '09:00:00', '$day', '17:00:00', 'leave')";
    $attendence_result = mysqli_query($conn, $attendance_sql);

    if($attendence_result){
        header('Location: attendence?success_absent');
    }else{
        echo mysqli_error($conn);
    }
}

?>
<?php



?>



<!DOCTYPE html>
<html lang="en">

<head>
    <title>Mark Attendence</title>
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
               
            
<center><h4>Mark Attendence</h4></center>

 <?php
     if(isset($_GET['success_present'])){
 ?>
<center><div class="alert-success">Present marked</div></center>
 <?php

 }elseif(isset($_GET['success_absent'])){
 ?>
 <center><div class="alert-primary">Absent marked</div></center>
 <?php
     }
 ?>

 <center>
    <div class="leave_table">
         <form action="" method="post">
<table>
    <tr class="head_back">
    <th>SI.No</th>
    <th>Name</th>
    <th>Present</th>
    <th>Absent</th>
</tr>
    <tr>
<?php
    $i = 1;
    while($row=mysqli_fetch_assoc($display_result)){
    $attendence_checker = attendenceChecker($conn, $row['regid']);
    if($attendence_checker==0){
?>
<tr>
    <form method="post" action="">
    <th class="head_back"><?php echo $i; ?></th>
    <th class="data_back"><center><?php echo $row['uname'] ?></center></th>
    <input type="hidden" name='regid' value=<?php echo $row['regid'] ?>>
    <th><input type="submit" class='btn btn-outline-primary' name='present' value='Present'></th>
    <th><input type="submit" class='btn btn-outline-danger' name='absent' value='Absent'></th>
</form>
    </tr>
<?php
    $i++;
}
    }
?>
</tr>                       

</table>
            
 </div><br><br>

                <!-- modal pop ups when apply for leave button is clicked
                <button type="submit" name='mark_attendence' class='btn btn-outline-success' data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    Submit
                </button> -->

         </form>
                
                 

        </div>
    </div>

    </div>

    </div>

</body>


</html>
