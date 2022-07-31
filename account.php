<?php

require_once('./include/connect.php');
session_start();
require './encdec.php';

if(!isset($_SESSION['regid'])){
    header('Location: index');
    exit();
}

$id = $_SESSION['regid'];
$status = $_SESSION['usertype'];

$login = "SELECT * FROM tbl_login where login_id=$id";
$login_result = mysqli_query($conn, $login);
while($row = mysqli_fetch_assoc($login_result)){
    $emailid = $row['emailid'];
}

$sql = "select * from tbl_registration where regid=$id";
$result = mysqli_query($conn, $sql);

while($row=mysqli_fetch_assoc($result)){
    $regid = $row['regid'];
    $name = $row['uname'];
    $address = $row['uaddress'];
    $phone = $row['uphone'];
    $aadharno = $row['aadharno'];
    $company_registration = $row['company_registration'];
    $dob = $row['dob'];
    $place = $row['place'];
    $district = $row['district'];
    $localbody = $row['localbody'];
    $companyname = $row['companyname'];
}

if($status==0){
    $userDetails = "select * from tbl_user_details where user_reg_id='$id'";
    $userDetailsresult = mysqli_query($conn, $userDetails);
    $user_details_row = mysqli_fetch_assoc($userDetailsresult);

    if($user_details_row['marital_status']==1){
        $marry = "Married";
    }elseif($user_details_row['marital_status']==2){
        $marry = "Unmarried";
    }elseif($user_details_row['marital_status']==3){
        $marry = "Widow";
    }elseif($user_details_row['marital_status']==0){
        $marry = "NULL";
    }
}

if(isset($_POST['getFile'])){
    $filename = $_FILES["image_dp"]["name"];

    $tempname = $_FILES["image_dp"]["tmp_name"];  
    echo $filename;
    echo $tempname;
}

