<?php

require_once('./include/connect.php');
session_start();
require './encdec.php';

if(!isset($_SESSION['regid'])){
    header('Location: index');
    exit();
}

$id = $_SESSION['regid'];

function upload_documents($doc, $conn, $id){

    if(!is_dir("superuser/assets/documents/".$id)){
        mkdir("superuser/assets/documents/".$id); 
        if(!is_dir("superuser/assets/documents/".$id."/adhaar")){
            mkdir("superuser/assets/documents/".$id."/adhaar");
        }
        if(!is_dir("superuser/assets/documents/".$id."/pan")){
            mkdir("superuser/assets/documents/".$id."/pan");
        }
        if(!is_dir("superuser/assets/documents/".$id."/death")){
            mkdir("superuser/assets/documents/".$id."/death");
        }
        if(!is_dir("superuser/assets/documents/".$id."/birth")){
            mkdir("superuser/assets/documents/".$id."/birth");
        }
        if(!is_dir("superuser/assets/documents/".$id."/widow")){
            mkdir("superuser/assets/documents/".$id."/widow");
        }
        if(!is_dir("superuser/assets/documents/".$id."/tenth")){
            mkdir("superuser/assets/documents/".$id."/tenth");
        }
        if(!is_dir("superuser/assets/documents/".$id."/income")){
            mkdir("superuser/assets/documents/".$id."/income");
        }
        if(!is_dir("superuser/assets/documents/".$id."/plus2")){
            mkdir("superuser/assets/documents/".$id."/plus2");
        }
    }else{
        if(!is_dir("superuser/assets/documents/".$id."/adhaar")){
            mkdir("superuser/assets/documents/".$id."/adhaar");
        }
        if(!is_dir("superuser/assets/documents/".$id."/pan")){
            mkdir("superuser/assets/documents/".$id."/pan");
        }
        if(!is_dir("superuser/assets/documents/".$id."/death")){
            mkdir("superuser/assets/documents/".$id."/death");
        }
        if(!is_dir("superuser/assets/documents/".$id."/birth")){
            mkdir("superuser/assets/documents/".$id."/birth");
        }
        if(!is_dir("superuser/assets/documents/".$id."/widow")){
            mkdir("superuser/assets/documents/".$id."/widow");
        }
        if(!is_dir("superuser/assets/documents/".$id."/tenth")){
            mkdir("superuser/assets/documents/".$id."/tenth");
        }
        if(!is_dir("superuser/assets/documents/".$id."/income")){
            mkdir("superuser/assets/documents/".$id."/income");
        }
        if(!is_dir("superuser/assets/documents/".$id."/plus2")){
            mkdir("superuser/assets/documents/".$id."/plus2");
        }
    }  

    $target_dir_adhaar = "superuser/assets/documents/".$id."/adhaar/";
    $target_dir_pan = "superuser/assets/documents/".$id."/pan/";
    $target_dir_death = "superuser/assets/documents/".$id."/death/";
    $target_dir_birth = "superuser/assets/documents/".$id."/birth/";
    $target_dir_widow = "superuser/assets/documents/".$id."/widow/";
    $target_dir_income = "superuser/assets/documents/".$id."/income/";
    $target_dir_tenth = "superuser/assets/documents/".$id."/tenth/";
    $target_dir_plus2 = "superuser/assets/documents/".$id."/plus2/";

    $fidExist = "select fid from tbl_documents where fid='$id'";
    $check = mysqli_query($conn, $fidExist);
    if(mysqli_fetch_assoc($check)){
        $target_folder = "target_dir_".$doc;
        $target_folder = $$target_folder;
        $target = $target_folder . basename($_FILES[$doc]['name']);
        $file = $_FILES[$doc]['name'];
        $sql = "update tbl_documents set $doc='$file' where fid='$id'";
        $result = mysqli_query($conn, $sql);
        if($result){
            move_uploaded_file($_FILES[$doc]['tmp_name'], $target);
            header('Location: mydocuments?success');
        }
    }else{
        $target_folder = "target_dir_".$doc;
        $target_folder = $$target_folder;
        $target = $target_folder . basename($_FILES[$doc]['name']);
        $file = $_FILES[$doc]['name'];

        $doc_avaialable = ['adhaar', 'pan', 'widow', 'birth', 'death', 'tenth', 'income', 'plus2'];
        $acard = $pcard = $widow_cert = $birth_cert = $death_cert = $tenth_cert = $income_cert = $plus2_cert = 'NULL';
        for($i=0; $i<sizeof($doc_avaialable); $i++){
            if($doc=='adhaar'){
                $acard = $file;
            }else if($doc=='pan'){
                $pcard = $file;
            }else if($doc=='widow'){
                $widow_cert = $file;
            }else if($doc=='birth'){
                $birth_cert = $file;
            }else if($doc=='death'){
                $death_cert = $file;
            }else if($doc=='tenth'){
                $tenth_cert = $file;
            }else if($doc=='income'){
                $income_cert = $file;
            }else if($doc=='plus2'){
                $plus2_cert = $file;
            }
        }

        $sql = "insert into tbl_documents(fid, adhaar, pan, birth, death, income, widow, tenth, plus2) values
      ('$id', '$acard', '$pcard', '$widow_cert', '$birth_cert', '$death_cert', '$tenth_cert', '$income_cert', '$plus2_cert')";
        $result = mysqli_query($conn, $sql);
        if($result){
            move_uploaded_file($_FILES[$doc]['tmp_name'], $target);
            header('Location: mydocuments?success');
        }
    }  
}

