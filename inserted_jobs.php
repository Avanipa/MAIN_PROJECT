<?php

require_once('./include/connect.php');
session_start();
require './encdec.php';

if(!isset($_SESSION['regid'])){
    header('Location: index');
    exit();
}

$id = $_SESSION['regid'];

$jobs_id = [];
$statuses = [];
$dates = [];
$comments = [];
$i = 0;

$sql = "select * from tbl_jobs where company_id='$id'";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)){
    $jobs_id[$i] = $row['jobs_id'];
    $statuses[$i] = $row['status'];
    $dates[$i] = $row['submitted_on'];
    $comments[$i] = $row['rejection_reason'];
    $i = $i+1;
}

if(isset($_POST['re_submit'])){
    $reject_id = mysqli_real_escape_string($conn, $_POST['rejection_id']);
    $comment = "Service has been resubmitted";
    $approval = "update tbl_jobs set status=1, rejection_reason='$comment' where jobs_id='$reject_id'";
    if(mysqli_query($conn, $approval)){
        echo "<script>";
            echo "alert('Job has been re submitted')";
        echo "</script>";
    }else{
        mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>My Account Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./assets/css/css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/jpg" href="./assets/images/logo.png" />
    <link rel="stylesheet" href="./assets/css/css/index.css">
    <link rel="stylesheet" href="./assets/css/css/account.css">
    <script src="./assets/jquery-3.6.0.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">

    <script src="./css/jquery-3.6.0.min.js"></script>
</head>

<body>

    <div class="container-fluid">
        <?php
        require './account_sidebar.php';
        ?><br><br>
        <div class="row cardBox">
            <div class="col-12">
                
                <center>
                    <table class="account_table">
                        <tr style="background-color: antiquewhite">
                            <th style="width: 400px; text-align: left;padding: 10px;">Inserted Jobs</th>
                            <th style="width: 350px; text-align: left;padding: 10px;">Comments</th>
                            <th style="width: 130px; text-align: left;padding: 10px;">Dates</th>
                            <th style="width: 50px; text-align: left;padding: 10px;">Status</th>
                        </tr>
                        <?php
                            for($j=0; $j<count($jobs_id); $j++){
                                echo "<tr style='box-shadow: 0px 5px 8px 1px black'>";
                                    echo "<td>";
                                        $sql = "select * from tbl_jobs where jobs_id='$jobs_id[$j]'";
                                        $result = mysqli_query($conn, $sql);
                                        while($row = mysqli_fetch_assoc($result)){
                                            echo $row['job_name'];
                                        }
                                        $status = $statuses[$j];
                                        if($status==0){
                                            echo "<h5 style='font-size:12px; color:red'>"."Please re submit after rectifying the error"."</h5>";
                                            echo "<form method='POST'>";
                                                echo "<input type='hidden' name='rejection_id' value='$jobs_id[$j]'/>"; 
                                                echo "<input type='submit' name='re_submit' value='Re-Submit' class='btn btn-outline-primary'>";
                                            echo "</form>";    
                                        }
                                    echo "<td>";
                                        $comment = $comments[$j];
                                        if($comment=='NULL'){
                                            echo "No comments yet!";
                                        }else{
                                            echo $comment;
                                        }
                                    echo "</td>";
                                    echo "<td>";

                                        echo $dates[$j];

                                    echo "</td>";
                                    echo "<td>";

                                        $status = $statuses[$j];
                                        if($status==1){
                                            echo "<button class='btn btn-warning'>"."Pending"."</button>";
                                        }else if($status==0){
                                            echo "<button class='btn btn-danger'>"."Rejected"."</button>";
                                        }else if($status==2){
                                            echo "<button class='btn btn-success'>"."Approved"."</button>";
                                        }else if($status==100){
                                            echo "<button class='btn btn-danger'>"."First Approval Rejected"."</button>";
                                        }else if($status==01){
                                            echo "<button class='btn btn-danger'>"."Second Approval Rejected"."</button>";
                                        }else if($status==10){
                                            echo "<button class='btn btn-success'>"."First Level Approved"."</button>";
                                        }else if($status==11){
                                            echo "<button class='btn btn-success'>"."Second Level Approved"."</button>";
                                        }
                                        
                                    echo "</td>";
                                echo "</tr>";
                            }
                        ?>

                    </table>
                </center>

            </div>
        </div>
    </div>

    </div>

    </div>

</body>

</html>

