<script>
  var genderConst = 1;
</script>

<?php

require_once './include/connect.php';
require './encdec.php';

$userType = "";
$register = "";
$enable = "";
$active = 1;

if(isset($_GET['tag'])){
  $data = $_GET['tag'];
  $userType = decrypt($data);
  $enable=1;
}

if($enable!=1){
  header('Location: login');
}

if(isset($_GET['error'])){
  $data = $_GET['error'];
  $error = decrypt($data);
}

if($userType==0){
  $register = "User Registration";
}else if($userType ==1){
  $register = "Company Registration";
  echo "<script>";
    echo "var genderConst = 0";
  echo "</script>";

}

if(isset($_POST['register'])){

  $flag = 1;
  $email_flag = 1;
  
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $name = ucwords($name);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $address = mysqli_real_escape_string($conn, $_POST['address']);
  $number = mysqli_real_escape_string($conn, $_POST['number']);
  $district = mysqli_real_escape_string($conn, $_POST['district']);
  $place = mysqli_real_escape_string($conn, $_POST['place']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);
  $hash = password_hash($password, PASSWORD_DEFAULT);

  if($userType==0){
    $company_name = 'NULL';
    $description = 'NULL';
    $company_registration = 0;
  }else if($userType==1){
    $company_name = mysqli_real_escape_string($conn, $_POST['company-name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $company_registration = mysqli_real_escape_string($conn, $_POST['registration']);
  }
  
  if($userType==1){
    $date = '0000-00-00';
    $gender = 'NULL';
    $adhaar = 0;
    $localbody = 'NULL';
  }else if($userType==0){
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $adhaar = mysqli_real_escape_string($conn, $_POST['adhaar']);
    $localbody = mysqli_real_escape_string($conn, $_POST['localbody']);
  }

  if($userType==0){
    $test_sql = "SELECT aadharno FROM tbl_registration where aadharno='$adhaar'";
    $result = mysqli_query($conn, $test_sql);
    if(mysqli_fetch_assoc($result)){
      $flag = 0;
    }
  }else if($userType==1){
    $test_sql = "SELECT company_registration FROM tbl_registration where company_registration='$company_registration'";
    $result = mysqli_query($conn, $test_sql);
    if(mysqli_fetch_assoc($result)){
      $flag = 0;
    }
  }

  if($userType==0){
    $test_sql = "SELECT emailid FROM tbl_login where emailid='$email'";
    $result = mysqli_query($conn, $test_sql);
    if(mysqli_fetch_assoc($result)){
      $email_flag = 0;
    }
  }else if($userType==1){
    $test_sql = "SELECT emailid FROM tbl_login where emailid='$email'";
    $result = mysqli_query($conn, $test_sql);
    if(mysqli_fetch_assoc($result)){
      $email_flag = 0;
    }
  }

  if($flag==1 && $email_flag==1){
    $designation = "NULL";
    $sql = "INSERT INTO tbl_registration(uname, uaddress, uphone, aadharno, company_registration, gender, dob, place, district, localbody, companyname, description) values
    ('$name','$address', '$number', '$adhaar', '$company_registration', '$gender', '$date', '$place', '$district', '$localbody', '$company_name', '$description')";
    if(mysqli_query($conn, $sql)){
      $login_table = "INSERT INTO tbl_login(login_id, emailid, password, usertype, designation, status, verified) VALUES((SELECT max(regid) from tbl_registration), '$email', '$hash', '$userType', '$designation', '$active', 0);";
      mysqli_query($conn, $login_table);
      if($userType==0){
        $userDetails = "insert into tbl_user_details(user_reg_id, marital_status, ward, caste, religion, qualifications, display_picture) values
        ((SELECT max(regid) from tbl_registration), 0, 0, 'NULL', 'NULL', 'NULL', 'NULL')";
        if(mysqli_query($conn, $userDetails)){
          $tbl_doc_table_sql = "insert into tbl_documents(fid, adhaar, pan, birth, death, income, widow, tenth, plus2) values
          ((SELECT max(regid) from tbl_registration), 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL','NULL')";
          mysqli_query($conn, $tbl_doc_table_sql);
        }else{
          die();
        }
      }
      header('Location: ./login');
    }else{
      echo mysqli_error($conn);
      die();
    }
  }else if($flag==0){
    $status = encrypt($userType);
    $error = encrypt("Error");
    header("Location: register?tag=$status&error=$error");
  }
  
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $register ?></title>
  <link rel="stylesheet" href="./assets/css/css/bootstrap.min.css">
  <link rel="stylesheet" href="./assets/css/css/login.css">
  <link rel="shortcut icon" type="image/jpg" href="./assets/images/logo.png"/>
  <script src="./assets/jquery-3.6.0.js"></script>
</head>

<body>

  <div class="container">
    <div class="row justify-content-md-center" style="margin-top: 5%;">
      <div id="col1" class="col-3"></div>
      <div id="col2" class="col-6 login-main-div"><br>
        <center><b class="login-head"><?php echo $register ?></b></center><br>
        <div id="alert" class="alert-danger">ahi</div>

        <?php
          if(isset($_GET['error'])){
            if ($error=="Error"){
              $message = $_GET['error'];
              $message = "Account already exists, Please login";
        ?>
         <center><div id="alert" class="alert-warning"><?php echo $message ?></div></center><br>
        <?php
            }
          }
        ?>

        <form id="form" action="" method="POST" style="width: 100%;" autocomplete="OFF">
          <div class="container">
            <input type="text" id="name" name="name" class="inp px-3" pattern="[A-Za-z]+" title='Please neter a valid name' placeholder="Name" required>
            <?php
            if($userType==0){
            ?>
              <input type="email" name="email" class="inp px-3" placeholder="Email id" required>
            <?php
            }else if($userType==1){
            ?>
              <input type="email" name="email" class="inp px-3" placeholder="Company Email id" required>
            <?php
            }
            ?>
            <input type="number" id="phone" name="number" pattern="^[6-9].[0-9]+"
            title='Please enter a valid phone number'
            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
            type = "number"
            maxlength = "10"
            min="0"
            class="inp px-3" placeholder="Phone" required>
            <input type="text" name="address" class="inp px-3" placeholder="Address" required>
            <?php
              if ($userType==0){
            ?>
              <div class="gender_style">
                <h6>Gender&nbsp;&nbsp;</h6>
                <input type="radio" name="gender" id="inlineRadio1" value="male">
                <!-- <input type="radio" name="male" id="inlineRadio1" value="male"> -->
                <label for="inlineRadio2">Male</label>
                <input type="radio" name="gender" id="inlineRadio2" value="female">
                <!-- <input type="radio" name="female" id="inlineRadio2" value="female"> -->
                <label for="inlineRadio2">Female</label>
                <input type="radio" name="gender" id="inlineRadio3" value="other">
                <!-- <input type="radio" name="female" id="inlineRadio2" value="female"> -->
                <label for="inlineRadio2">Other</label>
                <p id="genderError" style="display: none; padding: 0; margin:0;color: yellow;">*Gender is required</p>
              </div>
              <div class="dob_style" required>
                <h6 style="display: inline;">DOB</h6>
                <input style="display: inline;" type="date" name="date" class="inp px-3" value="Date" placeholder="Date" min="1980-01-01" max="2010-12-30" required>
              </div>
              <input type="number" id="adhaar" name="adhaar" class="inp px-3" placeholder="Adhaar number"
              oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
              type = "number"
              maxlength = "12"
              min="0"
              required>
              <!-- <input type="text" name="ward" class="inp px-3" placeholder="Ward"> -->
            <?php
              }
            ?>
            <input type="text" name="place" class="inp px-3" placeholder="Place" required>
            <div class="district_drop">
              <label for="district">District</label>
              <select name="district" id="district" required>
                <option value="Alappuzha">Alappuzha</option>
                <option value="Ernakulam">Ernakulam</option>
                <option value="Idukki">Idukki</option>
                <option value="Kannur">Kannur</option>
                <option value="Kasaragod">Kasaragod</option>
                <option value="Kollam">Kollam</option>
                <option value="Kottayam">Kottayam</option>
                <option value="Kozhikode">Kozhikode</option>
                <option value="Malappuram">Malappuram</option>
                <option value="Palakkad">Palakkad</option>
                <option value="Pathanamthitta">Pathanamthitta</option>
                <option value="Thiruvananthapuram">Thiruvananthapuram</option>
                <option value="Thrissur">Thrissur</option>
                <option value="Wayanad">Wayanad</option>
                <option value="NULL" selected>Select District</option>
              </select>
            </div>
            <?php
              if ($userType==0){
            ?>
              <input type="text" name="localbody" class="inp px-3" placeholder="Localbody" required>
            <?php
              }
            ?>

            <?php
              if ($userType==1){
            ?>
                <input type="number" id="registration" name="registration" class="inp px-3" placeholder="Registration Number" required>
                <input type="text" name="company-name" class="inp px-3" placeholder="Company Name" required>
                <div class="district_drop">
                  <label for="description">Type</label>
                  <select name="description" id="description">
                    <option value="Public IT">Public IT</option>
                    <option value="Govt IT">Govt IT</option>
                    <option value="Private IT">Private IT</option>
                    <option value="Private Sector">Private Sector</option>
                    <option value="Public Sector">Public Sector</option>
                    <option value="Other">Other</option>
                  </select>
                </div>
            <?php
              }
            ?>

            <input type="password" id="passwd" name="password" class="inp px-3" placeholder="Password" required>
            <div id="passwordError" style="display: none; padding: 0; margin:0;color: yellow;">
              *Please select a password with atleast<br>
              <ul>
                <li>An uppercase</li>
                <li>A lowercase</li>
                <li>A letter</li>
                <li>A number </li>
                <li>8 characters</li>
              </ul>
            </div>
            <input type="password" id="confirm_passwd" name="confirm_password" class="inp px-3"
              placeholder="Confirm Password" required>
          </div>
          <div class="form-row py-4">
            <div class="offset-1 col-lg-10">
              <center><input type="submit" name="register" value="Register" class="btn btn-success" id="register"></center><br>
              <p style="color: white; margin: 0;">Already have an account? &nbsp; <a href="./login" style="color: lightblue;">Sign in</a><br><br></p>
            </div>
          </div>
        </form>
      </div>
      <div id="col3" class="col-3"></div>
    </div>
  </div>
</body>

<script>
  $(document).ready(function () {

    $("#form").submit(function (e) {

      var passwd = $("#passwd").val();
      var confirm_passwd = $("#confirm_passwd").val();
      var phone = $("#phone").val();
      var adhaar = $("#adhaar").val();
      var isMale = $('#inlineRadio1').is(':checked');
      var isfemale = $('#inlineRadio2').is(':checked');
      var isOther = $('#inlineRadio3').is(':checked');
      var district = $('#district').val();

      const regexExp = /^[6-9]\d{9}$/gi;

      if (String(phone).length != 10) {
        display_error("Please enter a valid 10 digit phone number");
        $('#phone').focus();
        interrupt();
        return 0;
      }

      if(regexExp.test(String(phone)) == false){
        display_error("Please enter a valid mobile number");
        $('#phone').focus();
        interrupt();
        return 0;
      }
      
      if(isMale!=true && isfemale!=true && isOther!=true && genderConst==1){
        display_error('Please select a gender');
        $('#genderError').css("display", "block");
        interrupt();
        return 0;
      } else if(isMale==true || isfemale==true || isOther==true){
        $('#genderError').css("display", "none");
      } 
      if(genderConst==1){
        if(adhaar.length != 12){
          display_error('Please enter a valid adhaar number');
          interrupt();
          return 0;
        } 
      }
      if(district=='NULL'){
        display_error('Please select a district');
        interrupt();
        return 0;
      } 
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
        e.preventDefault(e);
      }
    });

    if ($(window).width() < 991) {
      $("#col1").attr('class', 'col-1');
      $("#col2").attr('class', 'col-10');
      $("#col3").attr('class', 'col-1');
      $("#col2").addClass('login-main-div');
    }
    function display_error(error){
      $("#alert").css("display", "block");
      $("#alert").text(error);
    }

  });
</script>

</html>