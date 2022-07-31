<?php

require_once('./include/connect.php');

$conn = $conn;

include './encdec.php';
session_start();
if(!isset($_SESSION['regid'])){
    header('Location: index');
    exit();
}

$target_dir_adhaar = "assets/documents/adhaar/";
$target_dir_pan = "assets/documents/pan/";
$target_dir_death = "assets/documents/death/";
$target_dir_birth = "assets/documents/birth/";
$target_dir_widow = "assets/documents/widow/";
$target_dir_income = "assets/documents/income/";
$target_dir_tenth = "assets/documents/tenth/";
$target_dir_plus2 = "assets/documents/plus2/";

// $ahaar_name = "";
$id = 0;
$reg_id = 0;
$docs_needed = "";

// **************************SCHEME*********************
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $id = str_replace(' ', '+', $id);
    $id = decrypt($id);

    $sql = "select * from tbl_cscheme where schemeid='$id'";
    $result = mysqli_query($conn, $sql);
    while($row=mysqli_fetch_assoc($result)){
        $docs = $row['docs_needed'];
    }
}
if(isset($_GET['ids'])){
    $reg_id = $_GET['ids'];
    $reg_id = str_replace(' ', '+', $reg_id);
    $reg_id = decrypt($reg_id);
}

// ****************************SERVICE*********************
if(isset($_GET['serid'])){
    $id = $_GET['serid'];
    $id = str_replace(' ', '+', $id);
    $id = decrypt($id);

    $sql = "select * from tbl_services where serviceid='$id'";
    $result = mysqli_query($conn, $sql);
    while($row=mysqli_fetch_assoc($result)){
        $docs = $row['docs_needed'];
    }
}
if(isset($_GET['serids'])){
    $reg_id = $_GET['serids'];
    $reg_id = str_replace(' ', '+', $reg_id);
    $reg_id = decrypt($reg_id);
}

$docs_needed = explode(',', $docs);
$length = sizeof($docs_needed);

function download($doc, $reg_id, $conn){

    $document = "";

    $target_dir_adhaar = "assets/documents/adhaar/";
    $target_dir_pan = "assets/documents/pan/";
    $target_dir_death = "assets/documents/death/";
    $target_dir_birth = "assets/documents/birth/";
    $target_dir_widow = "assets/documents/widow/";
    $target_dir_income = "assets/documents/income/";
    $target_dir_tenth = "assets/documents/tenth/";
    $target_dir_plus2 = "assets/documents/plus2/";

    $name = "select $doc from tbl_documents where fid='$reg_id'";
    $name_result = mysqli_query($conn, $name);
    while($file = mysqli_fetch_assoc($name_result)){
        $document = $file[$doc];
    }

    $target = "target_dir_".$document;
    $target = $$target;

    echo $document;

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($target));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    readfile($target . $file[$document]);

    echo $file[$document];
    echo $target;
}

for($i=0; $i<$length; $i++){
    download($docs_needed[$i], $reg_id, $conn);
}

?>