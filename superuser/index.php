<?php
require_once '../include/connect.php';
session_start();

if(isset($_POST['submit'])){
$uname = mysqli_real_escape_string($conn, $_POST['username']);
$pass = mysqli_real_escape_string($conn, $_POST['password']);
        
$sql = "SELECT * FROM tbl_admin WHERE username='$uname'";
$result = mysqli_query($conn, $sql);
        
if($row = mysqli_fetch_assoc($result)){
    $hash = password_verify($pass, $row['password']);
        if($hash==false){
            header('Location: ./index.php?invaid_password');
            exit();
            }else if($hash==true){
                $_SESSION['superuser'] = $_POST['username'];
                header('Location: ./dashboard.php');
                exit();
            }
            }else{
            header('Location: ./index.php?invalid_email');
            exit();
        }

    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./css/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/login.css">
    <script src="./css/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <script src="./css/jquery-3.6.0.min.js"></script>
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-3"></div>
        <div class="col-6 login-main-col">
        <div class="card">
            <p class="card-text text-center">Login to Admin</p><br>
            <?php
            if(isset($_GET['invaid_password'])){
            $message = $_GET['invaid_password'];
            $message = "Invalid password";
            ?>
<div class="alert-danger alert text-center"> <?php echo $message ?> </div>
    <?php
        }
    ?>

            <?php
                if(isset($_GET['invalid_email'])){
                $message = $_GET['invalid_email'];
                $message = "Invalid email";     
            ?>

<div class="alert-danger alert text-center"> <?php echo $message ?> </div>
    <?php
        }
    ?>
<form action="" method="POST">
<input class="login-inputs" type="text" name="username" placeholder="Enter Username"><br>
<input class="login-inputs" type="password" name="password" placeholder="Enter Password"><br><br>
<input style="width: 96%; margin:2%" type="submit" class="btn btn-primary" name="submit" value="Submit">
</form>
</div>
</div>
<div class="col-3"></div>
</div>
         </div>
</body>

</html>

