<?php

require_once('./include/connect.php');
session_start();


if(!isset($_SESSION['regid'])){
    header('Location: index');
    exit();
}

$id = $_SESSION['regid'];

$scheme_id = [];
$statuses = [];
$dates = [];
$comments = [];
$i = 0;

$sql = "select * from tbl_user_schemes where reg_fid='$id'";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)){
    $scheme_id[$i] = $row['cscheme_id'];
    $statuses[$i] = $row['status'];
    $dates[$i] = $row['applied_date'];
    $comments[$i] = $row['comments'];
    $i = $i+1;
}

if(isset($_POST['re_submit'])){
    $reject_id = mysqli_real_escape_string($conn, $_POST['rejection_id']);
    $comment = "Scheme has been resubmitted";
    $approval = "update tbl_user_schemes set status=1, comments='$comment', approval_type=0 where cscheme_id='$reject_id'";
    if(mysqli_query($conn, $approval)){
        echo "<script>";
            echo "alert('Scheme has been re submitted')";
        echo "</script>";
    }else{
        mysqli_error($conn);
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

    <script src="./css/jquery-3.6.0.min.js"></script>
</head>

<body>

    <div class="container-fluid">
        <?php
        require './account_sidebar.php';
        ?><br><br>
        <div class="row cardBox">
            <div class="col-12">
                
                <center>
                    <table class="account_table">
                        <tr style="background-color: antiquewhite">
                            <th style="width: 400px; text-align: left;padding: 10px;">Applied Schemes</th>
                            <th style="width: 350px; text-align: left;padding: 10px;">Comments</th>
                            <th style="width: 130px; text-align: left;padding: 10px;">Dates</th>
                            <th style="width: 50px; text-align: left;padding: 10px;">Status</th>
                        </tr>
                        <?php
                            for($j=0; $j<count($scheme_id); $j++){
                                echo "<tr style='box-shadow: 0px 5px 8px 1px black'>";
                                    echo "<td>";
                                        $sql = "select * from tbl_cscheme where schemeid='$scheme_id[$j]'";
                                        $result = mysqli_query($conn, $sql);
                                        while($row = mysqli_fetch_assoc($result)){
                                            echo $row['subscheme'];
                                        }
                                        $status = $statuses[$j];
                                        if($status==100){
                                            echo "<h5 style='font-size:12px; color:red'>"."Please reupload the rejected document in My Documents tab, Once completed please click on the below button"."</h5>";
                                            echo "<form method='POST'>";
                                                echo "<input type='hidden' name='rejection_id' value='$scheme_id[$j]'/>"; 
                                                echo "<input type='submit' name='re_submit' value='Re-Submit' class='btn btn-outline-primary'>";
                                            echo "</form>";    
                                        }
                                    echo "<td>";
                                        $comment = $comments[$j];
                                        if($comment=='NULL'){
                                            echo "No comments yet!";
                                        }else{
                                            echo $comment;
                                        }
                                    echo "</td>";
                                    echo "<td>";

                                        echo $dates[$j];

                                    echo "</td>";
                                    echo "<td>";

                                        $status = $statuses[$j];
                                        if($status==1){
                                            echo "<button class='btn btn-warning'>"."Pending"."</button>";
                                        }else if($status==0){
                                            echo "<button class='btn btn-danger'>"."Rejected"."</button>";
                                        }else if($status==2){
                                            echo "<button class='btn btn-success'>"."Approved"."</button>";
                                        }else if($status==100){
                                            echo "<button class='btn btn-danger'>"."First Approval Rejected"."</button>";
                                        }else if($status==110){
                                            echo "<button class='btn btn-danger'>"."Second Approval Rejected"."</button>";
                                        }else if($status==10){
                                            echo "<button class='btn btn-success'>"."First Level Approved"."</button>";
                                        }else if($status==11){
                                            echo "<button class='btn btn-success'>"."Second Level Approved"."</button>";
                                        }
                                        
                                    echo "</td>";
                                echo "</tr>";
                            }
                        ?>

                    </table>
                </center>

            </div>
        </div>
    </div>

    </div>

    </div>

</body>

</html>

