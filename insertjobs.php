<?php

ob_start(); 

require './navbar.php';
if(!isset($_SESSION['regid'])){
  header('Location: index');
  exit();
}

$id = $_SESSION['regid'];

if(isset($_POST['submit'])){
  $job_name = mysqli_real_escape_string($conn, $_POST['job_name']);
  $job_description = mysqli_real_escape_string($conn, $_POST['job_description']);
  $job_post = mysqli_real_escape_string($conn, $_POST['job_post']);
  $job_branch = mysqli_real_escape_string($conn, $_POST['job_branch']);
  $job_vacancy_no = mysqli_real_escape_string($conn, $_POST['job_vacancy_no']);
  $job_qualification = $_POST['job_qualification'];
  $job_qualification = implode(',', $job_qualification);
  $job_start_date = mysqli_real_escape_string($conn, $_POST['job_start_date']);
  $job_end_date = mysqli_real_escape_string($conn, $_POST['job_end_date']);
  $status = 1;
  $submitted_on = date("Y-m-d");
  $approved_on = date("Y-m-d");
  $job_type = mysqli_real_escape_string($conn, $_POST['job_type']);
  $company_employee_no = mysqli_real_escape_string($conn, $_POST['company_employee_no']);
  $job_pay_range = mysqli_real_escape_string($conn, $_POST['job_pay_range']);

  $sql = "insert into tbl_jobs (company_id, job_name, job_description, job_post, job_branch, job_vacancy_no, job_qualification, submitted_on, job_start_date, job_end_date, status, job_type, company_employee_no, job_pay_range, approved_by, approved_on, rejection_reason)
  values('$id', '$job_name', '$job_description', '$job_post', '$job_branch', '$job_vacancy_no', '$job_qualification', '$submitted_on', '$job_start_date', '$job_end_date', '$status', '$job_type', '$company_employee_no', '$job_pay_range', 'NULL', '$approved_on', 'NULL')";
  $result = mysqli_query($conn, $sql);
  if($result){
    header('Location: ./insertjobs?success');
  }else{
    echo mysqli_error($conn);
  }

}

?>

<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Insert Jobs</title>
  <link rel="stylesheet" href="./assets/css/css/bootstrap.min.css">
  <link rel="shortcut icon" type="image/jpg" href="./assets/images/logo.png"/>
  <link rel="stylesheet" href="./assets/css/css/index.css">
  <script src="./assets/jquery-3.6.0.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">

   <!-- *********************************************SELECT MULTIPLE LIBRARIES -->

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
</head>

<body>

  <div class="container">
    <div class="inputstyles">
      <div class="row">
        <div class="col-2"></div>
          <div class="col-8" style="background-color: white; box-shadow: 0 0 40px 1px rgb(243, 194, 194); border-radius: 10px; padding: 20px;">
          <?php
            if(isset($_GET['success'])){
          ?>
              <div class="alert-success text-center">New job inserted successfully</div>
          <?php
            }
          ?>
            <center>
              <form id="job_form" method="POST">
                <input type="text" name="job_name" placeholder="Job Name" required><br>
                <textarea rows = "10" cols = "60" name="job_description" class="service_desc" placeholder="Job Description" required></textarea><br>
                <input type="text" name="job_post" placeholder="Job Post" required><br>
                <input type="text" name="job_branch" placeholder="Company Branch" required><br>
                <input type="number" name="job_vacancy_no" placeholder="Vaccancy Available" required><br>
  
                <select class="mul-select" name="job_qualification[]" id="qualification" multiple="true">
                  <option value="tenth">SSLC</option>
                    <option value="degreeUG">Degree</option>
                    <option value="degreePG">PG</option>
                    <option value="diploma">Diploma</option>
                    <option value="it">IT</option>
                </select><br><br>
                <div id="qualification_error" style="display: none; padding: 0; margin:0;color: red;">
                  *Please select a qualification<br>
                </div>
  
                <div class="dob_style" style="float: left;">
                  <h6 style="display: inline;">Start date</h6>
                  <input style="display: inline;" type="date" name="job_start_date" class="inp px-3" value="Date" placeholder="Start date" required>
                </div>
  
                <div class="dob_style">
                  <h6 style="display: inline;">End Date</h6>
                  <input style="display: inline;" type="date" name="job_end_date" class="inp px-3" value="Date" placeholder="Apply Before" required>
                </div>
            
                <select class="job_type" id="job_type" name="job_type">
                  <option value="NULL">Job Type</option>
                  <option value="0">Full time</option>
                  <option value="1">Part time</option>
                </select><br><br>
                <div id="job_type_error" style="display: none; padding: 0; margin:0;color: red;">
                  *Please select a valid job type<br>
                </div>
                <input type="number" name="company_employee_no" placeholder="Employee Number" required><br>
                <input type="number" name="job_pay_range" placeholder="Pay Range" required><br>
            
                <!-- Script -->
                <script>
                    $(document).ready(function(){
                        $(".mul-select").select2({
                            placeholder: " Qualification", //placeholder
                            tags: true,
                            tokenSeparators: ['/',',',';'," "] 
                        });
                    })
                </script>
                <!-- Script End -->
               
                <input class="btn btn-outline-info" type="submit" name="submit" id="" value="Insert" required>
              </form>
            </center>
          </div>
        <div class="col-2"></div>
      </div>
    </div>
  </div>
    
    <?php include 'footer.php' ?>

</body>

<script>

  $(document).ready(function () {

    $("#job_form").submit(function (e) {
      var job_type = $("#job_type").val();
      var qualification = $('#qualification').val();

      if(qualification==null){
        $('#qualification_error').css("display", "block")
        e.preventDefault(e);
      }else{
        $('#job_type_error').css("display", "none")
      }

      if(job_type=='NULL'){
        $('#job_type_error').css("display", "block")
        e.preventDefault(e);
      }else{
        $('#job_type_error').css("display", "none")
      }

    });

      if ($(window).width() < 991) {
        $("#row1").attr('class', 'col-12');
        $("#row2").attr('class', 'col-12');
      }
  });

</script>

<?php
  ob_flush();
?>

</html>