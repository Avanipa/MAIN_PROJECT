<?php

require './navbar.php';

if(!isset($_SESSION['regid'])){
  header('Location: index');
  exit();
}

$approvals = "";
$userType = $_SESSION['usertype'];
$user = "";
$id = 104;

if($userType==0){
    $user = 0;
}elseif($userType==1){
    $user = 1;
}

// ID checking from approvals page
if(isset($_GET['id'])){
  $id = $_GET['id'];
  $id = str_replace(' ', '+', $id);
  $id = decrypt($id);
}

// back to approvals check
if(isset($_GET['ser'])){
  $approvals = 'service';
}elseif(isset($_GET['sch'])){
  $approvals = 'scheme';
}elseif(isset($_GET['appsch'])){
  $approvals = 'appscheme';
}elseif(isset($_GET['appser'])){
  $approvals = 'appservice';
}

$sql_reg = "select * from tbl_registration where regid='$id'";
$result_reg = mysqli_query($conn, $sql_reg);
$row = mysqli_fetch_assoc($result_reg);

$sql_log = "select * from tbl_login where login_id='$id'";
$result_log = mysqli_query($conn, $sql_log);
$login_row = mysqli_fetch_assoc($result_log);

$userDetails = "select * from tbl_user_details where user_reg_id='$id'";
$userDetails_result = mysqli_query($conn, $userDetails);
$userDetails_row = mysqli_fetch_assoc($userDetails_result);
$qualification = $userDetails_row['qualifications'];
$qualification = explode(',', $qualification);
$qual = [];
for($i=0; $i<sizeof($qualification); $i++){
  $qual[$i] = ucfirst($qualification[$i])." ";
}
$qualification = implode(', ', $qual);

?>
<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>View Users</title>
  <link rel="stylesheet" href="./assets/css/css/bootstrap.min.css">
  <link rel="shortcut icon" type="image/jpg" href="./assets/images/logo.png"/>
  <link rel="stylesheet" href="./assets/css/css/index.css">
  <script src="./assets/jquery-3.6.0.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body>


<h3 style="margin:15px 0 20px 40px">User Details</h3>

<div class="container">
    <div class="row">
        <table class="view_user_table">
            <tr>
                <th>Registration ID</th>
                <td><?php
                    $reg_id = $row['regid'];
                    $reg_id = $reg_id*1000+(9876543210-1234567890);
                    echo $reg_id;
                ?></td>
            </tr>
            <tr>
                <th>Name</th>
                <td><?php echo ucfirst($row['uname']) ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo $login_row['emailid'] ?></td>
            </tr>
            <tr>
                <th>DOB</th>
                <td><?php echo $row['dob'] ?></td>
            </tr>
            <tr>
                <th>Caste</th>
                <td><?php echo ucfirst($userDetails_row['caste']) ?></td>
            </tr>
            <tr>
                <th>Religion</th>
                <td><?php echo ucfirst($userDetails_row['religion']) ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo ucfirst($row['uaddress']) ?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?php echo $row['uphone'] ?></td>
            </tr>
            <tr>
                <th>Adhaaar</th>
                <td><?php echo $row['aadharno'] ?></td>
            </tr>
            <tr>
                <th>Gender</th>
                <td><?php echo ucfirst($row['gender']) ?></td>
            </tr>
            <tr>
                <th>Qualification</th>
                <td><?php echo $qualification ?></td>
            </tr>
            <tr>
                <th>Place</th>
                <td><?php echo ucfirst($row['place']) ?></td>
            </tr>
            <tr>
                <th>ward</th>
                <td><?php echo $userDetails_row['ward'] ?></td>
            </tr>
            <tr>
                <th>Local Body</th>
                <td><?php echo ucfirst($row['localbody']) ?></td>
            </tr>

            <tr>
                <th>District</th>
                <td><?php echo ucfirst($row['district']) ?></td>
            </tr>

        </table>
        <center>
          <?php
          if($approvals=='service'){
          ?>
            <a href="./approvals?ser=<?php echo encrypt('service_approvals') ?>" >
              <button style='margin-top: 20px;' class='btn btn-outline-success'>Back</button>
            </a>
          <?php
          }elseif($approvals=='scheme'){
          ?>
            <a href="./approvals?sch=<?php echo encrypt('approvals') ?>" >
              <button style='margin-top: 20px;' class='btn btn-outline-success'>Back</button>
            </a>
          <?php
          }elseif($approvals=='appscheme'){
            ?>
            <a href="./approvals?appsch=<?php echo encrypt('approved') ?>" >
              <button style='margin-top: 20px;' class='btn btn-outline-success'>Back</button>
            </a>
        <?php
          }elseif($approvals=='appservice'){
            ?>
            <a href="./approvals?appser=<?php echo encrypt('service_approved') ?>" >
              <button style='margin-top: 20px;' class='btn btn-outline-success'>Back</button>
            </a>
        <?php
          }
          ?>

        </center>
    </div>
</div>

<?php include 'footer.php' ?>

</body>

<script>
  $(document).ready(function () {

    if ($(window).width() < 991) {
      $("#index_slide").attr('class', 'col-12');
      $("#index_boxes").attr('class', 'col-12');
    }

  });
</script>

</html>
</html>