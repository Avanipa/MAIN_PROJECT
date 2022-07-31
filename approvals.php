<?php

require './navbar.php';
include './mail_style.php';

if(!isset($_SESSION['regid'])){
    // header('Location: index');
    exit();
}
$approvals = 0;
$USERTYPE = $_SESSION['usertype'];
$userID = $_SESSION['regid'];
$appid = 0;

$name = "select uname from tbl_registration where regid='$userID'";
$name_result = mysqli_query($conn, $name);
$name_row = mysqli_fetch_assoc($name_result);

if(isset($_GET['sch'])){
    $approvals = 1;
}else if(isset($_GET['ser'])){
    $approvals = 2;
}elseif(isset($_GET['appsch'])){
    $approvals = 3;
}elseif(isset($_GET['appser'])){
    $approvals = 4;
}elseif(isset($_GET['japp'])){
    $approvals = 5;
}elseif(isset($_GET['appj'])){
    $approvals = 6;
}

// Scheme
$scheme_id = [];
$doc_fid = [];
$reg_fid = [];
$name_id = [];
$applied_date = [];


// Service 
$service_id = [];
$doc_id = [];
$reg_id = [];
$service_name_id = [];
$service_applied_date = [];

// Jobs
$company_id = [];
$post = [];
$job_id = [];
$submitted_on = [];

// checking privileges to approve
if($USERTYPE==2){
    $privilege = 0;
}elseif($USERTYPE==3){
    $privilege = 1;
}

if($privilege==0){
    $status = 1;
    $appoval_status = 10;
    $rejection_status = 100;
}elseif($privilege==1){
    $status = 10;
    $appoval_status = 11;
    $rejection_status = 110;
}


// /Schemes Query
$i = 0;
$sql = "select * from tbl_user_schemes where status='$status' and approval_type='$privilege'";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)){
    $scheme_id[$i] = $row['cscheme_id'];
    $doc_fid[$i] = $row['doc_fid'];
    $name_id[$i] = $row['reg_fid'];
    $appid = $row['reg_fid'];
    $reg_fid[$i] = $appid*1000+(9876543210-1234567890);
    $applied_date[$i] = $row['applied_date'];
    $i = $i+1;
}

// Company query
$i = 0;
$sql = "select * from tbl_jobs where status=1";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)){
    $company_id[$i] = $row['company_id'];
    $job_id[$i] = $row['jobs_id'];
    $post[$i] = $row['job_name'];
    $submitted_on[$i] = $row['submitted_on'];
    $i = $i+1;
}

// Services Query
$i = 0;
$sql = "select * from tbl_user_services where status='$status' and approval_type='$privilege'";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)){
    $service_id[$i] = $row['service_id'];
    $doc_id[$i] = $row['doc_id'];
    $service_name_id[$i] = $row['reg_id'];
    $appid = $row['reg_id'];
    $reg_id[$i] = $appid*1000+(9876543210-1234567890);
    $service_applied_date[$i] = $row['applied_date'];
    $i = $i+1;
}
if(isset($_GET['sertag'])){
    $approval_id = $_GET['sertag'];
    $approval_id = str_replace(' ', '+', $approval_id);
    $approval_id = decrypt($approval_id);
    $approval = "update tbl_user_services set status='$appoval_status', approval_type=1 where service_id='$approval_id'";
    if(mysqli_query($conn, $approval)){
        echo "<script>";
            echo "alert('Service has been approved')";
        echo "</script>";
    }else{
        mysqli_error($conn);
    }

}

if(isset($_POST['reject_service'])){
    $rejection_reason_service = mysqli_real_escape_string($conn, $_POST['rejection_reason_service']);
    $rejection_id_service = mysqli_real_escape_string($conn, $_POST['rejection_id_service']);

    $rejection = "update tbl_user_services set status='$rejection_status', comments='$rejection_reason_service' where service_id='$rejection_id_service'";
    if(mysqli_query($conn, $rejection)){
        echo "<script>";
            echo "alert('Service has been rejected')";
        echo "</script>";
    }else{
        mysqli_error($conn);
    }
    
}