if(isset($_POST['adhaar_submit'])){

    upload_documents('adhaar', $conn, $id);  
}

if(isset($_POST['pan_submit'])){

    upload_documents('pan', $conn, $id);  
}

if(isset($_POST['birth_submit'])){

    upload_documents('birth', $conn, $id);  
}

if(isset($_POST['death_submit'])){

    upload_documents('death', $conn, $id);  
}

if(isset($_POST['income_submit'])){

    upload_documents('income', $conn, $id);  
}

if(isset($_POST['widow_submit'])){

    upload_documents('widow', $conn, $id);  
}

if(isset($_POST['tenth_submit'])){

    upload_documents('tenth', $conn, $id);  
}

if(isset($_POST['plus2_submit'])){

    upload_documents('plus2', $conn, $id);  
}

// Document downloads
function getDocumentName($name, $id, $conn){
    $doc_sql = "select * from tbl_documents where fid='$id'";
    $doc_result = mysqli_query($conn, $doc_sql);
    $doc_row = mysqli_fetch_assoc($doc_result);
    $doc_name = $doc_row[$name];
    return $doc_name;
}

if(isset($_GET['path']) && isset($_GET['name'])){
    $document_name = $_GET['path'];
    $doc_required = $_GET['name'];

    $url = "superuser/assets/documents/".$id."/".$doc_required."/".$document_name;
    // $url = "./superuser/assets/documents/3/church1.png";
    
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($url).'"');
    header('Content-Length: ' . filesize($url));
    header('Pragma: public');

    flush();

    readfile($url, true);

}

if(isset($_POST['delete'])){
    $document_name = $_POST['doc_name'];
    $delete_sql = "update tbl_documents set $document_name='NULL' where fid='$id'";
    $delete_result = mysqli_query($conn, $delete_sql);
    if($delete_result){
        header('Location: mydocuments?delete');
    }
}