if(isset($_POST['reset_password'])){
    $old_password = mysqli_real_escape_string($conn, $_POST['old_password']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $change_password_sql = "select * from tbl_login where login_id='$id'";
    $change_password_result = mysqli_query($conn, $change_password_sql);
    $change_passsword_row = mysqli_fetch_assoc($change_password_result);

    $correct_password = password_verify($old_password, $change_passsword_row['password']);

    if($correct_password==true){
        $reset_password = "update tbl_login set password='$hash' where login_id='$id'";
        if(mysqli_query($conn, $reset_password)){
            header('Location: account?success');
        }
    }elseif($correct_password==false){
        header('Location: account?incorrect_password');
    }
    
}

if(isset($_POST['dp_submit'])){
    $target_dir_dp = "superuser/assets/documents/".$id."/dp/";

    if(!is_dir("superuser/assets/documents/".$id)){
        mkdir("superuser/assets/documents/".$id); 
        if(!is_dir("superuser/assets/documents/".$id."/dp")){
            mkdir("superuser/assets/documents/".$id."/dp");
        }
    }else{
        if(!is_dir("superuser/assets/documents/".$id."/dp")){
            mkdir("superuser/assets/documents/".$id."/dp");
        }
    }

    $target = $target_dir_dp . basename($_FILES['dp']['name']);
    $image = $_FILES['dp']['name'];

    $allowed_image_extension = array(
        "png",
        "jpg",
        "jpeg"
    );

    $file_extension = pathinfo($_FILES["dp"]["name"], PATHINFO_EXTENSION);
    if(! in_array($file_extension, $allowed_image_extension)){
        echo "<script>";
            echo "alert('Only images are allowed (Extensions: JPG, JPEG, PNG)')";
        echo "</script>";
    }else{
        $sql = "update tbl_user_details set display_picture='$image' where user_reg_id='$id'";
        if(mysqli_query($conn, $sql)){
            move_uploaded_file($_FILES['dp']['tmp_name'], $target);
            header('Location: ./account?successd');
        }
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script src="./css/jquery-3.6.0.min.js"></script>
</head>

<body>

    <div class="container-fluid">
        <?php
        require './account_sidebar.php';
        ?><br><br>
        <div class="row cardBox">
            <div class="col-2"></div>
            <div class="col-8">
            
            <?php
                if(isset($_GET['success'])){
                    $message = $_GET['success'];
                    $message = "Password has been reset successfully";
                ?>
                    <center><div id="alert" style="padding:10px" class="alert-warning"><?php echo $message ?></div></center><br>
                <?php
                }elseif(isset($_GET['successd'])){
                    $message = $_GET['successd'];
                    $message = "Profile picture has been updated successfully";
                ?>
                    <center><div id="alert" style="padding:10px" class="alert-warning"><?php echo $message ?></div></center><br>
                <?php
                }elseif(isset($_GET['incorrect_password'])){
                ?>
                    <center><h5 class="alert-danger">Your old passord is incorrect</h5></center>
                <?php
                }
                
            ?>

                <center>
                    <?php
                        if($status==0){
                            $pic = "select display_picture from tbl_user_details where user_reg_id='$id'";
                            $pic_result = mysqli_query($conn, $pic);
                            $pic_row = mysqli_fetch_assoc($pic_result);
                            if($pic_row['display_picture']=='NULL'){
                                echo "<img src='./assets/icons/profile.png' class='account_image'>";
                            }else{
                                $path = "superuser/assets/documents/".$id."/dp/";
                                echo "<img src='$path" .$pic_row['display_picture']. " ' class='account_image'>";
                            }
                        }else{
                            echo "<img src='./assets/icons/profile.png' class='account_image'>";
                        }
                    ?>
                </center>
                
                <center>
                    
                    <table class="account_table">
                        <tr>
                            <td><b>Name</b></td>
                            <td> : </td>
                            <td>
                                <?php echo $name ?>
                            </td>
                            <!-- <td><i class="bi bi-pencil-square"></i></td> -->
                        </tr>
                        <?php
                        if($status==0){
                        ?>
                            <tr>
                                <td><b>Display Picture</b></td>
                                <td> : </td>
                                <td>
                                    <form action="" method="POST" enctype="multipart/form-data">
                                        <input type="file" name="dp" required>
                                        <input type="submit" class="btn btn-outline-success" name="dp_submit" value="Submit">
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <td><b>ID</b></td>
                                <td> : </td>
                                <td>
                                    <?php
                                        $appid = $regid*1000+(9876543210-1234567890);
                                    ?>
                                    <?php echo $appid ?>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                        <tr>
                            <td><b>Email</b></td>
                            <td> : </td>
                            <td>
                                <?php echo $emailid?>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Phone</b></td>
                            <td> : </td>
                            <td>
                                <?php echo $phone ?>
                            </td>
                            <!-- <td><i class="bi bi-pencil-square"></i></td> -->
                        </tr>

                        <?php
                            if($status==1){
                        ?>
                        <tr>
                            <td><b>Company Registration</b></td>
                            <td> : </td>
                            <td>
                                <?php echo $company_registration ?>
                            </td>
                        </tr>
                        <?php
                            }else{
                        ?>
                        <tr>
                            <td><b>Adhaar Number</b></td>
                            <td> : </td>
                            <td>
                                <?php echo $aadharno ?>
                            </td>
                        </tr>
                        <?php
                                if($status==0){
                            ?>
                        <tr>
                            <td><b>DOB</b></td>
                            <td> : </td>
                            <td>
                                <?php echo $dob ?>
                            </td>
                            <!-- <td><i class="bi bi-pencil-square"></i></td> -->
                        </tr>
                        <tr>
                            <td><b>Marital Status</b></td>
                            <td> : </td>
                            <td>
                                <?php echo $marry ?>
                            </td>
                            <td><a href="./mydetails.php?fo=<?php echo encrypt('#marital_status'); ?>"><i class="bi bi-pencil-square"></i></a></td>
                        </tr>
                        <tr>
                            <td><b>Caste</b></td>
                            <td> : </td>
                            <td>
                                <?php echo $user_details_row['caste'] ?>
                            </td>
                            <td><a href="./mydetails.php?fo=<?php echo encrypt('#caste'); ?>"><i class="bi bi-pencil-square"></i></a></td>
                        </tr>
                        <tr>
                            <td><b>Religion</b></td>
                            <td> : </td>
                            <td>
                                <?php echo $user_details_row['religion'] ?>
                            </td>
                            <td><a href="./mydetails.php?fo=<?php echo encrypt('#religion'); ?>"><i class="bi bi-pencil-square"></i></a></td>
                        </tr>
                        <tr>
                            <td><b>Place</b></td>
                            <td> : </td>
                            <td>
                                <?php echo $place ?>
                            </td>
                            <td><a href="./mydetails.php?fo=<?php echo encrypt('#place'); ?>"><i class="bi bi-pencil-square"></i></a></td>
                        </tr>
                        <tr>
                            <td><b>Ward</b></td>
                            <td> : </td>
                            <td>
                                <?php echo $user_details_row['ward'] ?>
                            </td>
                            <td><a href="./mydetails.php?fo=<?php echo encrypt('#ward'); ?>"><i class="bi bi-pencil-square"></i></a></td>
                        </tr>
                        <tr>
                            <td><b>Localbody</b></td>
                            <td> : </td>
                            <td>
                                <?php echo $localbody?>
                            </td>
                            <td><a href="./mydetails.php?fo=<?php echo encrypt('#localbody'); ?>"><i class="bi bi-pencil-square"></i></a></td>
                        </tr>
                        <tr>
                            <td><b>District</b></td>
                            <td> : </td>
                            <td>
                                <?php echo $district?>
                            </td>
                            <td></td>
                        </tr>
                        <?php
                                }
                            ?>
                        <?php
                            }
                        ?>
                        <tr>
                            <td><b>Password</b></td>
                            <td> : </td>
                            <td>
                                <button data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-outline-primary">Change Password</button>
                            </td>
                        </tr>
                        

                    </table>
                    <br>
                    <br>
                     <!-- <tr>
                        <a href="./mydetails.php"><input type="submit" name="EDIT" value="Edit" class="btn btn-outline-primary"></a>
                                
                        </tr> -->
                </center>

            </div>
            <div class="col-2"></div>
        </div>
    </div>

    </div>

    </div>

    <!-- Modal for resetting password -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Reset Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="password_form">
                <div class="modal-body">
                    <center><div style="display: none; padding: 10px;" id="alert" class="alert-danger"></div></center>
                    <input type="text" name="old_password" placeholder="Old Password" required><br>
                    <input type="text" name="password" id="password" placeholder="New Password" required><br>
                    <input type="text" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" name="reset_password" value="Reset Password" class="btn btn-outline-primary">
                </div>
                <!-- Password Strength Validation -->
                <div id="passwordError" style="display: none; padding: 10px; margin:0;color: red">
                    *Please select a password with atleast<br>
                    <ul>
                      <li>An uppercase</li>
                      <li>A lowercase</li>
                      <li>A letter</li>
                      <li>A number </li>
                      <li>8 characters</li>
                    </ul>
                  </div>
            </form>
        </div>
        </div>
    </div>

    <script>
        var passwd = $("#password").val();
        var confirm_passwd = $("#confirm_password").val();
        
        $(document).ready(function () {

            $("#password_form").submit(function (e) {

                var passwd = $("#password").val();
                var confirm_passwd = $("#confirm_password").val();

                if(passwd.match(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/)){
                    $('#passwordError').css("display", "none");
                } else{
                    display_error('Please enter a valid password');
                    $('#passwordError').css("display", "block");
                    interrupt();
                    return 0;
                }
                if (passwd != confirm_passwd) {
                    display_error('Passwords are not matching');
                    interrupt();
                    return 0;
                }

                function interrupt() {
                    e.preventDefault(e);;
                }
            });

            function display_error(error){
                $("#alert").css("display", "block");
                $("#alert").text(error);
            }

        });

    </script>
    
</body>

</html>
