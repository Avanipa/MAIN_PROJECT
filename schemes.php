<?php

require './navbar.php';

$scheme = "";
$id = "";
$schemeExist = 0;
$approvals = 0;
$approval_scheme_id = 0;

if(isset($_GET['tag'])){
  $scheme = $_GET['tag'];
  $scheme = str_replace(' ', '+', $scheme);
  $scheme = decrypt($scheme);
}else{
  header('Location: ./index.php');
  flush();
}

$description = "";
$eligibility = "";
$age = "";
$docs_required = "";


if(isset($_GET['id'])){
  $id = $_GET['id'];
  $id = str_replace(' ', '+', $id);
  $id = decrypt($id);
  $sql = "select * from tbl_cscheme where schemeid='$id' and status=1";
  $result = mysqli_query($conn, $sql);
  while($row=mysqli_fetch_array($result)){
    $description = $row['schemedescription'];
    $eligibility = $row['criteria'];
    $age = $row['category'];
    $docs_required = $row['docs_needed'];
  }
  $sql = "select * from tbl_user_schemes where cscheme_id='$id'";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_assoc($result)){
    $schemeExist = 1;
  }
}else{
  $description = "NA";
  $eligibility = "NA";
  $age = "NA";
  $docs_required = "NA";
}

if(isset($_GET['tags'])){
  $approvals = 1;
  $approval_scheme_id = $_GET['tags'];
  $approval_scheme_id = str_replace(' ', '+', $approval_scheme_id);
  $approval_scheme_id = decrypt($approval_scheme_id);
  $approval_scheme = "select * from tbl_cscheme where schemeid='$approval_scheme_id'";
  $approval_scheme_result = mysqli_query($conn, $approval_scheme);
  while($scheme_row=mysqli_fetch_assoc($approval_scheme_result)){
    $name = $scheme_row['name'];
    $description = $scheme_row['schemedescription'];
    $eligibility = $scheme_row['criteria'];
    $age = $scheme_row['category'];
    $docs_required = $scheme_row['docs_needed'];
  }
}

// Checking eligibility
// $reg_id = $_SESSION['regid'];
// $userDetails = "select * from tbl_user_details where user_reg_id='$reg_id'";
// $userDetails_result = mysqli_query($conn, $userDetails);
// $userDetails_row = mysqli_fetch_assoc($userDetails_result);
// $marital_status = $userDetails_row['marital_status'];
// if($marital_status==3){
//   $marry = "widow";
// }


?>

<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Schemes</title>
  <link rel="stylesheet" href="./assets/css/css/bootstrap.min.css">
  <link rel="shortcut icon" type="image/jpg" href="./assets/images/logo.png"/>
  <link rel="stylesheet" href="./assets/css/css/index.css">
  <script src="./assets/jquery-3.6.0.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body>

    <div class="container">
      <div class="row">
        <?php
          if($approvals==0){
        ?>
          <h3 class="scheme_head">Loan</h3>
            <div id="row1" class="col-4" style="padding:20px; background-color: rgba(255, 238, 140, 0.7); border-top-left-radius: 10px; border-bottom-left-radius: 10px;">
              <ul class="scheme_row_1">
                <?php

                  $sql = "select * from tbl_cscheme where name='$scheme' and status=1";
                  $result = mysqli_query($conn, $sql);
                  while($row=mysqli_fetch_array($result)){
                    $docs_needed = $row['docs_needed'];
                  ?>
                    <a href="./schemes?tag=<?php echo encrypt($scheme) ?>&id=<?php echo encrypt(strval($row['schemeid'])) ?>">
                  <?php
                      echo "<li>" .ucfirst($row['subscheme']). "</li>";
                    echo "</a>";
                  }

                ?>
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
                  }else if($schemeExist==1){
                    echo "<button style='margin-left:30%' class='btn btn-outline-danger' disabled>";
                      echo "You have already applied for this scheme";
                    echo "</button>";
                  }else{
                    $enc = encrypt($id);
                    echo "<a href='./scheme_submission?tag=$enc'>";
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
          }else if($approvals==1){
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
              <a href='./approvals?tag=<?php echo encrypt('approvals') ?>'>
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