// Approvals
if(isset($_GET['atag'])){
    $approval_id = $_GET['atag'];
    $approval_id = str_replace(' ', '+', $approval_id);
    $approval_id = decrypt($approval_id);
    $approval = "update tbl_user_schemes set status='$appoval_status', approval_type=1 where cscheme_id='$approval_id'";
    if(mysqli_query($conn, $approval)){
        echo "<script>";
            echo "alert('Scheme has been approved')";
        echo "</script>";
        echo("<script>location.href = './approvals?sch=$secret_data'</script>");
    }else{
        mysqli_error($conn);
    }

}

// job Approval
// Job Status: 1=Pending, 2 = Approved, 0 = Rejected
if(isset($_GET['jatag'])){
    $approval_id = $_GET['jatag'];
    $approval_id = str_replace(' ', '+', $approval_id);
    $approval_id = decrypt($approval_id);
    $approved_by = $name_row['uname'];
    $approved_on = date("Y-m-d");
    $approval = "update tbl_jobs set status=2, approved_by='$approved_by', approved_on='$approved_on' where jobs_id='$approval_id'";
    if(mysqli_query($conn, $approval)){
        echo "<script>";
            echo "alert('Job has been approved')";
        echo "</script>";
    }else{
        mysqli_error($conn);
    }
}
// JOB REJECT
if(isset($_POST['job_reject'])){
    $rejection_reason = mysqli_real_escape_string($conn, $_POST['job_rejection_reason']);
    $reject_id = mysqli_real_escape_string($conn, $_POST['job_rejection_id']);

    $approval = "update tbl_jobs set status=0, rejection_reason='$rejection_reason' where jobs_id='$reject_id'";
    if(mysqli_query($conn, $approval)){
        echo "<script>";
            echo "alert('Job has been rejected')";
        echo "</script>";
    }else{
        mysqli_error($conn);
    }
}

// On Click of Approval Button for sending mails
if(isset($_POST['sch_approve'])){
    $tag = $_POST['tag'];
    $atag = $_POST['atag'];
    $sch_applied_date = date("Y-m-d");    
    $sch_more_details = $_POST['more_details'];
    $sch_regID = $_POST['regid'];
    $sch_name = $_POST['name'];
    $sch_sql = "select emailid from tbl_login where login_id='$sch_regID'";
    $sch_result = mysqli_query($conn, $sch_sql);
    $sch_email_result = mysqli_fetch_assoc($sch_result);
    $sch_email = $sch_email_result['emailid'];
    $rejection_reason = 0;

    send_email_status($sch_email, $sch_name, $sch_more_details, $sch_applied_date, $rejection_reason);
    echo("<script>location.href = './approvals?tag=$tag&atag=$atag'</script>");
}

// On Click of Reject Button for sending mails
if(isset($_POST['sch_reject'])){
    $rejection_reason = mysqli_real_escape_string($conn, $_POST['rejection_reason']);
    $reject_id = mysqli_real_escape_string($conn, $_POST['rejection_id']);

    $sch_applied_date = date("Y-m-d");  
    $sch_more_details = $_POST['more_details'];
    $sch_scheme_status = 'staff_reject';
    $sch_regID = $_POST['regid'];
    $sch_name = $_POST['name'];
    $sch_sql = "select emailid from tbl_login where login_id='$sch_regID'";
    $sch_result = mysqli_query($conn, $sch_sql);
    $sch_email_result = mysqli_fetch_assoc($sch_result);
    $sch_email = $sch_email_result['emailid'];

    send_email_status($sch_email, $sch_name, $sch_more_details, $sch_applied_date, $rejection_reason);

    $approval = "update tbl_user_schemes set status='$rejection_status', approval_type='$privilege', comments='$rejection_reason' where cscheme_id='$reject_id'";
    if(mysqli_query($conn, $approval)){
        echo "<script>";
            echo "alert('Scheme has been rejected')";
        echo "</script>";
        $secret_data = encrypt('approvals');
        echo("<script>location.href = './approvals?sch=$secret_data'</script>");
    }else{
        mysqli_error($conn);
    }
    
}


