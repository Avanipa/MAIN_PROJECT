<?php
require_once('./include/connect.php');
session_start();

$id=$_SESSION['regid'];

$sql = "SELECT  leave_type, applied_date, from_date, to_date, leave_reason, leave_status FROM tbl_applied_leaves WHERE leave_status=1";

$result = mysqli_query($conn,$sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Leave History</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./assets/css/css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/jpg" href="./assets/images/logo.png" />
    <link rel="stylesheet" href="./assets/css/css/index.css">
    <link rel="stylesheet" href="./assets/css/css/account.css">
    <script src="./assets/jquery-3.6.0.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script src="./css/jquery-3.6.0.min.js"></script>
</head>
<body>

   
<div class="container-fluid">

 <?php
  	require './account_sidebar.php';
 ?><br><br>


<div class="row cardBox">
    <div class="col-12">
<div>
   
<a href="./rejected_leave_status.php"><input type="submit" name="rejected_leave" value="Rejected Leave" class="btn btn-outline-danger"></a> &numsp;&numsp;&numsp;
<a href="./pending_leave_status.php"><input type="submit" name="pending_leave" value="Pending Leave" class="btn btn-outline-warning">&numsp;&numsp;&numsp;
<a href="./leave_history.php"><input type="submit" name="back" value="Back" class="btn btn-outline-primary">
<br><br>
</div>
<center><h4>Approved Leave History</center>
    <center>
       <div class="leave_table">
    		<table>
    			<tr class="head_back">
                    
    				<th class="head_back">Leave Type</th>
    				<th class="head_back">Applied Date</th>
    				<th class="head_back">From</th>
    				<th class="head_back">To</th>
    				<th class="head_back">Reason</th>
    				
    			</tr>
<?php
while($row = mysqli_fetch_assoc($result)){
?>
<tr class="head_back">
	<td class="data_back"><?php echo $row['leave_type']?></td>
	<td class="data_back"><?php echo $row['applied_date']?></td>
	<td class="data_back"><?php echo $row['from_date']?></td>
	<td class="data_back"><?php echo $row['to_date']?></td>
	<td class="data_back"><?php echo $row['leave_reason']?></td>


</tr>    
<?php
    }
?>
                            
    </table>
</div>
</center>

</div>

</div>
</div>
</body>
</html>