<?php

require_once('./include/connect.php');
session_start();
require './encdec.php';

if(!isset($_SESSION['regid'])){
    header('Location: index');
    exit();
}

$id = $_SESSION['regid'];

if(isset($_POST['marital_submit'])){
    $marital_status = mysqli_real_escape_string($conn, $_POST['marital_option']);
    $marital_sql = "update tbl_user_details set marital_status='$marital_status' where user_reg_id='$id'";
    if(mysqli_query($conn, $marital_sql)){
        header('Location: ./mydetails?success');
    }else{
        echo mysqli_error($conn);
    }
}

if(isset($_POST['caste_submit'])){
    $caste = mysqli_real_escape_string($conn, $_POST['caste']);
    $caste_sql = "update tbl_user_details set caste='$caste' where user_reg_id='$id'";
    if(mysqli_query($conn, $caste_sql)){
        header('Location: ./mydetails?success');
    }else{
        echo mysqli_error($conn);
    }
}

if(isset($_POST['religion_submit'])){
    $religion = mysqli_real_escape_string($conn, $_POST['religion']);
    $religion_sql = "update tbl_user_details set religion='$religion' where user_reg_id='$id'";
    if(mysqli_query($conn, $religion_sql)){
        header('Location: ./mydetails?success');
    }else{
        echo mysqli_error($conn);
    }
}

if(isset($_POST['qualification_submit'])){
    $qualifications = $_POST['qualifications'];
    $qualifications = implode(',', $qualifications);
    $qualifications_sql = "update tbl_user_details set qualifications='$qualifications' where user_reg_id='$id'";
    if(mysqli_query($conn, $qualifications_sql)){
        header('Location: ./mydetails?success');
    }else{
        echo mysqli_error($conn);
    }
}

if(isset($_POST['ward_submit'])){
    $ward = mysqli_real_escape_string($conn, $_POST['ward']);
    $ward_sql = "update tbl_user_details set ward='$ward' where user_reg_id='$id'";
    if(mysqli_query($conn, $ward_sql)){
        header('Location: ./mydetails?success');
    }else{
        echo mysqli_error($conn);
    }
}

if(isset($_POST['place_submit'])){
    $place = mysqli_real_escape_string($conn, $_POST['place']);
    $place_sql = "update tbl_registration set place='$place' where regid='$id'";
    if(mysqli_query($conn, $place_sql)){
        header('Location: ./mydetails?success');
    }else{
        echo mysqli_error($conn);
    }
}

