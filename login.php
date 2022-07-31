<?php

require_once './include/connect.php';
require './encdec.php';

session_start();

if(isset($_GET['error'])){
  $sSalt = '20adeb83e85f03cfc84d0fb7e5f4d290';
  $sSalt = substr(hash('sha256', $sSalt, true), 0, 32);
  $method = 'aes-256-cbc';
  $password = $_GET['error'];
  $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

  $decrypt = openssl_decrypt(base64_decode($password), $method, $sSalt, OPENSSL_RAW_DATA, $iv);
  $error = $decrypt;
}

if(isset($_POST['login'])){
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);
  
  $sql = "SELECT * FROM tbl_login where emailid='$email'";
  $result = mysqli_query($conn, $sql);

  if($row = mysqli_fetch_assoc($result)){

    // For admin
    if($row['usertype']==4){
      $hash = password_verify($password, $row['password']);
      if($hash == true){
        $_SESSION['superuser'] = $email;
        header('Location: superuser/dashboard');
        exit();
      }elseif($hash == false){
        $status = encrypt($userType);
        $error = encrypt("Error");
        header("Location: login?error=$error");
        exit();
      }
    }

    // For normal users
    $active = $row['status'];
    if($active==1){
      $hash = password_verify($password, $row['password']);
      if($hash == false){
        $status = encrypt($userType);
        $error = encrypt("Error");
        header("Location: login?error=$error");
        exit();
      }
      else if($hash == true){
        $_SESSION['regid'] = $row['login_id'];
        $_SESSION['usertype'] = $row['usertype'];
        $_SESSION['verified'] = $row['verified'];
        header("Location: ./index");
        exit();
      }
    }else{
      echo "<script>";
        echo "alert('Your account is deactivated, Kindly check with Admin to reactivate')";
      echo "</script>";
      header('Location: ./index');
      exit();
    }
      
  }

}

?>


<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Page</title>
  <link rel="stylesheet" href="./assets/css/css/bootstrap.min.css">
  <link rel="stylesheet" href="./assets/css/css/login.css">
  <script src="./assets/jquery-3.6.0.js"></script>
  <link rel="shortcut icon" type="image/jpg" href="./assets/images/logo.png"/>
</head>

<body>

  <div class="container">
    <div class="row justify-content-md-center" style="margin-top: 10%;">
      <div id="col1" class="col-2"></div>
      <div id="col2" class="col-8 col-md-4 login-main-div"><br><br>
        <center><b class="login-head">Login</b></center><br>
        <?php
          if(isset($_GET['error'])){
            if ($error=="Error"){
              $message = $_GET['error'];
              $message = "Invalid username or password";
        ?>
         <center><div id="alert" class="alert-warning"><?php echo $message ?></div></center><br>
        <?php
            }
          }
        ?>
        <form method="POST" action="" style="width: 100%;">
          <div class="container">
            <input type="email" name="email" class="inp px-3" placeholder="Email id" ><br>
            <input type="password" name="password" class="inp px-3" placeholder="Password" ><br><br>
            <center><input type="submit" name="login" value="Login" class="btn btn-success" id="register"></center><br>
          </div>
          <div class="form-row py-4">
            <div class="offset-1 col-lg-10">
              <p  style="color: white;">Dont't have account? &nbsp; <a href="./register?tag=<?php echo encrypt("0") ?>" style="color: lightblue;">Sign up</a></p>
              <p  style="color: white;">For company please use &nbsp; <a href="./register?tag=<?php echo encrypt("1") ?>" style="color: lightblue;">Sign up</a></p>
            </div>
          </div>
        </form>
      </div>
      <div id="col3" class="col-2"></div>
    </div>
  </div>
</body>

<script>
  $(document).ready(function () {

    if ($(window).width() < 991) {
      $("#col1").attr('class', 'col-1');
      $("#col2").attr('class', 'col-10');
      $("#col3").attr('class', 'col-1');
      $("#col2").addClass('login-main-div');
    }
  });
</script>

</html>