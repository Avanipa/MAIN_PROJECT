<?php

    require_once '../include/connect.php';
    require './encdec.php';
    require '../mail.php';

    session_start();

    if(!isset($_SESSION['superuser'])){
        header('Location: ./index.php');
        exit();
    }

    if(isset($_POST['submit_staff'])){

        $flag = 1;
        $flag_email = 1;

        // echo $flag."   ".$flag_email;

        // Password
        $comb = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $shfl = str_shuffle($comb);
        $password = substr($shfl,0,10);

        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $designation = mysqli_real_escape_string($conn, $_POST['designation']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $number = mysqli_real_escape_string($conn, $_POST['number']);
        $date = mysqli_real_escape_string($conn, $_POST['date']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $adhaar = mysqli_real_escape_string($conn, $_POST['adhaar']);
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $district = 'NULL';
        $place = 'NULL';
        $company_name = 'NULL';
        $description = 'NULL';
        $company_registration = 0;
        $ward = 0;
        $localbody = 'NULL';
        $active = 1;
        $userType = 2;

        $test_sql_adhaar = "SELECT aadharno FROM tbl_registration where aadharno='$adhaar'";
        $result_adhaar = mysqli_query($conn, $test_sql_adhaar);
        if(mysqli_fetch_assoc($result_adhaar)){
            $flag = 0;
        }
        
        $test_sql_email = "SELECT emailid FROM tbl_login where emailid='$email'";
        $result_email = mysqli_query($conn, $test_sql_email);
        if(mysqli_fetch_assoc($result_email)){
            $flag_email = 0;
        }

        if($flag==1 && $flag_email==1){
            $sql = "INSERT INTO tbl_registration(uname, uaddress, uphone, aadharno, company_registration, gender, dob, place, district, localbody, companyname, description) values
            ('$name','$address', '$number', '$adhaar', '$company_registration', '$gender', '$date', '$place', '$district', '$localbody', '$company_name', '$description')";
            if(mysqli_query($conn, $sql)){
                $login_table = "INSERT INTO tbl_login(login_id, emailid, password, usertype, designation, status, verified) VALUES((SELECT max(regid) from tbl_registration), '$email', '$hash', '$userType', '$designation', '$active', 0);";
                mysqli_query($conn, $login_table);
                $leave_sql = "INSERT INTO tbl_leave(leave_regid, earned_leave, casual_leave, sick_leave, hospital_leave, maternity_leave) 
                VALUES((SELECT max(regid) from tbl_registration), 30, 14, 90, 90, 180)";
                mysqli_query($conn, $leave_sql);
                
                $recipient = $email;
                $message = 'Hi ' . $name . ',' . '<br><br> Please find attached login credentials.<br><br>Username: '.$email.'<br>Password: '.$password;

                $subject = 'Employment Exchange | Login Credentials';

                send_mail($recipient, $subject, $message);
                $alert_message = "Credentials have been sent to " .$name. "'s email address";

                echo '<script type="text/javascript">alert("'.$alert_message.'");</script>';
            }
        }else{
            header('Location: ./officers.php?alr');
        }

    }

    if(isset($_POST['submit_officer'])){

        $flag = 1;
        $flag_email = 1;

        // Password
        $comb = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
        $shfl = str_shuffle($comb);
        $password = substr($shfl,0,10);

        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $designation = mysqli_real_escape_string($conn, $_POST['designation']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $number = mysqli_real_escape_string($conn, $_POST['number']);
        $date = mysqli_real_escape_string($conn, $_POST['date']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $adhaar = mysqli_real_escape_string($conn, $_POST['adhaar']);
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $district = 'NULL';
        $place = 'NULL';
        $company_name = 'NULL';
        $description = 'NULL';
        $company_registration = 0;
        $ward = 0;
        $localbody = 'NULL';
        $active = 1;
        $userType = 3;

        $test_sql_adhaar = "SELECT aadharno FROM tbl_registration where aadharno='$adhaar'";
        $result_adhaar = mysqli_query($conn, $test_sql_adhaar);
        if(mysqli_fetch_assoc($result_adhaar)){
            $flag = 0;
        }
        
        $test_sql_email = "SELECT emailid FROM tbl_login where emailid='$email'";
        $result_email = mysqli_query($conn, $test_sql_email);
        if(mysqli_fetch_assoc($result_email)){
            $flag_email = 0;
        }

        if($flag==1 && $flag_email==1){
            $sql = "INSERT INTO tbl_registration(uname, uaddress, uphone, aadharno, company_registration, gender, dob, place, district, localbody, companyname, description) values
            ('$name','$address', '$number', '$adhaar', '$company_registration', '$gender', '$date', '$place', '$district', '$localbody', '$company_name', '$description')";
            if(mysqli_query($conn, $sql)){
                $login_table = "INSERT INTO tbl_login(login_id, emailid, password, usertype, designation, status, verified) VALUES((SELECT max(regid) from tbl_registration), '$email', '$hash', '$userType', '$designation', '$active', 0);";
                mysqli_query($conn, $login_table);
                $encpass = encrypt($password);
                $passSQL = "INSERT INTO tbl_password(eid, epasswd) values((SELECT max(regid) from tbl_registration), '$encpass')";
                mysqli_query($conn, $passSQL);
                $leave_sql = "INSERT INTO tbl_leave(leave_regid, earned_leave, casual_leave, sick_leave, hospital_leave, maternity_leave) 
                VALUES((SELECT max(regid) from tbl_registration), 30, 14, 90, 90, 180)";
                mysqli_query($conn, $leave_sql);

                $recipient = $email;
                $message = 'Hi ' . $name . ',' . '<br><br> Please find attached login credentials.<br><br>Username: '.$email.'<br>Password: '.$password;

                $subject = 'Employment Exchange | Login Credentials';

                send_mail($recipient, $subject, $message);
                $alert_message = "Credentials have been sent to " .$name. "'s email address";

                echo '<script type="text/javascript">alert("'.$alert_message.'");</script>';
                
            }else{
                echo mysqli_error($conn);
            }
        }else{
            header('Location: ./officers.php?alr');
        }

    }

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $sql = "update tbl_login set status=0 where login_id='$id'";
        if(mysqli_query($conn, $sql)){
            echo "<script>";
                echo "alert('Disabled Successfully')";
            echo "</script>";
        }
        header('Location: ./officers.php');
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Staff / Offcers</title>
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
            <!-- Inser update delete slides -->

            <div class="details">

                <!-- Update button and choose file -->

                <div class="update-top-bar">
                    <div class="cardHeader">
                        <h2>Insert Officer/Staff</h2>
                    </div>

<!-- Alerts -->
                <?php
                    if(isset($_GET['success'])){
                        $message = "User added successfully"
                ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong id="alert-status"> <?php echo $message ?> </strong>
                        <button type="button" style="float:right; background:transparent; border:none" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="bi bi-x-octagon"></i></span>
                        </button>
                    </div>
                <?php
                    }elseif(isset($_GET['alr'])){
                        $message = "User already exists";
                ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <center>
                        <strong id="alert-status"> <?php echo $message ?> </strong>
                        <button type="button" style="float:right; background:transparent; border:none" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="bi bi-x-octagon"></i></span>
                        </button>
                        </center>
                    </div>
                <?php
                    }
                ?>
<!-- Alerts End -->
                    <div class="inputstyles">
                    <center><div id="alert" class="alert-danger">ahi</div></center>
                        <form id="form" action="" method="POST">
                            <input type="text" name="name" class="inp px-3" placeholder="Name" required>
                            <input type="email" name="email" class="inp px-3" placeholder="Email id" required>
                            <input type="text" name="designation" class="inp px-3" placeholder="Designation" required>
                            <input type="number" id="phone" name="number"
                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                            type = "number"
                            maxlength = "10"
                            min="0"
                            class="inp px-3" placeholder="Phone" >
                            <input type="text" name="address" class="inp px-3" placeholder="Address" required>
                            <div class="gender_style">
                            <h6>Gender&nbsp;&nbsp;</h6>
                            <input type="radio" name="gender" id="inlineRadio1" value="male">
                            <!-- <input type="radio" name="male" id="inlineRadio1" value="male"> -->
                            <label for="inlineRadio2">Male</label>
                            <input type="radio" name="gender" id="inlineRadio2" value="female">
                            <!-- <input type="radio" name="female" id="inlineRadio2" value="female"> -->
                            <label for="inlineRadio2">Female</label>
                            </div>
                            <div class="dob_style">
                                <h6 style="display: inline;">DOB</h6>
                                <input style="display: inline;" type="date" name="date" class="inp px-3" value="Date" placeholder="Date"  min="1950-01-01" max="2000-12-30" required>
                            </div>
                            <input type="number" id="adhaar"
                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                            type = "number"
                            maxlength = "12"
                            min="0"
                            name="adhaar" class="inp px-3" placeholder="Adhaar number" ><br>

                            <input class="btn btn-outline-info" type="submit" name="submit_staff" id="" value="Insert Staff">
                            <input class="btn btn-outline-info" type="submit" name="submit_officer" id="" value="Insert Officer"> 
                        </form>
                    </div>
                </div>

                <div class="top-alert">
                    <div class="cardHeader">
                        <h2>Added Staffs</h2>
                    </div>
                    <table>
                        <thead>
                            <td>Category</td>
                            <td>Designation</td>
                            <td>Status</td>
                        </thead>
                        <tbody>
                            <?php
                                $sql = "select * from tbl_login where usertype=3 or usertype=4";
                                $result = mysqli_query($conn, $sql);
                                while($row = mysqli_fetch_array($result)){
                                    $stat = $row['status'];
                                    $usertype = $row['usertype'];
                                    if ($usertype==3){
                                        echo "<tr style='background-color: lightblue;'>";
                                        echo "<td>" .$row['emailid']. "</td>";
                                        echo "<td>" .$row['designation']. "</td>";
                                ?>
                                        <td>
                                            <?php
                                                if($stat==0){
                                                    ?>
                                                    <button type='submit' name='disable' class='btn btn-sm btn-danger'>
                                                        <a style="text-decoration: none; color:white" href="./officers.php?id=<?php echo $row['login_id'] ?>">Disabled</a>
                                                    </button>
                                                <?php
                                                }else{
                                                ?>
                                                    <button type='submit' name='disable' class='btn btn-outline-info'>
                                                        <a style="text-decoration: none;" href="./officers.php?id=<?php echo $row['login_id'] ?>">Disable</a>
                                                    </button>
                                                <?php
                                                }
                                            ?>
                                    </td>
                                <?php
                                    echo "</tr>";
                                    }else if($usertype==4){
                                        echo "<tr style='background-color: rgb(255, 203, 203);'>";
                                        echo "<td>" .$row['emailid']. "</td>";
                                        echo "<td>" .$row['designation']. "</td>";
                                ?>
                                        <td>
                                            <?php
                                                if($stat==0){
                                                    ?>
                                                    <button type='submit' name='disable' class='btn btn-sm btn-danger'>
                                                        <a style="text-decoration: none; color:white" href="./officers.php?id=<?php echo $row['login_id'] ?>">Disabled</a>
                                                    </button>
                                                <?php
                                                }else{
                                                ?>
                                                    <button type='submit' name='disable' class='btn btn-outline-info'>
                                                        <a style="text-decoration: none;" href="./officers.php?id=<?php echo $row['login_id'] ?>">Disable</a>
                                                    </button>
                                                <?php
                                                }
                                            ?>
                                    </td>
                                <?php
                                    echo "</tr>";
                                    }
                                }
                                    
                            ?>
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>

</body>

<script>
  $(document).ready(function () {
    $("#form").submit(function (e) {
      var phone = $("#phone").val();
      var adhaar = $("#adhaar").val();
      var isMale = $('#inlineRadio1').is(':checked');
      var isfemale = $('#inlineRadio2').is(':checked');

      if (String(phone).length != 10) {
        display_error("Please enter a valid phone number");
        interrupt();
      } else if(isMale!=true && isfemale!=true){
        display_error('Please select a gender');
        interrupt();
      }else if(adhaar.length != 12){
        display_error('Please enter a valid adhaar number');
        interrupt();
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

</html>