<?php

    require_once '../include/connect.php';

    session_start();

    if(!isset($_SESSION['superuser'])){
        header('Location: ./index.php');
        exit();
    }

    if(isset($_POST['submit'])){
        $scheme = mysqli_real_escape_string($conn, $_POST['scheme']);
        $subscheme = mysqli_real_escape_string($conn, $_POST['subscheme']);
        $scheme_desc = mysqli_real_escape_string($conn, $_POST['scheme_desc']);
        $criteria = mysqli_real_escape_string($conn, $_POST['criteria']);
        $documents = $_POST['docs'];
        $docs = implode(',', $documents);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        $status = 1;
        
        $sql = "INSERT INTO tbl_cscheme(name, subscheme, schemedescription, criteria, docs_needed, category, status) values('$scheme', '$subscheme', '$scheme_desc', '$criteria', '$docs', '$category', '$status')";
        
        if(mysqli_query($conn, $sql)){
            header('Location: ./insert_schemes.php?success');
        }else{
            echo mysqli_error($conn);
        }
       
    }
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $sql = "update tbl_cscheme set status=0 where schemeid=$id";
        if(mysqli_query($conn, $sql)){
            header('Location: ./insert_schemes.php?delete');
        }
    }

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <title>Insert Schemes</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="./css/css/bootstrap.min.css">
    <script src="./css/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

<!-- *********************************************SELECT MULTIPLE LIBRARIES -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>


    <!-- <script src="./css/jquery-3.6.0.min.js"></script> -->
</head>
<style>
    .mul-select{
        width: 70%;
    }
</style>
<body>
    
    <div class="container-fluid">
        <?php
        require './sidebar.php';
        ?>
            <!-- Inser update delete slides -->

            <div class="details">

                <!-- Update button and choose file -->

                <div class="update-top-bar">
                    <div class="cardHeader">
                        <h2>Insert New Scheme</h2>
                    </div>

                    <!-- Alerts -->
    <!-- For insert -->
                <?php
                    if(isset($_GET['success'])){
                        $message = "Schemes inserted successfully"
                ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong id="alert-status"> <?php echo $message ?> </strong>
                        <button type="button" style="float:right; background:transparent; border:none" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="bi bi-x-octagon"></i></span>
                        </button>
                    </div>
                <?php
                    }
                ?>
    <!-- For deletion -->
                <?php
                    if(isset($_GET['delete'])){
                        $message = "Schemes disabled successfully"
                ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong id="alert-status"> <?php echo $message ?> </strong>
                        <button type="button" style="float:right; background:transparent; border:none" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="bi bi-x-octagon"></i></span>
                        </button>
                    </div>
                <?php
                    }
                ?>
<!-- Alerts End -->

                    <div class="inputstyles">
                        <form action="" method="POST">
                            <!-- <input type="text" name="alertInput"><br> -->
                            <input type="text" name="scheme" placeholder="Scheme Name" pattern="[A-Za-z]+" title='Please neter a valid name ,Only characters are allowed'  required><br>
                            <input type="text" name="subscheme" placeholder="Sub Scheme Name" required><br>
                            <textarea rows = "3" cols = "60" name="scheme_desc" placeholder="Scheme Description" required></textarea><br>
                            <input type="text" name="criteria" placeholder="Eligibility Criteria" required><br>
                            <!-- <input type="text" name="docs" placeholder="Docs Needed (Separated by comma)" required><br> -->
                            <select class="mul-select" name="docs[]" multiple="true">
                                <option value="adhaar">Adhaar Card</option>
                                <option value="pan">Pan Card</option>
                                <option value="birth">Birth certificate</option>
                                <option value="death">Death Certificate</option>
                                <option value="tenth">10th Certificate</option>
                                <option value="plus2">+2 Certificate</option>
                                <option value="widow">Widow Certificate</option>
                                <option value="income">Income Certificate</option>
                            </select><br>

                            <!-- Script -->
                            <script>
                                $(document).ready(function(){
                                    $(".mul-select").select2({
                                        placeholder: " Docs Needed", //placeholder
                                        tags: true,
                                        tokenSeparators: ['/',',',';'," "] 
                                    });
                                })
                            </script>
                            <!-- Script End -->
                            <input type="text" name="category" placeholder="Age Category" required><br>
                            <input class="btn btn-outline-info" type="submit" name="submit" id="" value="Insert" required>
                        </form>
                    </div>
                </div>

                <div class="top-alert">
                    <div class="cardHeader">
                        <h2>Schemes</h2>
                    </div>
                    <table>
                        <thead>
                            <td>Scheme Name</td>
                            <td>Status</td>
                        </thead>
                        <tbody>
                            <?php
                                $sql = "select * from tbl_cscheme";
                                $result = mysqli_query($conn, $sql);
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" .$row['subscheme']. "</td>";
                                ?>
                                        <td>
                                            <button type='submit' name='delete' class='btn btn-outline-info'>
                                                    <a style="text-decoration: none" href="./insert_schemes.php?id=<?php echo $row['schemeid']?>">
                                                        <?php
                                                            if($row['status']==1){
                                                                echo 'Disable';
                                                            }else{
                                                                echo 'Disabled';
                                                            }
                                                        ?>
                                                    </a>
                                            </button>
                                        </td>
                                <?php
                                    echo "</tr>";
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