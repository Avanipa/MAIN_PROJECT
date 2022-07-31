<?php

    require_once '../include/connect.php';
    require './encdec.php';
    require '../mail.php';

    session_start();

    if(!isset($_SESSION['superuser'])){
        header('Location: ./index.php');
        exit();
    }

    $select_names = "SELECT R.regid, R.uname, L.emailid, L.usertype from tbl_registration R, tbl_login L where R.regid=L.login_id";
    $select_result = mysqli_query($conn, $select_names);
    $salary_to_be_paid_staff = [];
    $salary_to_be_paid_officer = [];
    $staff = 0;
    $officer = 0;
    while($row = mysqli_fetch_assoc($select_result)){
        if($row['usertype']==2){
            $staff_ones = [];
            $staff_ones[0] = $row['regid'];
            $staff_ones[1] = $row['emailid'];
            $staff_ones[2] = $row['uname'];
            $staff_ones[3] = $row['usertype'];
            $salary_to_be_paid_staff[$staff] = $staff_ones;
            $staff++;
        }elseif($row['usertype'] == 3){
            $officer_ones = [];
            $officer_ones[0] = $row['regid'];
            $officer_ones[1] = $row['emailid'];
            $officer_ones[2] = $row['uname'];
            $officer_ones[3] = $row['usertype'];
            $salary_to_be_paid_officer[$officer] = $officer_ones;
            $officer++;
        }
    }

    // print_r($salary_to_be_paid_staff);

    if(isset($_POST['pay_staff'])){
        
        $days_of_months = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        $month = date('m');
        // (int)$month changes $month of type date to integer
        $days = $days_of_months[(int)$month];

        $slary_check = "select * from tbl_salary where salary_usertype=2 order by salary_id desc limit 1";
        $salary_check_result = mysqli_query($conn, $slary_check);
        $salary_check_row = mysqli_fetch_assoc($salary_check_result);
        $salary_check_month = $salary_check_row['month'];

        $salary_month = date('M');

        if($salary_check_month!=$salary_month){
            for($i=0; $i<sizeof($salary_to_be_paid_staff); $i++){

                $salary_regid = $salary_to_be_paid_staff[$i][0];
    
                $salary_sql = "select * from tbl_attendence where attendence_reg_id='$salary_regid' && full_half_day='leave'";
                $salary_result = mysqli_query($conn, $salary_sql);
    
                $counter = 0;
    
                while($salary_row = mysqli_fetch_assoc($salary_result)){
                    $counter++;
                }
                
                $salary = 0;
    
                // echo $usertype;
    
                $salary = ($days - $counter) * 1000;
    
                $year = date('Y');
                $month = date('M');
    
                $user_type = $salary_to_be_paid_staff[$i][3];
    
                $salary_insert_sql = "insert into tbl_salary(salary_regid, month, year, salary, salary_usertype) values 
                ('$salary_regid', '$month', '$year', '$salary', '$user_type')";
                $salary_insert_result = mysqli_query($conn, $salary_insert_sql);
                if($salary_insert_result){
                    header('Location: ./pay_salary?success');
                }else{
                    echo mysqli_error($conn);
                }
    
                // mail to be sent
                $month_mail = date('M');
                $year_mail = date('Y');
                $subject = "Salary credited for ".$month_mail;
                $message = 'Hi '.$salary_to_be_paid_staff[$i][2].'<br><br>Your Salary has been credited'.'<br><br> Salary: '.$salary.'<br><br>'.'Month: '.$month_mail.'<br><br>'.'Year: '.$year_mail;
                send_mail($salary_to_be_paid_staff[$i][1], $subject, $message);
    
            }
        }else{
            header('Location: ./pay_salary?already');
        }

    }

    if(isset($_POST['pay_officer'])){
        
        $days_of_months = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        $month = date('m');
        // (int)$month changes $month of type date to integer
        $days = $days_of_months[(int)$month];

        $slary_check = "select * from tbl_salary where salary_usertype=3 order by salary_id desc limit 1";
        $salary_check_result = mysqli_query($conn, $slary_check);
        $salary_check_row = mysqli_fetch_assoc($salary_check_result);
        $salary_check_month = $salary_check_row['month'];

        $salary_month = date('M');

        if($salary_check_month!=$salary_month){
            for($i=0; $i<sizeof($salary_to_be_paid_officer); $i++){

                $salary_regid = $salary_to_be_paid_officer[$i][0];
    
                $salary_sql = "select * from tbl_attendence where attendence_reg_id='$salary_regid' && full_half_day='leave'";
                $salary_result = mysqli_query($conn, $salary_sql);
    
                $counter = 0;
    
                while($salary_row = mysqli_fetch_assoc($salary_result)){
                    $counter++;
                }
                
                $salary = 0;
    
                // echo $usertype;
    
                $salary = ($days - $counter) * 1500;
    
                $year = date('Y');
                $month = date('M');
    
                $user_type = $salary_to_be_paid_officer[$i][3];
    
                $salary_insert_sql = "insert into tbl_salary(salary_regid, month, year, salary, salary_usertype) values 
                ('$salary_regid', '$month', '$year', '$salary', '$user_type')";
                $salary_insert_result = mysqli_query($conn, $salary_insert_sql);
                if($salary_insert_result){
                    header('Location: ./pay_salary?success');
                }else{
                    echo mysqli_error($conn);
                }
    
                // mail to be sent
                $month_mail = date('M');
                $year_mail = date('Y');
                $subject = "Salary credited for ".$month_mail;
                $message = 'Hi '.$salary_to_be_paid_officer[$i][2].'<br><br>Your Salary has been credited'.'<br><br> Salary: '.$salary.'<br><br>'.'Month: '.$month_mail.'<br><br>'.'Year: '.$year_mail;
                send_mail($salary_to_be_paid_officer[$i][1], $subject, $message);
    
            }
        }else{
            header('Location: ./pay_salary?already');
        }

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Salary</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="./css/css/bootstrap.min.css">
    <script src="./css/js/bootstrap.min.js"></script>
    <!-- Online scripts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <script src="./css/jquery-3.6.0.min.js"></script>
    <script src="./css/index.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>

<body>

<div class="container-fluid">
    <?php
        require './sidebar.php';
    ?>
    <style>
        ul{
            list-style: none
        }
    </style>
    <div class="details">

        <div class="update-top-bar">
            <div class="cardHeader">
                <h2>Salary</h2>
            </div>
            
            <?php
                if(isset($_GET['success'])){
            ?>
                    <div class="alert-success" style="padding: 10px">
                        <center><h5>Salary credited successfully</h5></center>
                    </div> <br>
            <?php
                }elseif(isset($_GET['already'])){
            ?>
                    <div class="alert-primary" style="padding: 10px">
                        <center><h5>This months salary has already been credited to all the employees</h5></center>
                    </div> <br>
            <?php
                }
            ?>

            <div class="container-fluid">
            
                <div class="row">
                    <div class="col-6">
                        <ul>
                            <li style='background: #CEE5D0; padding: 10px'>Staff</li>
                        
                            <?php
                                for($i=0; $i<sizeof($salary_to_be_paid_staff); $i++){
                                    echo "<li style='padding: 15px 10px 10px 10px'>".$salary_to_be_paid_staff[$i][2]."</li>";
                                }
                            ?>

                            <form method='POST'>
                                <center><input type="submit" name='pay_staff' value='Pay Salary' class='btn btn-outline-primary'></center>
                            </form>
                        
                        </ul>
                    </div>
                    <div class="col-6">
                        <ul>
                            <li style='background: #CEE5D0; padding: 10px'>Officer</li>
                        
                            <?php
                                for($i=0; $i<sizeof($salary_to_be_paid_officer); $i++){
                                    echo "<li style='padding: 15px 10px 10px 10px'>".$salary_to_be_paid_officer[$i][2]."</li>";
                                }
                            ?>

                            <form method='POST'>
                                <center><input type="submit" name='pay_officer' value='Pay Salary' class='btn btn-outline-primary'></center>
                            </form>

                        </ul>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

</body>

</html>