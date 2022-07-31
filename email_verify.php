<?php

require_once('./include/connect.php');
session_start();
require './encdec.php';
require 'mail.php';

if(!isset($_SESSION['regid'])){
    header('Location: index');
    exit();
}

$regid = $_SESSION['regid'];

if(isset($_POST['verify_email'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $sql_login = "select emailid from tbl_login where login_id='$regid'";
    $result_login = mysqli_query($conn, $sql_login);
    $row_login = mysqli_fetch_assoc($result_login);

    $sql_registration = "select uname from tbl_registration where regid='$regid'";
    $result_registration = mysqli_query($conn, $sql_registration);
    $row_registration = mysqli_fetch_assoc($result_registration);

    $name = $row_registration['uname'];
    
if($email == $row_login['emailid']){
    $otp = random_int(100000, 999999);
    $_SESSION['otp'] = $otp;
        
    $recipient = $email;
    $message = 'Hi ' . $name . ',' . '<br><br> Below is the one time password for your email verification in Employment Exchange<br>'.$otp;

    $subject = 'OTP for Employment Exchange email verification';

    send_mail($recipient, $subject, $message);
        // $alert_message = "Credentials have been sent to " .$name. "'s email address";

    header('Location: ./email_verify?ver');
    }else{
        header('Location: ./email_verify?err');
    }
}

if(isset($_POST['verify_otp'])){
    $otp = mysqli_real_escape_string($conn, $_POST['otp']);
    if($otp==$_SESSION['otp']){
        $sql = "update tbl_login set verified=1 where login_id='$regid'";
        $result = mysqli_query($conn, $sql);
        $_SESSION['verified'] = 1;
        unset($_SESSION['otp']);
        header('Location: ./email_verify');
    }else{
        header('Location: ./email_verify?er');
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Verify Email</title>
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

            <?php
                if(isset($_GET['err'])){
            ?>
                <center>
                    <div class="alert alert-danger" role="alert">
                        Please enter your email ID which you used during your registration
                    </div>
                </center>
            <?php 
                }elseif(isset($_GET['ver'])){
            ?>
                <center>
                    <div class="alert alert-success" role="alert">
                        An OTP has been sent to your email address
                    </div>
                </center>
            <?php
                }elseif(isset($_GET['er'])){
            ?>
                <center>
                    <div class="alert alert-danger" role="alert">
                        Please enter the correct OTP
                    </div>
                </center>
            <?php
                }
            ?>

            <?php
                if($_SESSION['verified'] == 0){
            ?>

                <center>
                    <h4>Verify Your Email</h4>

                    <form method="POST">
                        <input type="email" name="email" style="width:60%" placeholder="Enter your email" required><br>
                        <input type="submit" name="verify_email" value="Send OTP" class="btn btn-outline-success">
                    </form>
                        <br><br><br>
                    <form method="POST">
                        <input type="number" name="otp" placeholder="Enter your OTP" style="width: 30%" required>
                        <input type="submit" name="verify_otp" value="Verify OTP" class="btn btn-outline-success">
                    </form>

                </center>

            <?php
                }elseif($_SESSION['verified'] == 1){
            ?>
                <center>
                    <h4>Your email has been verified</h4>
                
                </center>
            <?php
                }
            ?>



            </div>
        </div>
    </div>

    </div>

    </div>

</body>

</html>