?>

<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>
  <?php

    if($approvals==1){
        echo "Scheme Approvals";
    }elseif($approvals==2){
        echo "Service Approvals";
    }else{
        echo "Approvals";
    }

  ?>
  </title>
  <link rel="stylesheet" href="./assets/css/css/bootstrap.min.css">
  <link rel="shortcut icon" type="image/jpg" href="./assets/images/logo.png"/>
  <link rel="stylesheet" href="./assets/css/css/index.css">
  <link rel="stylesheet" href="./assets/css/css/approval.css">
  <script src="./assets/jquery-3.6.0.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body>

    <?php
        if($approvals==0){
    ?>
        <div class="container" style="margin-top: 50px;">
            <div class="row">
                <div class="col-6">
                    <a href="./approvals?sch=<?php echo encrypt('approvals') ?>" ><button class="btn btn-outline-primary">Scheme Approvals</button></a><br><br>
                    <a href="./approvals?appsch=<?php echo encrypt('approved') ?>" ><button class="btn btn-outline-primary">Approved Schemes</button></a>
                </div>
                <div class="col-6">
                <a href="./approvals?ser=<?php echo encrypt('service_approvals') ?>" ><button class="btn btn-outline-primary">Service Approvals</button></a><br><br>
                <a href="./approvals?appser=<?php echo encrypt('service_approved') ?>" ><button class="btn btn-outline-primary">Approved Services</button></a>
                </div>
            </div><br>

            <?php
                if($USERTYPE==3){
            ?>
                    <div class="row">
                        <div class="col-6">
                            <a href="./approvals?japp=<?php echo encrypt('job_approvals') ?>" ><button class="btn btn-outline-primary">Job Approvals</button></a><br><br>
                        </div>
                        <div class="col-6">
                        <a href="./approvals?appj=<?php echo encrypt('approved_jobs') ?>" ><button class="btn btn-outline-primary">Approved Jobs</button></a><br><br>
                        </div>
                    </div>
            <?php
                }
            ?>

        </div> 
    <?php
        }else if($approvals==1){
    ?>
            <div class="container">
                <table class="approval_scheme_table">
                    <h4  style="margin-top: 50px;" class="text-center"><b>Scheme Approvals</b></h4>
                    <tr style="background-color: bisque;">
                        <th>Applicant ID</th>
                        <th>Name</th>
                        <th>Scheme</th>
                        <th>Documents</th>
                        <th>Submitted On</th>
                        <th>Approve</th>
                        <th>Reject</th>
                    </tr>
                    <?php
                        if(!empty($scheme_id)){
                            for($j=0; $j<count($scheme_id); $j++){
                                echo "<tr>";
                                    echo "<td>".$reg_fid[$j]."</td>";

                                    $more_details = [];

                                    echo "<td>";
                                        $name = "select uname from tbl_registration where regid='$name_id[$j]'";
                                        $result_name = mysqli_query($conn, $name);
                                        while($row=mysqli_fetch_assoc($result_name)){
                                            $regid = encrypt($name_id[$j]);
                                            echo "<a href='viewuser?id=$regid&sch' style='color:blue'>";
                                                echo $row['uname'];
                                            echo "</a>";
                                        }
                                    echo "</td>";
    
                                    echo "<td>";
                                        $scheme_name = "select name, subscheme from tbl_cscheme where schemeid='$scheme_id[$j]'";
                                        $result_scheme_name = mysqli_query($conn, $scheme_name);
                                        while($row=mysqli_fetch_assoc($result_scheme_name)){
                                            $enc = encrypt($scheme_id[$j]);
                                            echo "<a href='./schemes?tags=$enc'>";
                                                echo "<u>".$row['subscheme']."</u>";
                                                $more_details[0] = $row['name'];
                                                $more_details[1] = $row['subscheme'];
                                            echo "</a>";
                                        }
                                    echo "</td>";
    
                                    echo "<td>";
                                    $id = encrypt($scheme_id[$j]);
                                    $regid = encrypt($name_id[$j]);
                                        echo "<a href='download?id=$id&ids=$regid' style='color:blue'>";
                                            echo "Download";
                                        echo "</a>";
                                    echo "</td>";
    
                                    echo "<td>";
                                        echo $applied_date[$j];
                                    echo "</td>";
    
                                    echo "<td>";
                                        $enc = encrypt($scheme_id[$j]);
                                        $app = encrypt('approvals');
                                        ?>
                                            <form action="" method="POST">
                                                <input type="submit" name='sch_approve' class='btn btn-outline-success' value='Approve'>
                                                <input type="hidden" name='tag' value=<?php echo $app ?>>
                                                <input type="hidden" name='atag' value=<?php echo $enc ?>>
                                                <input type="hidden" name='regid' value=<?php echo $name_id[$j] ?>>
                                                <input type="hidden" name='name' value=<?php echo $reg_fid[$j] ?>>
                                                <?php
                                                    foreach($more_details as $value){
                                                        echo '<input type="hidden" name="more_details[]" value="'. $value. '">';
                                                    }
                                                ?>
                                            </form>
                                        <?php
                                        echo "</a>";
                                    echo "</td>";
    
                                    echo "<td>";
                                        echo "<form method='POST'>";
                                            echo "<table>";
                                                echo "<tr>";
                                                echo "<td>";
                                                    echo "<input type='text' name='rejection_reason' placeholder='Rejection Reason' required>";
                                                echo "</td>";
                                                echo "<td>";
                                                    echo "<input type='hidden' name='rejection_id' value='$scheme_id[$j]'/>"; 
                                                    echo "<input type='hidden' name='regid' value=$name_id[$j]>";
                                                    echo "<input type='hidden' name='name' value=$reg_fid[$j]>";
                                                    foreach($more_details as $value){
                                                        echo '<input type="hidden" name="more_details[]" value="'. $value. '">';
                                                    }
                                                    echo "<input type='submit' name='sch_reject' value='Reject' class='btn btn-outline-danger'>";
                                                echo "</td>";
                                                echo "</tr>";
                                            echo "</table>";
                                        echo "</form>";                                        
                                    echo "</td>";
    
                                echo "</tr>";
                            }
                        }else{
                            echo "<h5 class='text-center'>";
                                echo "No new entries";
                            echo "</h5>";
                        }
                    ?>
                </table>
            </div>
    <?php
        }else if($approvals==2){
    ?>

            <div class="container">
                <table class="approval_scheme_table">
                    <h4  style="margin-top: 50px;" class="text-center"><b>Service Approvals</b></h4>
                    <tr style="background-color: bisque;">
                        <th>Applicant ID</th>
                        <th>Name</th>
                        <th>Service</th>
                        <th>Documents</th>
                        <th>Submitted On</th>
                        <th>Approve</th>
                        <th>Reject</th>
                    </tr>
                    <?php
                        if(!empty($service_id)){
                            for($j=0; $j<count($service_id); $j++){
                                echo "<tr>";
                                    echo "<td>".$reg_id[$j]."</td>";

                                    echo "<td>";
                                        $name = "select uname from tbl_registration where regid='$service_name_id[$j]'";
                                        $result_name = mysqli_query($conn, $name);
                                        while($row=mysqli_fetch_assoc($result_name)){
                                            $regid = encrypt($name_id[$j]);
                                            echo "<a href='viewuser?id=$regid&ser' style='color:blue'>";
                                                echo $row['uname'];
                                            echo "</a>";
                                        }
                                    echo "</td>";
    
                                    echo "<td>";
                                        $service_name = "select subservice from tbl_services where serviceid='$service_id[$j]'";
                                        $result_service_name = mysqli_query($conn, $service_name);
                                        while($row=mysqli_fetch_assoc($result_service_name)){
                                            $enc = encrypt($service_id[$j]);
                                            echo "<a href='./services?tags=$enc'>";
                                                echo "<u>".$row['subservice']."</u>";
                                            echo "</a>";
                                        }
                                    echo "</td>";
    
                                    echo "<td>";
                                    $id = encrypt($service_id[$j]);
                                    $regid = encrypt($service_name_id[$j]);
                                        echo "<a href='download?serid=$id&serids=$regid' style='color:blue'>";
                                            echo "Download";
                                        echo "</a>";
                                    echo "</td>";
    
                                    echo "<td>";
                                        echo $applied_date[$j];
                                    echo "</td>";
    
                                    echo "<td>";
                                        $enc = encrypt($service_id[$j]);
                                        $app = encrypt('approvals');
                                        echo "<a href='./approvals?tag=$app&sertag=$enc' style='color:blue'>";
                                            echo "<button class='btn btn-outline-success'>"."Approve"."</button>";
                                        echo "</a>";
                                    echo "</td>";
    
                                    echo "<td>";
                                        echo "<form method='POST'>";
                                            echo "<table>";
                                                echo "<tr>";
                                                echo "<td>";
                                                    echo "<input type='text' name='rejection_reason_service' placeholder='Rejection Reason' required>";
                                                echo "</td>";
                                                echo "<td>";
                                                    echo "<input type='hidden' name='rejection_id_service' value='$service_id[$j]'/>"; 
                                                    echo "<input type='submit' name='reject_service' value='Reject' class='btn btn-outline-danger'>";
                                                echo "</td>";
                                                echo "</tr>";
                                            echo "</table>";
                                        echo "</form>";                                        
                                    echo "</td>";
    
                                echo "</tr>";
                            }
                        }else{
                            echo "<h5 class='text-center'>";
                                echo "No new entries";
                            echo "</h5>";
                        }
                    ?>
                </table>
            </div>

    <?php
        }elseif($approvals==3){
            // Schemes Approved Query
            $schapprovals = "select * from tbl_user_schemes where approval_type='$privilege'";
            $schapprovals_result = mysqli_query($conn, $schapprovals);
            

    ?>
        <div class="container">
            <table class="approval_scheme_table" style="width: 100%;">
                <h4  style="margin-top: 50px;" class="text-center"><b>Approved Schemes</b></h4><br>
                <tr style="background-color: bisque; width: 100%;">
                    <th style="width: 20%;">Applicant ID</th>
                    <th>Name</th>
                    <th>Scheme</th>
                    <th>Status</th>
                </tr>
                <?php
                while($schapprovals_row = mysqli_fetch_assoc($schapprovals_result)){
                    echo "<tr>";
                        $reg_fid = $schapprovals_row['reg_fid'];
                        $id = $schapprovals_row['reg_fid'];
                        $schemeID = $schapprovals_row['cscheme_id'];
                        $reg_fid = $appid*1000+(9876543210-1234567890);

                        echo "<td>".$reg_fid."</td>";

                        $sql = "select uname from tbl_registration where regid='$id'";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $regid = encrypt($id);
                        echo "<td>";
                            echo "<a href='viewuser?id=$regid&appsch' style='color:blue'>";
                                echo $row['uname'];
                            echo "</a>";
                        echo "</td>";

                        $sql = "select subscheme from tbl_cscheme where schemeid='$schemeID'";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        echo "<td>";
                            $enc = encrypt($schemeID);
                            echo "<a href='./schemes?tags=$enc'>";
                                echo "<u>".$row['subscheme']."</u>";
                            echo "</a>";
                        echo "</td>";

                            $sql = "select status from tbl_user_schemes where cscheme_id='$schemeID'";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            $scheme_status = "";
                            if($row['status']==1){
                                $scheme_status = "<button class='btn btn-primary'>"."Pending"."</button>";    
                            }else if($row['status']==0){
                                $scheme_status = "<button class='btn btn-danger'>"."Rejected"."</button>";;    
                            }else if($row['status']==2){
                                $scheme_status = "<button class='btn btn-success'>"."Approved"."</button>";    
                            }else if($row['status']==100){
                                $scheme_status = "<button class='btn btn-danger'>"."First Approval Rejected"."</button>";
                            }else if($row['status']==110){
                                $scheme_status = "<button class='btn btn-danger'>"."Second Approval Rejected"."</button>";
                            }else if($row['status']==10){
                                $scheme_status = "<button class='btn btn-success'>"."First Level Approved"."</button>";
                            }else if($row['status']==11){
                                $scheme_status = "<button class='btn btn-success'>"."Second Level Approved"."</button>";
                            }
                        echo "<td>".$scheme_status."</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    <?php
        }elseif($approvals==4){
            // Schemes Approved Query
            $serapprovals = "select * from tbl_user_services where approval_type='$privilege'";
            $serapprovals_result = mysqli_query($conn, $serapprovals);
            

    ?>
        <div class="container">
            <table class="approval_scheme_table" style="width: 100%;">
                <h4  style="margin-top: 50px;" class="text-center"><b>Approved Services</b></h4><br>
                <tr style="background-color: bisque; width: 100%;">
                    <th style="width: 20%;">Applicant ID</th>
                    <th>Name</th>
                    <th>Scheme</th>
                    <th>Status</th>
                </tr>
                <?php
                while($serapprovals_row = mysqli_fetch_assoc($serapprovals_result)){
                    echo "<tr>";
                        $reg_fid = $serapprovals_row['reg_id'];
                        $id = $serapprovals_row['reg_id'];
                        $serviceID = $serapprovals_row['service_id'];
                        $reg_fid = $appid*1000+(9876543210-1234567890);

                        echo "<td>".$reg_fid."</td>";

                        $sql = "select uname from tbl_registration where regid='$id'";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $regid = encrypt($id);
                        echo "<td>";
                            echo "<a href='viewuser?id=$regid&appser' style='color:blue'>";
                                echo $row['uname'];
                            echo "</a>";
                        echo "</td>";

                        $sql = "select subservice from tbl_services where serviceid='$serviceID'";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        echo "<td>";
                            $enc = encrypt($serviceID);
                            echo "<a href='./services?tags=$enc'>";
                                echo "<u>".$row['subservice']."</u>";
                            echo "</a>";
                        echo "</td>";

                            $sql = "select status from tbl_user_services where service_id='$serviceID'";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            $scheme_status = "";
                            if($row['status']==1){
                                $scheme_status = "<button class='btn btn-primary'>"."Pending"."</button>";    
                            }else if($row['status']==0){
                                $scheme_status = "<button class='btn btn-danger'>"."Rejected"."</button>";;    
                            }else if($row['status']==2){
                                $scheme_status = "<button class='btn btn-success'>"."Approved"."</button>";    
                            }else if($row['status']==100){
                                $scheme_status = "<button class='btn btn-danger'>"."First Approval Rejected"."</button>";
                            }else if($row['status']==110){
                                $scheme_status = "<button class='btn btn-danger'>"."Second Approval Rejected"."</button>";
                            }else if($row['status']=10){
                                $scheme_status = "<button class='btn btn-success'>"."First Level Approved"."</button>";
                            }else if($row['status']==11){
                                $scheme_status = "<button class='btn btn-success'>"."Second Level Approved"."</button>";
                            }
                        echo "<td>".$scheme_status."</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    <?php
        }else if($approvals==5){
            ?>
        
                    <div class="container">
                        <table class="approval_scheme_table">
                            <h4  style="margin-top: 50px;" class="text-center"><b>Job Approvals</b></h4>
                            <tr style="background-color: bisque;">
                                <th>Company Name</th>
                                <th>Post</th>
                                <th>Submitted On</th>
                                <th>Approve</th>
                                <th>Reject</th>
                            </tr>

                            <tr>
                                <?php
                                    for($j=0; $j<sizeof($company_id); $j++){
                                        $name = "select uname from tbl_registration where regid='$company_id[$j]'";
                                        $name_result = mysqli_query($conn, $name);
                                        $name_row = mysqli_fetch_assoc($name_result);

                                        echo "<td>".$name_row['uname']."</td>";

                                        echo "<td>";
                                        $enc = encrypt($job_id[$j]);
                                            echo "<a href='./jobs?appjid=$enc'>";
                                                echo "<u>".$post[$j]."</u>";
                                            echo "</a>";
                                        echo "</td>";
                                        
                                        echo "<td>".$submitted_on[$j]."</td>";

                                        echo "<td>";
                                            $enc = encrypt("job_approvals");
                                            $jobid = encrypt($job_id[$j]);
                                            echo "<a href='./approvals?japp=$jobid&jatag=$jobid' style='color:blue'>";
                                                echo "<button class='btn btn-outline-success'>"."Approve"."</button>";
                                            echo "</a>";
                                        echo "</td>";

                                        echo "<td>";
                                            echo "<form method='POST'>";
                                                echo "<table>";
                                                    echo "<tr>";
                                                    echo "<td>";
                                                        echo "<input type='text' name='job_rejection_reason' placeholder='Rejection Reason' required>";
                                                    echo "</td>";
                                                    echo "<td>";
                                                        echo "<input type='hidden' name='job_rejection_id' value='$job_id[$j]'/>"; 
                                                        echo "<input type='submit' name='job_reject' value='Reject' class='btn btn-outline-danger'>";
                                                    echo "</td>";
                                                    echo "</tr>";
                                                echo "</table>";
                                            echo "</form>";
                                        echo "</td>";
                                    }
                                ?>
                            </tr>
                            
                        </table>
                    </div>
        
            <?php
                }elseif($approvals==6){
                    // Schemes Approved Query
                    $approved_jobs = "select * from tbl_jobs where status=2";
                    $approved_jobs_result = mysqli_query($conn, $approved_jobs);
                    $jobs_id = [];
                    $company_ID = [];
                    $i = 0;
                    while($approved_jobs_row = mysqli_fetch_assoc($approved_jobs_result)){
                        $jobs_id[$i] = $approved_jobs_row['jobs_id'];
                        $company_ID[$i] = $approved_jobs_row['company_id'];
                        $i = $i+1;
                    }
        
            ?>
                <div class="container">
                    <table class="approval_scheme_table" style="width: 100%;">
                        <h4  style="margin-top: 50px;" class="text-center"><b>Approved Jobs</b></h4><br>
                        <tr style="background-color: bisque; width: 100%;">
                            <th style="width: 20%;">Company Name</th>
                            <th>Job Name</th>
                            <th>Job Post</th>
                            <th>Submitted On</th>
                            <th>Approved On</th>
                            <th>Approved By</th>
                        </tr>
                        <?php
                            for($j=0; $j<sizeof($jobs_id); $j++){
                                echo "<tr>";
                                    $company_name = "select uname from tbl_registration where regid='$company_ID[$j]'";
                                    $company_name_result = mysqli_query($conn, $company_name);
                                    $company_name_row = mysqli_fetch_assoc($company_name_result);
                                    echo "<td>".$company_name_row['uname']."</td>";

                                    $job_details = "select * from tbl_jobs where jobs_id='$jobs_id[$j]'";
                                    $job_details_result = mysqli_query($conn, $job_details);
                                    $job_details_row = mysqli_fetch_assoc($job_details_result);

                                    echo "<td>";
                                        $enc = encrypt($jobs_id[$j]);
                                        echo "<a href='./jobs?jobid=$enc'>";
                                            echo "<u>".$job_details_row['job_name']."</u>";
                                        echo "</a>";
                                    echo "</td>";

                                    echo "<td>".$job_details_row['job_post']."</td>";
                                    
                                    echo "<td>".$job_details_row['submitted_on']."</td>";

                                    echo "<td>".$job_details_row['approved_on']."</td>";

                                    echo "<td>".$job_details_row['approved_by']."</td>";

                                echo "</tr>";
                            }
                        ?>
                    </table>
                </div>
            <?php
                }
    ?>

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