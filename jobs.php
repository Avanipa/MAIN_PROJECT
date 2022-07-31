<?php

require './navbar.php';

if(!isset($_SESSION['regid'])){

echo "<script>";
echo "location.replace('./index')";
echo "</script>";    
    exit();
}

$userID = $_SESSION['regid'];
$eligible = 1;
$approvals = 0;
$job_details = 0;
$dataExistForQualification = 1;
$qualificationNoMatch = 1;

if($_SESSION['usertype']==2 or $_SESSION['usertype']==3){
    $approvals = 1;
}else{
    $sql_doc = "select * from tbl_user_details where user_reg_id='$userID'";
    $result_doc = mysqli_query($conn, $sql_doc);
    $doc_row = mysqli_fetch_assoc($result_doc);
    $qualification = $doc_row['qualifications'];
    if($qualification=='NULL'){
        $eligible = 0;
    }else{
        $qual = "'".str_replace(",", "','", $qualification)."'";
        $sql_jobs = "select * from tbl_jobs where status=2 and job_qualification in (".$qual.")";
        $result_jobs = mysqli_query($conn, $sql_jobs);
        $dataExistForQualification = mysqli_num_rows($result_jobs);
        if($dataExistForQualification==0){
            $qualificationNoMatch = 0;
        }
    }
}

if(isset($_GET['jobid'])){
    $job_details = 1;
    $job_id = $_GET['jobid'];
    $job_id = str_replace(' ', '+', $job_id);
    $job_id = decrypt($job_id);

    $sql = "select * from tbl_jobs where jobs_id='$job_id'";
    $result = mysqli_query($conn, $sql);
    while($row=mysqli_fetch_array($result)){
        $job_name = $row['job_name'];
        $job_desc = $row['job_description'];
        $job_post = $row['job_post'];
        $job_branch = $row['job_branch'];
        $job_vacancy = $row['job_vacancy_no'];
        $qualifi = $row['job_qualification'];
        $job_start_date = $row['job_start_date'];
        $job_end_date = $row['job_end_date'];
        $job_type = $row['job_type'];
        if($job_type==0){
            $jobType = "Full Time";
        }else{
            $jobType = "Part Time";
        }
        $employees = $row['company_employee_no'];
        $pay_range = $row['job_pay_range'];
        if($pay_range==0){
            $pay = "Pay range unavailable";
        }else{
            $pay = $pay_range;
        }
    }
}

