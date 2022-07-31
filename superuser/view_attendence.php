<?php

    require_once '../include/connect.php';

    session_start();

    if(!isset($_SESSION['superuser'])){
        header('Location: ./index.php');
        exit();
    }

    $sql = 'select * from tbl_attendence';

    if(isset($_POST['filter_date'])){
        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];
        $sql = "select * from tbl_attendence where attend_date<='$to_date' && attend_date>='$from_date'";
    }

    if(isset($_POST['filter_exact_date'])){
        $to_date = $_POST['to_date'];
        $sql = "select * from tbl_attendence where attend_date='$to_date'";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Attendence</title>
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

    <div style="float: right; width: 140px; padding: 10px; display: flex; justify-content: center;">
    <div class="progress-bar" role="progressbar" style="width: 100%; background-color: #F9F2ED; color: black; padding:5px; border-radius: 8px;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Staff</div>
    <div class="progress-bar" role="progressbar" style="width: 100%; background-color: #D6D5A8; color: black; padding:5px; border-radius: 8px;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Officer</div>
</div>

                <!-- Filter -->

<div style="float: right; width: 800px; padding: 10px; display: flex; justify-content: left;">
    <form method="POST">
        From: &numsp;&numsp;&numsp;<input type="date" name="from_date" id="" required>&numsp;&numsp;&numsp;
    To: &numsp;&numsp;&numsp;<input type="date" name="to_date" id="" required>
<input type="submit" name="filter_date" value="Go" class="btn btn-outline-success">
                    
</form>
                
</div>

<div style="float: right; width: 800px; padding: 10px; display: flex; justify-content: left;">
                   
<form method="POST">

To: &numsp;&numsp;&numsp;<input type="date" name="to_date" id="" required>

<input type="submit" name="filter_exact_date" value="Go" class="btn btn-outline-success">

</form>
                </div>

                <!-- Update button and choose file -->


                
                
<div class="top-alert">
                    
<div class="cardHeader">
                    
<h2>View Attendence</h2>
                    </div>
                   
         <table>
            <thead>
                           
            <td><b>Name</b></td>
            
            <td><b>Time In</b></td>
            
            <td><b>Time Out</b></td>
            
            <td><b>Date</b></td>
            
            <td><b>Day</b></td>
            
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
        $usertype = getUserType($row['attendence_reg_id'], $conn);
            if($usertype==2){
            echo "<tr style='background: #F9F2ED'>" ;
            $name = getName($row['attendence_reg_id'], $conn);
            echo "<td>" .$name. "</td>";  
            echo "<td>" .$row['time_in']. "</td>";
            echo "<td>" .$row['time_out']. "</td>";
            echo "<td>" .$row['attend_date']. "</td>";
            echo "<td>" .$row['full_half_day']. "</td>";
            echo "</tr>";
}elseif($usertype==3){
            echo "<tr style='background: #D6D5A8'>" ;
            $name = getName($row['attendence_reg_id'], $conn);
            echo "<td>" .$name. "</td>";  
            echo "<td>" .$row['time_in']. "</td>";
            echo "<td>" .$row['time_out']. "</td>";
            echo "<td>" .$row['attend_date']. "</td>";
            echo "<td>" .$row['full_half_day']. "</td>";
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

</html>