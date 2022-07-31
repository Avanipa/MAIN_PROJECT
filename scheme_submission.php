<?php

require './navbar.php';
require './mail.php';

if(!isset($_SESSION['regid'])){
  header('Location: index');
  exit();
}

$scheme = "";
$flag = 0;
$docExist = 0;
$schemeExist = 0;
$reg_id = $_SESSION['regid'];

$email_sql = "select emailid from tbl_login where login_id='$reg_id'";
$email_result = mysqli_query($conn, $email_sql);
$email_row = mysqli_fetch_assoc($email_result);

$name_sql = "select uname from tbl_registration where regid='$reg_id'";
$name_result = mysqli_query($conn, $name_sql);
$name_row = mysqli_fetch_assoc($name_result);

if(isset($_GET['tag'])){
  $scheme = $_GET['tag'];
  $scheme = str_replace(' ', '+', $scheme);
  $scheme = decrypt($scheme);
}
$description = "";
$end = "";
$eligibility = "";
$age = "";
$scheme_name = "";
$docs_needed = "";

$sql = "select * from tbl_cscheme where schemeid='$scheme' and status=1";
$result = mysqli_query($conn, $sql);
  while($row=mysqli_fetch_array($result)){
    $description = $row['schemedescription'];
    $eligibility = $row['criteria'];
    $age = $row['category'];
    $scheme_name = ucfirst($row['subscheme']);
    $docs_needed = $row['docs_needed'];
  }

  $docs_needed = explode(',', $docs_needed);

  // Docs Checking
  $sql = "select * from tbl_documents where fid='$reg_id'";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_assoc($result)){
    $docExist = 1;
  }
  if($docExist==1){
    $sql = "select * from tbl_documents where fid='$reg_id'";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)){
      $tadhaar = $row['adhaar'];
      $tpan = $row['pan'];
      $tbirth = $row['birth'];
      $tdeath = $row['death'];
      $tincome = $row['income'];
      $twidow = $row['widow'];
      $ttenth = $row['tenth'];
      $tplus2 = $row['plus2'];
    }
  }else{
    $tadhaar = 'NULL';
    $tpan = 'NULL';
    $tbirth = 'NULL';
    $tdeath = 'NULL';
    $tincome = 'NULL';
    $twidow = 'NULL';
    $ttenth = 'NULL';
    $tplus2 = 'NULL';
  }


  if(isset($_POST['submit'])){
    $target_dir_adhaar = "assets/documents/adhaar/";
    $target_dir_pan = "assets/documents/pan/";
    $target_dir_death = "assets/documents/death/";
    $target_dir_birth = "assets/documents/birth/";
    $target_dir_widow = "assets/documents/widow/";
    $target_dir_income = "assets/documents/income/";
    $target_dir_tenth = "assets/documents/tenth/";
    $target_dir_plus2 = "assets/documents/plus2/";

    $allowed_image_extension = array(
      "png",
      "jpg",
      "jpeg"
    ); 

    if(in_array('adhaar', $docs_needed) && $tadhaar=="NULL"){
      $target_adhaar = $target_dir_adhaar . basename($_FILES['adhaar']['name']);
      $adhaar = $_FILES['adhaar']['name'];
      $file_extension_adhaar = pathinfo($_FILES["adhaar"]["name"], PATHINFO_EXTENSION);
      if(! in_array($file_extension_adhaar, $allowed_image_extension)){
        $flag = 1;
          echo "<script>";
            echo "alert('Only images are allowed for Adhaar card(Extensions: JPG, JPEG, PNG)')";
          echo "</script>";
      }
    }else{
      $adhaar = "NULL";
    }

    if(in_array('pan', $docs_needed) && $tpan=="NULL"){
      $target_pan = $target_dir_adhaar . basename($_FILES['pan']['name']);
      $pan = $_FILES['pan']['name'];
      $file_extension_pan = pathinfo($_FILES["pan"]["name"], PATHINFO_EXTENSION);
      if(! in_array($file_extension_pan, $allowed_image_extension)){
        $flag = 1;
          echo "<script>";
            echo "alert('Only images are allowed for Pan card(Extensions: JPG, JPEG, PNG)')";
          echo "</script>";
      }
    }else{
      $pan = "NULL";
    }

    if(in_array('death', $docs_needed) && $tdeath=="NULL"){
      $target_death = $target_dir_death . basename($_FILES['death']['name']);
      $death = $_FILES['death']['name'];
      $file_extension_death = pathinfo($_FILES["death"]["name"], PATHINFO_EXTENSION);
      if(! in_array($file_extension_death, $allowed_image_extension)){
        $flag = 1;
        echo "<script>";
          echo "alert('Only images are allowed for Death Certificates(Extensions: JPG, JPEG, PNG)')";
        echo "</script>";
      }
    }else{
      $death = "NULL";
    }

    if(in_array('birth', $docs_needed) && $tbirth=="NULL"){
      $target_birth = $target_dir_birth . basename($_FILES['birth']['name']);
      $birth = $_FILES['birth']['name'];
      $file_extension_birth = pathinfo($_FILES["birth"]["name"], PATHINFO_EXTENSION);
      if(! in_array($file_extension_birth, $allowed_image_extension)){
        $flag = 1;
        echo "<script>";
          echo "alert('Only images are allowed for Birth Certificates(Extensions: JPG, JPEG, PNG)')";
        echo "</script>";
      }
    }else{
      $birth = "NULL";
    }

    if(in_array('widow', $docs_needed) && $twidow=="NULL"){
      $target_widow = $target_dir_widow . basename($_FILES['widow']['name']);
      $widow = $_FILES['widow']['name'];
      $file_extension_widow = pathinfo($_FILES["widow"]["name"], PATHINFO_EXTENSION);
      if(! in_array($file_extension_widow, $allowed_image_extension)){
        $flag = 1;
        echo "<script>";
          echo "alert('Only images are allowed for Widow Certificate(Extensions: JPG, JPEG, PNG)')";
        echo "</script>";
      }
    }else{
      $widow = "NULL";
    }

    if(in_array('income', $docs_needed) && $tincome=="NULL"){
      $target_income = $target_dir_income . basename($_FILES['income']['name']);
      $income = $_FILES['income']['name'];
      $file_extension_income = pathinfo($_FILES["income"]["name"], PATHINFO_EXTENSION);
      if(! in_array($file_extension_income, $allowed_image_extension)){
        $flag = 1;
        echo "<script>";
          echo "alert('Only images are allowed for Income Certificate(Extensions: JPG, JPEG, PNG)')";
        echo "</script>";
      }
    }else{
      $income = "NULL";
    }

    if(in_array('tenth', $docs_needed) && $ttenth=="NULL"){
      $target_tenth = $target_dir_tenth . basename($_FILES['tenth']['name']);
      $tenth = $_FILES['tenth']['name'];
      $file_extension_tenth = pathinfo($_FILES["tenth"]["name"], PATHINFO_EXTENSION);
      if(! in_array($file_extension_tenth, $allowed_image_extension)){
        $flag = 1;
        echo "<script>";
          echo "alert('Only images are allowed for Tenth Certificate(Extensions: JPG, JPEG, PNG)')";
        echo "</script>";
      }
    }else{
      $tenth = "NULL";
    }

    if(in_array('plus2', $docs_needed) && $tplus2=="NULL"){
      $target_plus2 = $target_dir_plus2 . basename($_FILES['plus2']['name']);
      $plus2 = $_FILES['plus2']['name'];
      $file_extension_plus2 = pathinfo($_FILES["plus2"]["name"], PATHINFO_EXTENSION);
      if(! in_array($file_extension_plus2, $allowed_image_extension)){
        $flag = 1;
        echo "<script>";
          echo "alert('Only images are allowed for +2 Certificate(Extensions: JPG, JPEG, PNG)')";
        echo "</script>";
      }
    }else{
      $plus2 = "NULL";
    }

  
// Checking whether fid exisyt for document or not    

    if($flag==0){
      $sql = "select * from tbl_documents where fid='$reg_id'";
      $result = mysqli_query($conn, $sql);
      while($row = mysqli_fetch_assoc($result)){
        $docExist = 1;
      }
      $sql = "select * from tbl_user_schemes where cscheme_id='$scheme'";
      $result = mysqli_query($conn, $sql);
      while($row = mysqli_fetch_assoc($result)){
        $schemeExist = 1;
      }
    }
    
    if($flag==0){
      if($docExist==0){
      $sql = "insert into tbl_documents(fid, adhaar, pan, birth, death, income, widow, tenth, plus2) values
      ('$reg_id', '$adhaar', '$pan', '$birth', '$death', '$income', '$widow', '$tenth', '$plus2')";
      if(mysqli_query($conn, $sql)){
        if($schemeExist!=1){

          // ***************************************Move Uploaded File Function********************************************************* 
          function move_file($doc, $reg_id){
            if(!is_dir("superuser/assets/documents/".$reg_id)){
              mkdir("superuser/assets/documents/".$reg_id); 
              if(!is_dir("superuser/assets/documents/".$reg_id."/adhaar")){
                  mkdir("superuser/assets/documents/".$reg_id."/adhaar");
              }
              if(!is_dir("superuser/assets/documents/".$reg_id."/pan")){
                  mkdir("superuser/assets/documents/".$reg_id."/pan");
              }
              if(!is_dir("superuser/assets/documents/".$reg_id."/death")){
                  mkdir("superuser/assets/documents/".$reg_id."/death");
              }
              if(!is_dir("superuser/assets/documents/".$reg_id."/birth")){
                  mkdir("superuser/assets/documents/".$reg_id."/birth");
              }
              if(!is_dir("superuser/assets/documents/".$reg_id."/widow")){
                  mkdir("superuser/assets/documents/".$reg_id."/widow");
              }
              if(!is_dir("superuser/assets/documents/".$reg_id."/tenth")){
                  mkdir("superuser/assets/documents/".$reg_id."/tenth");
              }
              if(!is_dir("superuser/assets/documents/".$reg_id."/income")){
                  mkdir("superuser/assets/documents/".$reg_id."/income");
              }
              if(!is_dir("superuser/assets/documents/".$reg_id."/plus2")){
                  mkdir("superuser/assets/documents/".$reg_id."/plus2");
              }
          }else{
              if(!is_dir("superuser/assets/documents/".$reg_id."/adhaar")){
                  mkdir("superuser/assets/documents/".$reg_id."/adhaar");
              }
              if(!is_dir("superuser/assets/documents/".$reg_id."/pan")){
                  mkdir("superuser/assets/documents/".$reg_id."/pan");
              }
              if(!is_dir("superuser/assets/documents/".$reg_id."/death")){
                  mkdir("superuser/assets/documents/".$reg_id."/death");
              }
              if(!is_dir("superuser/assets/documents/".$reg_id."/birth")){
                  mkdir("superuser/assets/documents/".$reg_id."/birth");
              }
              if(!is_dir("superuser/assets/documents/".$reg_id."/widow")){
                  mkdir("superuser/assets/documents/".$reg_id."/widow");
              }
              if(!is_dir("superuser/assets/documents/".$reg_id."/tenth")){
                  mkdir("superuser/assets/documents/".$reg_id."/tenth");
              }
              if(!is_dir("superuser/assets/documents/".$reg_id."/income")){
                  mkdir("superuser/assets/documents/".$reg_id."/income");
              }
              if(!is_dir("superuser/assets/documents/".$reg_id."/plus2")){
                  mkdir("superuser/assets/documents/".$reg_id."/plus2");
              }
            }  
        
            $target_dir_adhaar = "superuser/assets/documents/".$reg_id."/adhaar/";
            $target_dir_pan = "superuser/assets/documents/".$reg_id."/pan/";
            $target_dir_death = "superuser/assets/documents/".$reg_id."/death/";
            $target_dir_birth = "superuser/assets/documents/".$reg_id."/birth/";
            $target_dir_widow = "superuser/assets/documents/".$reg_id."/widow/";
            $target_dir_income = "superuser/assets/documents/".$reg_id."/income/";
            $target_dir_tenth = "superuser/assets/documents/".$reg_id."/tenth/";
            $target_dir_plus2 = "superuser/assets/documents/".$reg_id."/plus2/";

            $target_folder = "target_dir_".$doc;
            $target_folder = $$target_folder;
            $target = $target_folder . basename($_FILES[$doc]['name']);
            $file = $_FILES[$doc]['name'];

            move_uploaded_file($_FILES[$doc]['tmp_name'], $target);
          }

          if($birth!='NULL'){
              move_file('birth', $reg_id);
          }
          if($adhaar!='NULL'){
              move_file('adhaar', $reg_id);
          }
          if($pan!='NULL'){
              move_file('pan', $reg_id);
          }
          if($tenth!='NULL'){
              move_file('tenth', $reg_id);
          }
          if($plus2!='NULL'){
              move_file('plus2', $reg_id);
          }
          if($income!='NULL'){
              move_file('income', $reg_id);
          }
          if($widow!='NULL'){
              move_file('widow', $reg_id);
          }
          if($death!='NULL'){
              move_file('death', $reg_id);
          }
// ***************************************Move Uploaded File Function End********************************************************* 

          $comments = "NULL";
          $applied_date = date("Y-m-d");
          $rejection = 0;

          $scheme_sql = "select * from tbl_cscheme where schemeid='$scheme'";
          $scheme_result = mysqli_query($conn, $scheme_sql);
          $scheme_row = mysqli_fetch_assoc($scheme_result);

          $scheme_insert = "insert into tbl_user_schemes(doc_fid, reg_fid, cscheme_id, status, comments, applied_date, rejection_status, approval_type) values
          ((select doc_id from tbl_documents where fid='$reg_id'), '$reg_id', '$scheme', 1, '$comments', '$applied_date', '$rejection', 0)";
          if(mysqli_query($conn, $scheme_insert)){
            echo "<script>";
              echo "alert('You have successfully applied for this scheme, Please wait for approvals')";
            echo "</script>";
          }else{
            mysqli_error($conn);
          }
        }else{
          echo "<script>";
              echo "alert('You have already applied for this scheme')";
            echo "</script>";
        }
      }
    }else{
      if($schemeExist!=1){
        $comments = "NULL";
        $applied_date = date("Y-m-d");
        $rejection = 0;

        // ****************************************************Documents Uploading**********************************
        function update_doc($conn, $doc, $reg_id){
          if(!is_dir("superuser/assets/documents/".$reg_id)){
            mkdir("superuser/assets/documents/".$reg_id); 
            if(!is_dir("superuser/assets/documents/".$reg_id."/adhaar")){
                mkdir("superuser/assets/documents/".$reg_id."/adhaar");
            }
            if(!is_dir("superuser/assets/documents/".$reg_id."/pan")){
                mkdir("superuser/assets/documents/".$reg_id."/pan");
            }
            if(!is_dir("superuser/assets/documents/".$reg_id."/death")){
                mkdir("superuser/assets/documents/".$reg_id."/death");
            }
            if(!is_dir("superuser/assets/documents/".$reg_id."/birth")){
                mkdir("superuser/assets/documents/".$reg_id."/birth");
            }
            if(!is_dir("superuser/assets/documents/".$reg_id."/widow")){
                mkdir("superuser/assets/documents/".$reg_id."/widow");
            }
            if(!is_dir("superuser/assets/documents/".$reg_id."/tenth")){
                mkdir("superuser/assets/documents/".$reg_id."/tenth");
            }
            if(!is_dir("superuser/assets/documents/".$reg_id."/income")){
                mkdir("superuser/assets/documents/".$reg_id."/income");
            }
            if(!is_dir("superuser/assets/documents/".$reg_id."/plus2")){
                mkdir("superuser/assets/documents/".$reg_id."/plus2");
            }
        }else{
            if(!is_dir("superuser/assets/documents/".$reg_id."/adhaar")){
                mkdir("superuser/assets/documents/".$reg_id."/adhaar");
            }
            if(!is_dir("superuser/assets/documents/".$reg_id."/pan")){
                mkdir("superuser/assets/documents/".$reg_id."/pan");
            }
            if(!is_dir("superuser/assets/documents/".$reg_id."/death")){
                mkdir("superuser/assets/documents/".$reg_id."/death");
            }
            if(!is_dir("superuser/assets/documents/".$reg_id."/birth")){
                mkdir("superuser/assets/documents/".$reg_id."/birth");
            }
            if(!is_dir("superuser/assets/documents/".$reg_id."/widow")){
                mkdir("superuser/assets/documents/".$reg_id."/widow");
            }
            if(!is_dir("superuser/assets/documents/".$reg_id."/tenth")){
                mkdir("superuser/assets/documents/".$reg_id."/tenth");
            }
            if(!is_dir("superuser/assets/documents/".$reg_id."/income")){
                mkdir("superuser/assets/documents/".$reg_id."/income");
            }
            if(!is_dir("superuser/assets/documents/".$reg_id."/plus2")){
                mkdir("superuser/assets/documents/".$reg_id."/plus2");
            }
          }  
      
          $target_dir_adhaar = "superuser/assets/documents/".$reg_id."/adhaar/";
          $target_dir_pan = "superuser/assets/documents/".$reg_id."/pan/";
          $target_dir_death = "superuser/assets/documents/".$reg_id."/death/";
          $target_dir_birth = "superuser/assets/documents/".$reg_id."/birth/";
          $target_dir_widow = "superuser/assets/documents/".$reg_id."/widow/";
          $target_dir_income = "superuser/assets/documents/".$reg_id."/income/";
          $target_dir_tenth = "superuser/assets/documents/".$reg_id."/tenth/";
          $target_dir_plus2 = "superuser/assets/documents/".$reg_id."/plus2/";

          $target_folder = "target_dir_".$doc;
          $target_folder = $$target_folder;
          $target = $target_folder . basename($_FILES[$doc]['name']);
          $file = $_FILES[$doc]['name'];

          $sql = "update tbl_documents set $doc='$file' where fid='$reg_id'";
          if(mysqli_query($conn, $sql)){
              move_uploaded_file($_FILES[$doc]['tmp_name'], $target);
          }else{
              echo "Error in uploading file";
          }
        }

// ****************************************************Documents Uploading End****************************************************

        if($birth!='NULL'){
            update_doc($conn, 'birth', $reg_id);
        }
        if($adhaar!='NULL'){
            update_doc($conn, 'adhaar', $reg_id);
        }
        if($pan!='NULL'){
            update_doc($conn, 'pan', $reg_id);
        }
        if($tenth!='NULL'){
            update_doc($conn, 'tenth', $reg_id);
        }
        if($plus2!='NULL'){
            update_doc($conn, 'plus2', $reg_id);
        }
        if($income!='NULL'){
            update_doc($conn, 'income', $reg_id);
        }
        if($widow!='NULL'){
            update_doc($conn, 'widow', $reg_id);
        }
        if($death!='NULL'){
            update_doc($conn, 'death', $reg_id);
        }

        $scheme_sql = "select * from tbl_cscheme where schemeid='$scheme'";
        $scheme_result = mysqli_query($conn, $scheme_sql);
        $scheme_row = mysqli_fetch_assoc($scheme_result);

        $scheme_insert = "insert into tbl_user_schemes(doc_fid, reg_fid, cscheme_id, status, comments, applied_date, rejection_status, approval_type) values
        ((select doc_id from tbl_documents where fid='$reg_id'), '$reg_id', '$scheme', 1, '$comments', '$applied_date', '$rejection', 0)";
        if(mysqli_query($conn, $scheme_insert)){
        
          echo "<script>";
            echo "alert('You have successfully applied for this scheme, Please wait for approvals')";
          echo "</script>";
        }else{
          mysqli_error($conn);
        }
      }else{
        echo "<script>";
            echo "alert('You have already applied for this scheme')";
          echo "</script>";
      }
    }
    }

    // Checking eligibility
    $userDetails = "select * from tbl_user_details where user_reg_id='$reg_id'";
    $userDetails_result = mysqli_query($conn, $userDetails);
    $userDetails_row = mysqli_fetch_assoc($userDetails_result);
    $marital_status = $userDetails_row['marital_status'];

  }