// getDocumentName('adhaar', $id, $conn);

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
                    <?php
                        if(isset($_GET['success'])){
                    ?>
                            <div class='alert-success'>
                                <center>Document has been uploaded successfully</center>
                            </div><br>
                    <?php
                        }elseif(isset($_GET['delete'])){
                    ?>
                            <div class='alert-danger'>
                                <center>Document has been deleted successfully</center>
                            </div><br>
                    <?php
                        }
                    ?>
                    <h5 style="color: red; font-size: 15px;">*only image files are allowed (.jpg, .jpeg, .png)</h5>
                    <table class="account_table">
                        <tr style="background-color: antiquewhite">
                            <th style="width: 600px; text-align: left;padding: 10px;">Documents</th>
                            <th style="width: 150px; text-align: left;padding: 10px;">Submit Here</th>
                            <th style="width: 150px; text-align: left;padding: 10px;">View</th>
                            <th style="width: 150px; text-align: left;padding: 10px;">Delete</th>
                        </tr>
                        
                        <tr>
                            <form id="adhaar_form" method="POST" enctype="multipart/form-data">
                                <td>
                                    <table>
                                        <tr>
                                            <td style="width: 180px;">Adhaar Card</td>
                                            <td><input type="file" name="adhaar" id="adhaar"></td>
                                        </tr>
                                    </table>
                                    <h5 id="error" style="color: red; font-size: 15px; display: none;">*Only Image files are allowed (.jpg, .jpeg, .png)</h5>
                                </td>
                                <td>
                                    <div style='display: flex; flex-direction: row; justify-content: space-between'>
                                        <input type="submit" name="adhaar_submit" value="Submit" class="btn btn-outline-success">&numsp;
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                        $doc_name = getDocumentName('adhaar', $id, $conn); 
                                        $doc_exist = 1;
                                        if($doc_name=='NULL'){
                                            $doc_exist = 0;
                                        }
                                        $doc_required = 'adhaar';

                                        if($doc_exist==1){
                                            $url = "superuser/assets/documents/".$id."/".$doc_required."/".$doc_name;
                                    ?>
                                            <img style="width: 80px; height: 80px" src="<?php echo $url ?>" alt="">
                                           
                                    <?php
                                        }else{
                                    ?>
                                            <p>Empty</p>
                                    <?php
                                        }
                                    ?>
                                </td>
                            </form>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="doc_name" value='adhaar' id="">
                                    <input type="submit" name="delete" value="Delete" class="btn btn-outline-danger">
                                </form>
                            </td>
                        </tr>

                        <tr>
                            <form id="pan_card" method="POST" enctype="multipart/form-data">
                                <td>
                                    <table>
                                        <tr>
                                            <td style="width: 180px;">Pan Card</td>
                                            <td><input type="file" name="pan" id="pancard"></td>
                                        </tr>
                                    </table>
                                    <h5 id="error1" style="color:red; font-size: 15px; display:none;">*Only Image files are allowed(.jpg,.jpeg,.png)</h5>
                                </td>
                                <td>
                                    <input type="submit" name="pan_submit" value="Submit" class="btn btn-outline-success">
                                </td>
                                <td>
                                    <?php 
                                        $doc_name = getDocumentName('pan', $id, $conn); 
                                        $doc_exist = 1;
                                        if($doc_name=='NULL'){
                                            $doc_exist = 0;
                                        }
                                        $doc_required = 'pan';

                                        if($doc_exist==1){
                                            $url = "superuser/assets/documents/".$id."/".$doc_required."/".$doc_name;      
                                    ?>
                                    <img style="width: 80px; height: 80px" src="<?php echo $url ?>" alt="">   

                                    <?php
                                        }else{
                                    ?>
                                            <p>Empty</p>
                                    <?php
                                        }
                                    ?>
                                </td>
                            </form>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="doc_name" value='pan' id="">
                                    <input type="submit" name="delete" value="Delete" class="btn btn-outline-danger">
                                </form>
                            </td>
                        </tr>

                        <tr>
                            <form id="birth_form" method="POST" enctype="multipart/form-data">
                                <td>
                                    <table>
                                        <tr>
                                            <td style="width: 180px;">Birth Certificate</td>
                                            <td><input type="file" name="birth" id="birth"></td>
                                        </tr>
                                    </table>
                                    <h5 id="error2" style="color: red; font-size: 15px; display: none;">*Only Image files are allowed (.jpg, .jpeg, .png)</h5>
                                </td>
                                <td>
                                    <input type="submit" name="birth_submit" value="Submit" class="btn btn-outline-success">
                                </td>
                                <td>
                                    <?php 
                                        $doc_name = getDocumentName('birth', $id, $conn); 
                                        $doc_exist = 1;
                                        if($doc_name=='NULL'){
                                            $doc_exist = 0;
                                        }
                                        $doc_required = 'birth';

                                        if($doc_exist==1){

                                    $url = "superuser/assets/documents/".$id."/".$doc_required."/".$doc_name;      
                                    ?>
                                    <img style="width: 80px; height: 80px" src="<?php echo $url ?>" alt="">
                                    <?php
                                        }else{
                                    ?>
                                            <p>Empty</p>
                                    <?php
                                        }
                                    ?>
                                </td>
                            </form>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="doc_name" value='birth' id="">
                                    <input type="submit" name="delete" value="Delete" class="btn btn-outline-danger">
                                </form>
                            </td>
                        </tr>

                        <!-- <tr>
                            <form id="death_form" method="POST" enctype="multipart/form-data">
                                <td>
                                    <table>
                                        <tr>
                                            <td style="width: 180px;">Death Certificate</td>
                                            <td><input type="file" name="death" id="death"></td>
                                        </tr>
                                    </table>
                                    <h5 id="error3" style="color: red; font-size: 15px; display: none;">*Only Image files are allowed (.jpg, .jpeg, .png)</h5>
                                </td>
                                <td>
                                    <input type="submit" name="death_submit" value="Submit" class="btn btn-outline-success">
                                </td>
                            </form>
                        </tr> -->

                        <tr>
                            <form id="income_form" method="POST" enctype="multipart/form-data">
                                <td>
                                    <table>
                                        <tr>
                                            <td style="width: 180px;">Income Certificate</td>
                                            <td><input type="file" name="income" id="income"></td>
                                        </tr>
                                    </table>
                                    <h5 id="error4" style="color: red; font-size: 15px; display: none;">*Only Image files are allowed (.jpg, .jpeg, .png)</h5>
                                </td>
                                <td>
                                    <input type="submit" name="income_submit" value="Submit" class="btn btn-outline-success">
                                </td>
                                <td>
                                    <?php 
                                        $doc_name = getDocumentName('income', $id, $conn); 
                                        $doc_exist = 1;
                                        if($doc_name=='NULL'){
                                            $doc_exist = 0;
                                        }
                                        $doc_required = 'income';

                                        if($doc_exist==1){
                                    $url = "superuser/assets/documents/".$id."/".$doc_required."/".$doc_name;      
                                    ?>
                                    <img style="width: 80px; height: 80px" src="<?php echo $url ?>" alt="">
                                    <?php
                                        }else{
                                    ?>
                                            <p>Empty</p>
                                    <?php
                                        }
                                    ?>
                                </td>
                            </form>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="doc_name" value='income' id="">
                                    <input type="submit" name="delete" value="Delete" class="btn btn-outline-danger">
                                </form>
                            </td>
                        </tr>

                        <tr>
                            <form id="widow_form" method="POST" enctype="multipart/form-data">
                                <td>
                                    <table>
                                        <tr>
                                            <td style="width: 180px;">Widow Certificate</td>
                                            <td><input type="file" name="widow" id="widow"></td>
                                        </tr>
                                    </table>
                                    <h5 id="error5" style="color: red; font-size: 15px; display: none;">*Only Image files are allowed (.jpg, .jpeg, .png)</h5>
                                </td>
                                <td>
                                    <input type="submit" name="widow_submit" value="Submit" class="btn btn-outline-success">
                                </td>
                                <td>
                                    <?php 
                                        $doc_name = getDocumentName('widow', $id, $conn); 
                                        $doc_exist = 1;
                                        if($doc_name=='NULL'){
                                            $doc_exist = 0;
                                        }
                                        $doc_required = 'widow';

                                        if($doc_exist==1){
                                            $url = "superuser/assets/documents/".$id."/".$doc_required."/".$doc_name;      
                                            ?>
                                            <img style="width: 80px; height: 80px" src="<?php echo $url ?>" alt="">
                                    <?php
                                        }else{
                                    ?>
                                            <p>Empty</p>
                                    <?php
                                        }
                                    ?>
                                </td>
                            </form>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="doc_name" value='widow' id="">
                                    <input type="submit" name="delete" value="Delete" class="btn btn-outline-danger">
                                </form>
                            </td>
                        </tr>

                        <tr>
                            <form id="tenth_form" method="POST" enctype="multipart/form-data">
                                <td>
                                    <table>
                                        <tr>
                                            <td style="width: 180px;">Tenth Certificate</td>
                                            <td><input type="file" name="tenth" id="tenth"></td>
                                        </tr>
                                    </table>
                                    <h5 id="error6" style="color: red; font-size: 15px; display: none;">*Only Image files are allowed (.jpg, .jpeg, .png)</h5>
                                </td>
                                <td>
                                    <input type="submit" name="tenth_submit" value="Submit" class="btn btn-outline-success">
                                </td>
                                <td>
                                    <?php 
                                        $doc_name = getDocumentName('tenth', $id, $conn); 
                                        $doc_exist = 1;
                                        if($doc_name=='NULL'){
                                            $doc_exist = 0;
                                        }
                                        $doc_required = 'tenth';

                                        if($doc_exist==1){
                                            $url = "superuser/assets/documents/".$id."/".$doc_required."/".$doc_name;      
                                            ?>
                                            <img style="width: 80px; height: 80px" src="<?php echo $url ?>" alt="">
                                    <?php
                                        }else{
                                    ?>
                                            <p>Empty</p>
                                    <?php
                                        }
                                    ?>
                                </td>
                            </form>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="doc_name" value='tenth' id="">
                                    <input type="submit" name="delete" value="Delete" class="btn btn-outline-danger">
                                </form>
                            </td>
                        </tr>

                        <tr>
                            <form id="plus2_form" method="POST" enctype="multipart/form-data">
                                <td>
                                    <table>
                                        <tr>
                                            <td style="width: 180px;">Plus2 Certificate</td>
                                            <td><input type="file" name="plus2" id="plus2"></td>
                                        </tr>
                                    </table>
                                    <h5 id="error7" style="color: red; font-size: 15px; display: none;">*Only Image files are allowed (.jpg, .jpeg, .png)</h5>
                                </td>
                                <td>
                                    <input type="submit" name="plus2_submit" value="Submit" class="btn btn-outline-success">
                                </td>
                                <td>
                                    <?php 
                                        $doc_name = getDocumentName('plus2', $id, $conn); 
                                        $doc_exist = 1;
                                        if($doc_name=='NULL'){
                                            $doc_exist = 0;
                                        }
                                        $doc_required = 'plus2';

                                        if($doc_exist==1){
                                            $url = "superuser/assets/documents/".$id."/".$doc_required."/".$doc_name;      
                                            ?>
                                            <img style="width: 80px; height: 80px" src="<?php echo $url ?>" alt="">
                                    <?php
                                        }else{
                                    ?>
                                            <p>Empty</p>
                                    <?php
                                        }
                                    ?>
                                </td>
                            </form>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="doc_name" value='plus2' id="">
                                    <input type="submit" name="delete" value="Delete" class="btn btn-outline-danger">
                                </form>
                            </td>
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

