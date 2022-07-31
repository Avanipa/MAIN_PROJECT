<?php

require './navbar.php';

$service = "";
$serviceExist = 0;
$approvals = 0;

if(isset($_GET['tag'])){
  $service = $_GET['tag'];
  $service = str_replace(' ', '+', $service);
  $service = decrypt($service);
}else{
  header('Location: ./index.php');
}

$description = "";
$end = "";
$eligibility = "";
$age = "";

if(isset($_GET['id'])){
  $id = $_GET['id'];
  $id = str_replace(' ', '+', $id);
  $id = decrypt($id);
  $sql = "select * from tbl_services where serviceid='$id' and status=1";
  $result = mysqli_query($conn, $sql);
  while($row=mysqli_fetch_array($result)){
    $description = $row['service_description'];
    $eligibility = $row['criteria'];
    $age = $row['category'];
    $docs_required = $row['docs_needed'];
  }
  $sql = "select * from tbl_user_services where service_id='$id'";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_assoc($result)){
    $serviceExist = 1;
  }
}else{
  $description = "NA";
  $eligibility = "NA";
  $age = "NA";
  $docs_required = "NA";
}

if(isset($_POST['apply'])){
  if(!isset($_SESSION['regid'])){
    echo "<script>";
      echo "alert('Please login to apply')";
    echo "</script>";
  }
}


// ***************Approval Page redirect*****************************
if(isset($_GET['tags'])){
  $approvals = 1;
  $approval_service_id = $_GET['tags'];
  $approval_service_id = str_replace(' ', '+', $approval_service_id);
  $approval_service_id = decrypt($approval_service_id);
  $approval_service = "select * from tbl_services where serviceid='$approval_service_id'";
  $approval_service_result = mysqli_query($conn, $approval_service);
  while($service_row=mysqli_fetch_assoc($approval_service_result)){
    $name = $service_row['name'];
    $description = $service_row['service_description'];
    $eligibility = $service_row['criteria'];
    $age = $service_row['category'];
    $docs_required = $service_row['docs_needed'];
  }
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Services</title>
  <link rel="stylesheet" href="./assets/css/css/bootstrap.min.css">
  <link rel="shortcut icon" type="image/jpg" href="./assets/images/logo.png"/>
  <link rel="stylesheet" href="./assets/css/css/index.css">
  <script src="./assets/jquery-3.6.0.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body>

    <h3 class="scheme_head">Services</h3>

    <div class="container">
      <div class="row">
        <?php
          if($approvals==0){
        ?>
          <div id="row1" class="col-4" style="padding:20px; background-color: rgba(255, 238, 140, 0.7); border-top-left-radius: 10px; border-bottom-left-radius: 10px;">
          <ul class="scheme_row_1">
            <?php

              $sql = "select * from tbl_services where name='$service' and status=1";
              $result = mysqli_query($conn, $sql);
              while($row=mysqli_fetch_array($result)){
              ?>
                <a href="./services?tag=<?php echo encrypt($service) ?>&id=<?php echo encrypt(strval($row['serviceid'])) ?>">
              <?php
                  echo "<li>" .ucfirst($row['subservice']). "</li>";
                echo "</a>";
              }

            ?>
            </ul>
          </div>

          <div id="row2" class="col-8 scheme_row_2" style="padding:20px; background-color: rgba(252, 233, 128, 0.7); border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
            <h3>Description</h3>
            <p><?php echo $description ?></p>
            <h3>Documents Needed</h3>
            <p><?php echo $docs_required ?></p>
            <h3>Eligibility criteria</h3>
            <p><?php echo $eligibility ?></p>
            <h3>Age Groups</h3>
            <p><?php echo $age ?></p>
                
            <?php
                    
                if(isset($_SESSION['regid'])){
                  
                  if($_SESSION['verified'] == 0){
                    echo "<a href='./email_verify'>";
                        echo "<button style='margin-left:40%' class='btn btn-outline-danger'>";
                          echo "Please verify your email to access this service";
                        echo "</button>"; 
                      echo "</a>";
                  }else{
                    if($description=='NA'){
                      echo "<a href='#'>";
                        echo "<button style='margin-left:40%' class='btn btn-outline-danger'>";
                          echo "Please select anyone scheme";
                        echo "</button>"; 
                      echo "</a>";
                    }else if($serviceExist==1){
                      echo "<button style='margin-left:30%' class='btn btn-outline-danger' disabled>";
                        echo "You have already applied for this scheme";
                      echo "</button>";
                    }else{
                      $enc = encrypt($id);
                      echo "<a href='./service_submission?tag=$enc'>";
                        echo "<button style='margin-left:40%' class='btn btn-outline-success'>";
                          echo "Apply now";
                        echo "</button>"; 
                      echo "</a>";
                    }
                  }
                  
                  }else{
                    echo "<a href='./login'>";
                      echo "<button style='margin-left:40%' class='btn btn-outline-danger'>";
                        echo "Please Login to Apply";
                      echo "</button>"; 
                    echo "</a>";
                  }

              ?>

          </div>
        <?php
          }elseif($approvals==1){
        ?>
          <div id="row1" class="col-4" style="padding:20px; background-color: rgba(255, 238, 140, 0.7); border-top-left-radius: 10px; border-bottom-left-radius: 10px;">
              <ul class="scheme_row_1">
                <li><?php echo $name ?></li>
              </ul>
            </div>

            <div id="row2" class="col-8 scheme_row_2" style="padding:20px; background-color: rgba(252, 233, 128, 0.7); border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
              <h3><b>Description</b></h3>
              <p><?php echo $description ?></p>
              <h3><b>Eligibility criteria</b></h3>
              <p><?php echo $eligibility ?></p>
              <h3><b>Age Groups</b></h3>
              <p><?php echo $age ?></p>
              <h3><b>Documents Required</b></h3>
              <p><?php echo $docs_required ?></p>
              <a href='./approvals?ser=<?php echo encrypt('service_approvals') ?>'>
                <button style='margin-left:40%' class='btn btn-outline-primary'>
                  Back
                </button>
              </a>
          </div>
        <?php
          }
        ?>
      </div>
    </div>

    <?php include 'footer.php' ?>

</body>

<script>
  $(document).ready(function () {

    if ($(window).width() < 991) {
      $("#row1").attr('class', 'col-12');
      $("#row2").attr('class', 'col-12');
    }

  });
</script>

</html>