?>

<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Schemes Submission</title>
  <link rel="stylesheet" href="./assets/css/css/bootstrap.min.css">
  <link rel="shortcut icon" type="image/jpg" href="./assets/images/logo.png"/>
  <link rel="stylesheet" href="./assets/css/css/index.css">
  <script src="./assets/jquery-3.6.0.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body>

    <h3 class="scheme_head">Schemes</h3>

    <div class="container">
      <h4><b>Scheme Name : </b><?php echo $scheme_name; ?></h4><br>
      <h5 style="color: red; font-size: 15px;">*only image files are allowed (.jpg, .jpeg, .png)</h5>
      <form method="POST" enctype="multipart/form-data">
        <table>
          <?php
            if(in_array('adhaar', $docs_needed) && $tadhaar=="NULL"){
              echo "<tr>";
                echo "<td class='scheme_table_td'>";
                  echo "Adhaar";
                echo "</td>";
                echo "<td class='scheme_table_td'>";
                  echo "<input type='file' name='adhaar'>";
                echo "</td>";
              echo "</tr>";
            }
            if(in_array('pan', $docs_needed) && $tpan=="NULL"){
              echo "<tr>";
                echo "<td class='scheme_table_td'>";
                  echo "Pan";
                echo "</td>";
                echo "<td class='scheme_table_td'>";
                  echo "<input type='file' name='pan'>";
                echo "</td>";
              echo "</tr>";
            }
            if(in_array('birth', $docs_needed) && $tbirth=="NULL"){
              echo "<tr>";
                echo "<td class='scheme_table_td'>";
                  echo "Birth Certificate";
                echo "</td>";
                echo "<td class='scheme_table_td'>";
                  echo "<input type='file' name='birth'>";
                echo "</td>";
              echo "</tr>";
            }
            if(in_array('widow', $docs_needed) && $twidow=="NULL"){
              echo "<tr>";
                echo "<td class='scheme_table_td'>";
                  echo "Widow Certificate";
                echo "</td>";
                echo "<td class='scheme_table_td'>";
                  echo "<input type='file' name='widow'>";
                echo "</td>";
              echo "</tr>";
            }
            if(in_array('death', $docs_needed) && $tdeath=="NULL"){
              echo "<tr>";
                echo "<td class='scheme_table_td'>";
                  echo "Death Certificate";
                echo "</td>";
                echo "<td class='scheme_table_td'>";
                  echo "<input type='file' name='death'>";
                echo "</td>";
              echo "</tr>";
            }
            if(in_array('tenth', $docs_needed) && $ttenth=="NULL"){
              echo "<tr>";
                echo "<td class='scheme_table_td'>";
                  echo "10th Certificate";
                echo "</td>";
                echo "<td class='scheme_table_td'>";
                  echo "<input type='file' name='tenth'>";
                echo "</td>";
              echo "</tr>";
            }
            if(in_array('plus2', $docs_needed) && $tplus2=="NULL"){
              echo "<tr>";
                echo "<td class='scheme_table_td'>";
                  echo "+2 Certificate";
                echo "</td>";
                echo "<td class='scheme_table_td'>";
                  echo "<input type='file' name='plus2'>";
                echo "</td>";
              echo "</tr>";
            }
            if(in_array('income', $docs_needed) && $tincome=="NULL"){
              echo "<tr>";
                echo "<td class='scheme_table_td'>";
                  echo "Income Certificate";
                echo "</td>";
                echo "<td class='scheme_table_td'>";
                  echo "<input type='file' name='income'>"; 
                echo "</td>";
              echo "</tr>";
            }
          ?>
        </table><br>
        <input type="submit" name="submit" value="Submit" class="btn btn-outline-success">
      </form>
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