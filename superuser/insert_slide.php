<?php

    require_once '../include/connect.php';
    // $encrypt = "UnitedMotivesProfessional"; 
    session_start();

    if(!isset($_SESSION['superuser'])){
        header('Location: ./index.php');
        exit();
    }

    if(isset($_POST['submit'])){
        $target_dir = "assets/slides/";

        $target = $target_dir . basename($_FILES['image']['name']);
        $image = $_FILES['image']['name'];

        $allowed_image_extension = array(
            "png",
            "jpg",
            "jpeg"
        );

        $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        if(! in_array($file_extension, $allowed_image_extension)){
            echo "<script>";
                echo "alert('Only images are allowed (Extensions: JPG, JPEG, PNG)')";
            echo "</script>";
        }else{
            $slideText = mysqli_real_escape_string($conn, $_POST['slideName']);

            move_uploaded_file($_FILES['image']['tmp_name'], $target);
            $sql = "insert into tbl_slide(slidename, slide) values('$slideText', '$image')";
            if(mysqli_query($conn, $sql)){
                header('Location: ./insert_slide.php?success');
            }
        }

    }


    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $sql = "delete from tbl_slide where id='$id'";
        if(mysqli_query($conn, $sql)){
            header('Location: ./insert_slide.php?delete');
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Slides</title>
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

                <!-- Update button and choose file -->

                <div class="update-top-bar">
                    <div class="cardHeader">
                        <h2>Insert new slide</h2>
                    </div>

<!-- Alerts -->
    <!-- For insert -->
                <?php
                    if(isset($_GET['success'])){
                        $message = "Slide inserted successfully"
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
                        $message = "Slide deleted successfully"
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
                        <form action="" method="POST" enctype="multipart/form-data">
                            <input type="text" pattern='[A-Za-z]+' name="slideName" placeholder='Slide Name' required><br>
                            <input type="file" name="image" required><br>
                            <input class="btn btn-outline-info" type="submit" name="submit" id="" value="Insert" required>
                        </form>
                    </div>
                </div>

                <div class="top-alert">
                    <div class="cardHeader">
                        <h2>Slides</h2>
                    </div>
                    <table>
                        <thead>
                            <td>Slide Name</td>
                            <td>Status</td>
                        </thead>
                        <tbody>
                            <?php
                                $sql = "select * from tbl_slide";
                                $result = mysqli_query($conn, $sql);
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" .$row['slidename']. "</td>";
                                ?>
                                        <td>
                                            <button type='submit' name='delete' class='btn btn-outline-info'>
                                                <a style="text-decoration: none" href="./insert_slide?id=<?php echo $row['id']?>">Delete</a>
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