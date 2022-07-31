<?php

    require_once '../include/connect.php';

    session_start();

    if(!isset($_SESSION['superuser'])){
        header('Location: ./index.php');
        exit();
    }

    if(isset($_POST['submit'])){
        $text = mysqli_real_escape_string($conn, $_POST['alert-text']);

        $sql = "INSERT INTO tbl_alert(message, status) values('$text', 1)";
        
        if(mysqli_query($conn, $sql)){
            echo "<script>";
                echo "alert('Inserted Successfully')";
            echo "</script>";
        }
    }
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $sql = "update tbl_alert set status=0 where atid='$id'";
        if(mysqli_query($conn, $sql)){
            echo "<script>";
                echo "alert('Disabled Successfully')";
            echo "</script>";
        }
        header('Location: ./insert_alert.php');
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Alerts</title>
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
                        <h2>Insert new alert</h2>
                    </div>
                    <div class="inputstyles">
                        <form action="" method="POST">
                            <!-- <input type="text" name="alertInput"><br> -->
                            <textarea rows = "5" cols = "60" name="alert-text" placeholder='Alert Text' required></textarea><br>
                            <input class="btn btn-outline-info" type="submit" name="submit" id="" value="Insert" required>
                        </form>
                    </div>
                </div>

                <div class="top-alert">
                    <div class="cardHeader">
                        <h2>Recent Alerts</h2>
                    </div>
                    <table>
                        <thead>
                            <td>Details</td>
                            <td>Status</td>
                        </thead>
                        <tbody>
                            <?php
                                $sql = "select * from tbl_alert";
                                $result = mysqli_query($conn, $sql);
                                while($row = mysqli_fetch_array($result)){
                                    $stat = $row['status'];
                                    echo "<tr>";
                                        echo "<td>" .$row['message']. "</td>";
                                ?>
                                        <td>
                                                
                                            <?php
                                                if($stat==0){
                                                    ?>
                                                    <button type='submit' name='disable' class='btn btn-sm btn-danger'>
                                                        <a style="text-decoration: none; color:white" href="./insert_alert.php?id=<?php echo $row['atid'] ?>">Disabled</a>
                                                    </button>
                                                <?php
                                                }else{
                                                ?>
                                                    <button type='submit' name='disable' class='btn btn-outline-info'>
                                                        <a style="text-decoration: none;" href="./insert_alert.php?id=<?php echo $row['atid'] ?>">Disable</a>
                                                    </button>
                                                <?php
                                                }
                                            ?>

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