<script>

$(document).ready(function(){
    var extension = ['jpg', 'jpeg', 'png'];

    $("#adhaar_form").submit(function (e){
        var filename = $('#adhaar').val().split('\\').pop();
        var ext = filename.split('.');
        if(jQuery.inArray(ext[1], extension ) !== -1){
            $("#error").css("display", "none");
        }else{
            $("#error").css("display", "block");
            e.preventDefault();
        }
    });

    $("#pan_card").submit(function (e){
        var filename = $('#pancard').val().split('\\').pop();
        var ext = filename.split('.');
        if(jQuery.inArray(ext[1], extension ) !== -1){
            $("#error1").css("display", "none");
        }else{
            $("#error1").css("display", "block");
            e.preventDefault();
        }
    });

    $("#birth_form").submit(function (e)
    {
        var filename =$('#birth').val().split('\\').pop();
        var ext = filename.split('.');
        if(jQuery.inArray(ext[1], extension ) !== -1){
            $("#error2").css("display", "none");
        }else{
            $("#error2").css("display", "block");
            e.preventDefault();
        }
    });

    $("#death_form").submit(function (e)
    {
        var filename =$('#death').val().split('\\').pop();
        var ext = filename.split('.');
        if(jQuery.inArray(ext[1], extension ) !== -1){
            $("#error3").css("display", "none");
        }else{
            $("#error3").css("display", "block");
            e.preventDefault();
        }
    });

    $("#income_form").submit(function (e)
    {
        var filename =$('#income').val().split('\\').pop();
        var ext = filename.split('.');
        if(jQuery.inArray(ext[1], extension ) !== -1){
            $("#error4").css("display", "none");
        }else{
            $("#error4").css("display", "block");
            e.preventDefault();
        }
    });

    $("#widow_form").submit(function (e)
    {
        var filename =$('#widow').val().split('\\').pop();
        var ext = filename.split('.');
        if(jQuery.inArray(ext[1], extension ) !== -1){
            $("#error5").css("display", "none");
        }else{
            $("#error5").css("display", "block");
            e.preventDefault();
        }
    });

    $("#tenth_form").submit(function (e)
    {
        var filename =$('#tenth').val().split('\\').pop();
        var ext = filename.split('.');
        if(jQuery.inArray(ext[1], extension ) !== -1){
            $("#error6").css("display", "none");
        }else{
            $("#error6").css("display", "block");
            e.preventDefault();
        }
    });

    $("#plus2_form").submit(function (e)
    {
        var filename =$('#plus2').val().split('\\').pop();
        var ext = filename.split('.');
        if(jQuery.inArray(ext[1], extension ) !== -1){
            $("#error7").css("display", "none");
        }else{
            $("#error7").css("display", "block");
            e.preventDefault();
        }
    });


});


</script>