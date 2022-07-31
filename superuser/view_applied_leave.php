<?php

require_once '../include/connect.php';

session_start();

if(!isset($_SESSION['superuser'])){
    header('Location: ./index');
    exit();
}


$sql = 'select * from tbl_applied_leaves where leave_status=0';
if(isset($_POST['filter_date'])){
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $sql = "select * from tbl_applied_leaves where applied_date<='$to_date' && applied_date>='$from_date' && leave_status=0";
}

    // if(isset($_POST['filter_exact_date'])){
    //     $to_date = $_POST['to_date'];
    //     $sql = "select * from tbl_applied_leaves where applied_date='$to_date' && leave_status=0";
    // }
    
if(isset($_POST['approve_id'])){
    $approve = $_POST['leave_id'];
    $approve_leave = "update tbl_applied_leaves set leave_status=1 where applied_leaves_id='$approve'";
    mysqli_query($conn,$approve_leave);
    header('Location: view_applied_leave?success');
}

$reject_reason = "NULL";

if(isset($_POST['reject_id'])){
    $reject = $_POST['leave_id'];
    $reject_reason = mysqli_real_escape_string($conn, $_POST['rejection_reason']);
    if($reject_reason!=''){
        $reject_leave = "update tbl_applied_leaves set leave_status=2, rejection_reason = '$reject_reason' where applied_leaves_id ='$reject'";
        mysqli_query($conn,$reject_leave);
        header('Location: view_applied_leave?reject');
    }else{
        header('Location: view_applied_leave?nodata');
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Leaves</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="./css/css/bootstrap.min.css">
    <script src="./css/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <script src="./css/jquery-3.6.0.min.js"></script>
</head>

<body>

    <div class="container-fluid">
        <?php
        require './sidebar.php';
        ?>
        <!-- Inser update delete slides -->

        <div class="details">
            
            <?php
                if(isset($_GET['success'])){
            ?>
                    <center>
                        <div class="alert-success">
                            Leave has been approved
                        </div>
                    </center>
            <?php
                }elseif(isset($_GET['reject'])){
            ?>
                    <center>
                        <div class="alert-primary">
                            Leave has been rejected
                        </div>
                    </center>
            <?php
                }elseif(isset($_GET['nodata'])){
            ?>
                    <center>
                        <div class="alert-info">
                            No rejection reason
                        </div>
                    </center>
            <?php
                }
            ?>

            <div style="float: right; width: 140px; padding: 10px; display: flex; justify-content: center;">
                <div class="progress-bar" role="progressbar"
                    style="width: 100%; background-color: #F9F2ED; color: black; padding:5px; border-radius: 8px;"
                    aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Staff</div>
                <div class="progress-bar" role="progressbar"
                    style="width: 100%; background-color: #D6D5A8; color: black; padding:5px; border-radius: 8px;"
                    aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Officer</div>
            </div>

            <!-- Filter -->

            <div style="float: right; width: 800px; padding: 10px; display: flex; justify-content: left;">
                <form method="POST">
                    From: &numsp;&numsp;&numsp;<input type="date" name="from_date" id="" required>&numsp;&numsp;&numsp;
                    To: &numsp;&numsp;&numsp;<input type="date" name="to_date" id="" required>
                    <input type="submit" name="filter_date" value="Go" class="btn btn-outline-success">
                </form>
            </div>
            <div>
                <a href="./approved_leave"><input type="submit" name="approved_leave" value="Approved Leave" class="btn btn-outline-success"></a> &numsp;&numsp;&numsp;
                <a href="./rejected_leave"><input type="submit" name="rejected_leave" value="Rejected Leave" class="btn btn-outline-danger"></a> &numsp;&numsp;&numsp;
            
            </div>

<div class="top-alert">
    <div class="cardHeader">
        <h2>View Applied Leaves</h2>
    </div>
        <table>
        <thead>
            <td><b>Name</b></td>
            <td><b>Applied Date</b></td>
            <td><b>Leave Type</b></td>
            <td><b>From Date</b></td>
            <td><b>To Date</b></td>
            <td><b>Leave Reason</b></td>
            <td><b>Rejection Reason</b></td>
            <td><b>Action</b></td>
        </thead>
    <tbody>
<?php
                                
function getName($reg_id, $conn){
    $name = "select uname from tbl_registration where regid='$reg_id'";
    $name_result = mysqli_query($conn, $name);
    $name_row = mysqli_fetch_assoc($name_result);
    $name_of_person = $name_row['uname'];
    return $name_of_person;
}

function getUserType($reg_id, $conn){
    $usertype = "select usertype from tbl_login where login_id='$reg_id'";
    $usertype_result = mysqli_query($conn, $usertype);
    $usertype_row = mysqli_fetch_assoc($usertype_result);
    $usertype_of_person = $usertype_row['usertype'];
    return $usertype_of_person;
}
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)){
        $usertype = getUserType($row['applied_leaves_reg_id'], $conn);

        echo "<tr style='background: #F9F2ED'>" ;
            $applied_leaves_id = $row['applied_leaves_id'];
            $name = getName($row['applied_leaves_reg_id'], $conn);
            echo "<td>" .$name. "</td>";  
            echo "<td>" .$row['applied_date']. "</td>";
            echo "<td>" .$row['leave_type']. "</td>";
            echo "<td>" .$row['from_date']. "</td>";
            echo "<td>" .$row['to_date']. "</td>";
            echo "<td>"  .$row['leave_reason']. "</td>";
            // echo "<td>"."<a href='view_applied_leave?approve_id=$applied_leaves_id'>"."<button class='btn btn-outline-success'>"."Approve"."</button>"."</a>"."</td>";
            // echo "<td>"."<a href='view_applied_leave?reject_id=$applied_leaves_id'>"."<button class='btn btn-outline-danger'>"."Reject"."</button>"."</a>"."</td>";
?>
            <form method='POST'>
                <td>
                    <input type="text" name='rejection_reason' placeholder='Rejection reason'>
                </td>
                <td>
                    <div style='display: flex; justify-content: space-around'>
                        <input type="hidden" name='leave_id' value=<?php echo $applied_leaves_id ?>>
                        <input type="submit" name='approve_id' value='Approve' class='btn btn-outline-success'>
                        <input type="submit" name='reject_id' value='Reject' class='btn btn-outline-danger'>
                    </div>
                </td>
            </form>
<?php
        echo "</tr>";

        // if($usertype==2){
        //     echo "<tr style='background: #F9F2ED'>" ;
        //     $applied_leaves_id = $row['applied_leaves_id'];
        //     $name = getName($row['applied_leaves_reg_id'], $conn);
        //     echo "<td>" .$name. "</td>";  
        //     echo "<td>" .$row['applied_date']. "</td>";
        //     echo "<td>" .$row['leave_type']. "</td>";
        //     echo "<td>" .$row['from_date']. "</td>";
        //     echo "<td>" .$row['to_date']. "</td>";
        //     echo "<td>"  .$row['leave_reason']. "</td>";
        //     echo "<td>"."<a href='view_applied_leave?approve_id=$applied_leaves_id'>"."<button class='btn btn-outline-success'>"."Approve"."</button>"."</a>"."</td>";
        //     echo "<td>"."<a href='view_applied_leave?reject_id=$applied_leaves_id'>"."<button class='btn btn-outline-danger'>"."Reject"."</button>"."</a>"."</td>";
        //     echo "</tr>";
        // }elseif($usertype==3){
        //     echo "<tr style='background: #D6D5A8'>" ;
        //     $applied_leaves_id = $row['applied_leaves_id'];
        //     $name = getName($row['applied_leaves_reg_id'], $conn);
        //     echo "<td>" .$name. "</td>";  
        //     echo "<td>" .$row['applied_date']. "</td>";
        //     echo "<td>" .$row['leave_type']. "</td>";
        //     echo "<td>" .$row['from_date']. "</td>";
        //     echo "<td>" .$row['to_date']. "</td>";
        //     echo "<td>" .$row['leave_reason']. "</td>";
            
            
        //     echo "<td>";
        //     echo "<form method='POST'>";
        //     echo "<table>";
            
            

        //     echo "<td>"."<input type='text'  placeholder='Rejection Reason' name='rejection_reason'>"."</td>";

          
            
        //     echo "</td>";

        //     echo "<td>"."<a href='view_applied_leave?approve_id=$applied_leaves_id'>"."<button class='btn btn-outline-success'>"."Approve"."</button>"."</a>"."</td>";
        //     echo "<td>"."<a href='view_applied_leave?reject_id=$applied_leaves_id'>"."<button class='btn btn-outline-danger'>"."Reject"."</button>"."</a>"."</td>";
        //     echo "</tr>";

        //     echo "</table>";
        //     echo "</form>";
        // }
 }
?>



</tbody>

</table>
</div>

</div>
</div>
</div>
</body>

</html>