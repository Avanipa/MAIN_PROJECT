<?php

require_once('./include/connect.php');
session_start();
require './encdec.php';

if(!isset($_SESSION['regid'])){
    header('Location: index');
    exit();
}

$id = $_SESSION['regid'];

if(isset($_POST['submit'])){
    
    $qualification = mysqli_real_escape_string($conn, $_POST['qualification']);
    $institution = mysqli_real_escape_string($conn, $_POST['institution']);
    $board = mysqli_real_escape_string($conn, $_POST['board']);
    $register_no = mysqli_real_escape_string($conn, $_POST['register_no']);
    $percentage = mysqli_real_escape_string($conn, $_POST['percentage']);
    $cgpa = mysqli_real_escape_string($conn, $_POST['cgpa']);
    $passout = mysqli_real_escape_string($conn, $_POST['passout']);
    $month = substr($passout, -2);
    $year = (int)substr($passout, 0, 4);

    if($percentage == null && $cgpa == null){
        header('Location: ./document_value_insert?error');
    }else{

        $qual_check_sql = "select * from tbl_qualification where quali_reg_id='$id' and qualification='$qualification'";
        $qual_check_result = mysqli_query($conn, $qual_check_sql);
        if(mysqli_fetch_assoc($qual_check_result)){
            header('Location: ./document_value_insert?repeat');
        }else{
            $sql = "insert into tbl_qualification(quali_reg_id, qualification, institution, board, register_no, percentage, cgpa, passoutmonth, passoutyear)values
            ('$id', '$qualification', '$institution', '$board', '$register_no', '$percentage', '$cgpa', '$month', '$year')";
            $result = mysqli_query($conn, $sql);
            if($result){
                header('Location: ./document_value_insert?success');
            }
        }
    }

}

$sql_display = "select * from tbl_qualification where quali_reg_id='$id'";
$result_display = mysqli_query($conn, $sql_display);

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
                <center><h3>Insert Educational Details</h3></center><br><br>

                <?php
                    if(isset($_GET['error'])){
                ?>  
                        <center><div style='padding: 5px' class="alert-warning">Please select either CGPA or percentage</div><br></center>
                <?php

                    }elseif(isset($_GET['success'])){
                ?>
                        <center><div style='padding: 5px' class="alert-success">Successfully updated</div><br></center>
                <?php
                    }elseif(isset($_GET['repeat'])){
                ?>
                        <center><div style='padding: 5px' class="alert-warning">You have already updated your Educational qualification</div><br></center>
                <?php
                    }
                ?>

                <center>
                    <form method="POST" id="qual_form" style="width: 80%;">
                        <select class="form-select" name="qualification" id="qualification">
                            <option value="null">Select your qualification</option>
                            <option value="tenth">10th</option>
                            <option value="twelth">12th</option>
                            <option value="ug">UG</option>
                            <option value="pg">PG</option>
                        </select>
                        <p id="error1" style="color: red; float: left; display: none;">Please select a qualification</p><br>
                        <input type="text" name="institution" placeholder="Enter your institution" required><br>
                        
                        <select class="form-select" name="board" id="board">
                            <option value="null">Select your board</option>
                            <option value="State Board">State Board</option>
                            <option value="CBSE">CBSE</option>
                            <option value="ICSE">ICSE</option>
                            <option value="Mahatma Gandhi University">Mahatma Gandhi University</option>
                            <option value="Kerala University">Kerala University</option>
                            <option value="Calicut University">Calicut University</option>
                        </select>
                        <p id="error2" style="color: red; float: left; display: none;">Please select a board</p><br>

                        <input type="number" name="register_no" placeholder="Enter your register number" required>
                        <div style="display: flex; justify-content: space-between;">
                            <input type="number" name="percentage" id='percentage' placeholder="Enter your percentage"> OR
                            <input type="number" name="cgpa" id='cgpa' placeholder="Enter your cgpa">
                        </div>
                        <p id="error3" style="color: red; float: left; display: none;">Percentage can't be more than 100</p><br>
                        <p id="error4" style="color: red; float: left; display: none;">CGPA can't be more than 10</p><br>
                        <h5 style="float: left; font-size: 18px; padding-left: 10px;">Please select the passout date: <input type="month" name="passout" required></h5><br><br><br>
                        <input type="submit" name="submit" value="Submit" class="btn btn-outline-success">
                    </form>
                </center>

                <br><br><center><h3>View Educational Details</h3></center><br><br>

                <style>
                    
                </style>

                <div class="leave_table">
                    <table>
                        <tr>
                            <th>Qualification</th>
                            <th>Institution</th>
                            <th>Board</th>
                            <th>Register No</th>
                            <th>%/CGPA</th>
                            <th>Passout</th>
                            <th>Action</th>
                        </tr>
                        <?php
                            while($row = mysqli_fetch_assoc($result_display)){
                                echo "<tr>";
                                    $percent = $row['percentage'];
                                    $cgpa = $row['cgpa'];
                                    $cgperc = 0;
                                    if($percent!=0){
                                        $cgperc = $percent;
                                    }elseif($cgpa!=0){
                                        $cgperc = $cgpa;
                                    }
                                    $passout = $row['passoutmonth'].', '.$row['passoutyear'];
                                    echo "<td class='data_back'>".$row['qualification']."</td>";
                                    echo "<td class='data_back'>".$row['institution']."</td>";
                                    echo "<td class='data_back'>".$row['board']."</td>";
                                    echo "<td class='data_back'>".$row['register_no']."</td>";
                                    echo "<td class='data_back'>".$cgperc."</td>";
                                    echo "<td class='data_back'>".$passout."</td>";
                                    echo "<td class='data_back'>";
                            ?>
                                    <button class='btn btn-info'><a href="educational_edit?id=<?php echo $row['qualification_id'] ?>">Edit</a></button>
                            <?php
                                    echo "</td>";
                                echo "</tr>";
                            }
                        ?>
                    </table>
                </div>

            <div>
        </div>

    </div>

</body>

</html>

<script>
    $(document).ready(function(){
        $('#qual_form').submit(function(e){
            var qualification = $('#qualification').val();
            var board = $('#board').val();
            var percentage = $('#percentage').val();
            var cgpa = $('#cgpa').val();

            if(percentage>100){
                $('#error3').css('display', 'block');
                e.preventDefault();
            }else{
                $('#error3').css('display', 'none');
            }

            if(cgpa>10){
                $('#error4').css('display', 'block');
                e.preventDefault();
            }else{
                $('#error4').css('display', 'none');
            }

            if(qualification=='null'){
                $('#error1').css('display', 'block');
                e.preventDefault();
            }else{
                $('#error1').css('display', 'none');
            }

            if(board=='null'){
                $('#error2').css('display', 'block');
                e.preventDefault();
            }else{
                $('#error2').css('display', 'none');
            }
        });
    });
</script>