if(isset($_POST['localbody_submit'])){
    $localbody = mysqli_real_escape_string($conn, $_POST['localbody']);
    $localbody_sql = "update tbl_registration set localbody='$localbody' where regid='$id'";
    if(mysqli_query($conn, $localbody_sql)){
        header('Location: ./mydetails?success');
    }else{
        echo mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>My Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./assets/css/css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/jpg" href="./assets/images/logo.png" />
    <link rel="stylesheet" href="./assets/css/css/index.css">
    <link rel="stylesheet" href="./assets/css/css/account.css">
    <script src="./assets/jquery-3.6.0.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">


    <!-- *********************************************SELECT MULTIPLE LIBRARIES -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
</head>

<body>
    <style>
        .mul-select{
            width: 70%;
        }
    </style>

    <div class="container-fluid">
        <?php
        require './account_sidebar.php';
        ?><br><br>
        <div class="row cardBox">
            <div class="col-12">
                
                <center>
                    <table class="account_table">

                        <center>
                            <?php
                                if(isset($_GET['success'])){
                            ?>
                                <div class="alert-success" style="padding: 5px;">Data has been updated successfully</div>
                            <?php
                                }
                            ?>
                        </center><br>

                        <tr style="background-color: antiquewhite">
                            <th style="width: 600px; text-align: left;padding: 10px;">Details</th>
                            <th style="width: 150px; text-align: left;padding: 10px;">Submit Here</th>
                        </tr>
                        
                        <tr>
                            <form id="marital_form" method="POST" enctype="multipart/form-data">
                                <td>
                                    <select class="form-select" aria-label="Default select example" name="marital_option" id="marital_status">
                                        <option value="0" selected>Please select your marital status</option>
                                        <option value="1">Married</option>
                                        <option value="2">Unmarried</option>
                                        <option value="3">Widow</option>
                                    </select>
                                    <h5 id="error1" style="color:red; font-size: 15px; display:none;">*Please select an option</h5>
                                </td>
                                <td>
                                    <input type="submit" id="marital_status" name="marital_submit" value="Submit" class="btn btn-outline-success">
                                </td>
                            </form>
                        </tr>

                        <tr>
                            <form id="ward_form" method="POST" enctype="multipart/form-data">
                                <td>
                                    <input type="number" name="ward" placeholder="Ward" required id="ward">
                                </td>
                                <td>
                                    <input type="submit" name="ward_submit" value="Submit" class="btn btn-outline-success">
                                </td>
                            </form>
                        </tr>

                        <tr>
                            <form id="caste_form" method="POST" enctype="multipart/form-data">
                                <td>
                                    <input type="text" name="caste" placeholder="Caste" required id="caste">
                                </td>
                                <td>
                                    <input type="submit" name="caste_submit" value="Submit" class="btn btn-outline-success">
                                </td>
                            </form>
                        </tr>
                        <tr>
                            <form id="religion_form" method="POST" enctype="multipart/form-data">
                                <td>
                                    <input type="text" name="religion" placeholder="Religion" required id="religion">
                                </td>
                                <td>
                                    <input type="submit" name="religion_submit" value="Submit" class="btn btn-outline-success">
                                </td>
                            </form>
                        </tr>

                        <tr>
                            <form id="place_form" method="POST" enctype="multipart/form-data">
                                <td>
                                    <input type="text" name="place" placeholder="Place" required id="place">
                                </td>
                                <td>
                                    <input type="submit" name="place_submit" value="Submit" class="btn btn-outline-success">
                                </td>
                            </form>
                        </tr>

                        <tr>
                            <form id="localbody_form" method="POST" enctype="multipart/form-data">
                                <td>
                                    <input type="text" name="localbody" placeholder="Localbody" required id="localbody">
                                </td>
                                <td>
                                    <input type="submit" name="localbody_submit" value="Submit" class="btn btn-outline-success">
                                </td>
                            </form>
                        </tr>

                        <tr>
                            <form id="qualification_form" method="POST" enctype="multipart/form-data">
                                <td>
                                    <select class="mul-select" name="qualifications[]" id="qualification" multiple="true">
                                        <option value="tenth">10th</option>
                                        <option value="plus2">Plus 2</option>
                                        <option value="degreeUG">Degree (UG)</option>
                                        <option value="degreePG">Degree (PG)</option>
                                        <option value="diploma">Diploma</option>
                                        <option value="it">IT</option>
                                    </select><br>
                                    <h5 id="error2" style="color:red; font-size: 15px; display:none;">*Please select an option</h5>
                                    <!-- Script -->
                                        <script>
                                            $(document).ready(function(){
                                                $(".mul-select").select2({
                                                    placeholder: " Qualifications ", //placeholder
                                                    tags: true,
                                                    tokenSeparators: ['/',',',';'," "] 
                                                });
                                            })
                                        </script>
                                    <!-- Script End -->
                                </td>
                                <td>
                                    <input type="submit" name="qualification_submit" value="Submit" class="btn btn-outline-success">
                                </td>
                            </form>
                        </tr>

                    </table>
                </center>

            </div>
        </div>
    </div>

    </div>

    </div>

</body>

</html>

<style>
    input[type=text]:focus, input[type=number]:focus, .form-select:focus{
        box-shadow: 0 0 20px 2px red;
    }
</style>

<script>

function focus_element(a){
    $(a).focus();
}

$(document).ready(function(){

    $("#marital_form").submit(function (e){
        var marital = $('#marital_status').val();
        if(marital=="0"){
            $("#error1").css("display", "block");
            e.preventDefault();
        }else{
            $("#error1").css("display", "none");
        }
    });
    
    $("#qualification_form").submit(function (e){
        var qualification = $('#qualification').val();
        if(qualification==null){
            $("#error2").css("display", "block");
            e.preventDefault();
        }else{
            $("#error2").css("display", "none");
        }
    });

});


</script>

<?php

// Account page redirect


if(isset($_GET['fo'])){
    $element = decrypt($_GET['fo']);
    $element = str_replace(' ', '+', $element);
    $ele = "";
    echo "<script>";
?>
    var ele = "<?php echo $element ?>";
    focus_element(ele);
<?php
    echo "</script>";
}


?>