if(isset($_GET['appjid'])){
    $job_details = 1;
    $job_id = $_GET['appjid'];
    $job_id = str_replace(' ', '+', $job_id);
    $job_id = decrypt($job_id);

    $sql = "select * from tbl_jobs where jobs_id='$job_id'";
    $result = mysqli_query($conn, $sql);
    while($row=mysqli_fetch_array($result)){
        $job_name = $row['job_name'];
        $job_desc = $row['job_description'];
        $job_post = $row['job_post'];
        $job_branch = $row['job_branch'];
        $job_vacancy = $row['job_vacancy_no'];
        $qualifi = $row['job_qualification'];
        $job_start_date = $row['job_start_date'];
        $job_end_date = $row['job_end_date'];
        $job_type = $row['job_type'];
        if($job_type==0){
            $jobType = "Full Time";
        }else{
            $jobType = "Part Time";
        }
        $employees = $row['company_employee_no'];
        $pay_range = $row['job_pay_range'];
        if($pay_range==0){
            $pay = "Pay range unavailable";
        }else{
            $pay = $pay_range;
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Jobs</title>
  <link rel="stylesheet" href="./assets/css/css/bootstrap.min.css">
  <link rel="shortcut icon" type="image/jpg" href="./assets/images/logo.png"/>
  <link rel="stylesheet" href="./assets/css/css/index.css">
  <script src="./assets/jquery-3.6.0.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body>

    <div class="container"><br><br><br>
      <div class="row">
        <?php

            if($job_details==0){
        ?>
            <table class="job_first_page">

                <?php                
                    if($eligible==1 && $qualificationNoMatch==1){
                    ?>
                        <tr>
                            <th>Company Name</th>
                            <th>Job Post</th>
                            <th>Start Date</th>
                        </tr>
                    <?php
                    while($result_row=mysqli_fetch_assoc($result_jobs)){
                        $job_ID = encrypt($result_row['jobs_id'])
                    ?>
                        <tr>
                            <td class="job_first_page_td">
                                <?php
                                $company_ID = $result_row['company_id'];
                                $company_name = "select uname from tbl_registration where regid='$company_ID'";
                                $company_result = mysqli_query($conn, $company_name);
                                while($company_row=mysqli_fetch_assoc($company_result)){
                                    echo $company_row['uname'];
                                }
                                ?>
                            </td>
                            <td class="job_first_page_td"><a href="./jobs?jobid=<?php echo $job_ID ?>"><u><?php echo $result_row['job_name'] ?></u></a></td>
                            <td class="job_first_page_td"><?php echo $result_row['job_start_date'] ?></td>
                        </tr>
                <?php
                    }
                }elseif($qualificationNoMatch==0 && $dataExistForQualification==0){
                ?>
                    <center><img style="width: 400px;" src="./assets/images/access_denied.png" alt=""></center><br>
                    <center><h4 style="color: red;">There are no jobs for your qualifications</h4></center>
                <?php
                }else{
                ?>
                    <!-- <center><img style="width: 400px;" src="./assets/images/access_denied.png" alt=""></center><br> -->
                    <center><h4 style="color: red;">Please fill the qualification details in your <a href="./mydetails.php?fo=<?php echo encrypt('#qualification'); ?>"><u style="color: blue;">account</u></a> page</h4></center>
                <?php
                }

                ?>

            </table>
        <?php
            }elseif($job_details==1){
        ?>
        <?php
            if($approvals==0){
            ?>
            <h3 class="scheme_head">Jobs</h3>
                <div id="row1" class="col-8" style="padding:20px; background-color: rgba(255, 238, 140, 0.7); border-top-left-radius: 10px; border-bottom-left-radius: 10px;">
                    <ul class="scheme_row_1">
                        <?php

                            echo "<b>".$job_name."</b><br><br>";
                            echo $job_desc;

                        ?>
                    </ul>
                    </div>

                    <div id="row2" class="col-4 scheme_row_2" style="padding:20px; background-color: rgba(252, 233, 128, 0.7); border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
                    <h3><b>Job Post</b></h3>
                    <p><?php echo $job_post ?></p>
                    <h3><b>Job Branch</b></h3>
                    <p><?php echo $job_branch?></p>
                    <h3><b>Qualification</b></h3>
                    <p><?php echo $qualifi ?></p>
                    <h3><b>Vacancy</b></h3>
                    <p><?php echo $job_vacancy?></p>
                    <h3><b>Interview Start Date</b></h3>
                    <p><?php echo $job_start_date ?></p>
                    <h3><b>interview End Date</b></h3>
                    <p><?php echo $job_end_date?></p>
                    <h3><b>Job Type</b></h3>
                    <p><?php echo $jobType?></p>
                    <h3><b>Number of Employees</b></h3>
                    <p><?php echo $employees ?></p>
                    <h3><b>Payrange</b></h3>
                    <p><?php echo $pay?></p>

                        <center><a href="./jobs" class="btn btn-outline-primary">Back</a></center>


                </div>
            <?php
            }else if($approvals==1){
            ?>

                <div id="row1" class="col-8" style="padding:20px; background-color: rgba(255, 238, 140, 0.7); border-top-left-radius: 10px; border-bottom-left-radius: 10px;">
                    <ul class="scheme_row_1">
                        <?php

                            echo "<b>".$job_name."</b><br><br>";
                            echo $job_desc;


                        ?>
                    </ul>
                    </div>

                    <div id="row2" class="col-4 scheme_row_2" style="padding:20px; background-color: rgba(252, 233, 128, 0.7); border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
                    <h3><b>Job Post</b></h3>
                    <p><?php echo $job_post ?></p>
                    <h3><b>Job Branch</b></h3>
                    <p><?php echo $job_branch?></p>
                    <h3><b>Qualification</b></h3>
                    <p><?php echo $qualifi ?></p>
                    <h3><b>Vacancy</b></h3>
                    <p><?php echo $job_vacancy?></p>
                    <h3><b>Interview Start Date</b></h3>
                    <p><?php echo $job_start_date ?></p>
                    <h3><b>interview End Date</b></h3>
                    <p><?php echo $job_end_date?></p>
                    <h3><b>Job Type</b></h3>
                    <p><?php echo $jobType?></p>
                    <h3><b>Number of Employees</b></h3>
                    <p><?php echo $employees ?></p>
                    <h3><b>Payrange</b></h3>
                    <p><?php echo $pay?></p>

                    <?php
                        if(isset($_GET['jobid'])){
                    ?>
                        <center><a href="./approvals?appj=<?php echo encrypt('approved_jobs') ?>" class="btn btn-outline-primary">Back</a></center>
                    <?php
                        }elseif(isset($_GET['appjid'])){
                    ?>
                        <center><a href="./approvals?japp=<?php echo encrypt('job_approvals') ?>" class="btn btn-outline-primary">Back</a></center>
                    <?php
                        }
                    ?>

                </div>

            <?php
            }
        ?>
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