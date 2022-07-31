<?php

    require_once '../include/connect.php';
    session_start();

    if(!isset($_SESSION['superuser'])){
        header('Location: ./index.php');
        exit();
    }

    $service = "select serviceid from tbl_services";
    $service_result = mysqli_query($conn, $service);
    $serviceCount = mysqli_num_rows($service_result);

    $scheme = "select schemeid from tbl_cscheme";
    $scheme_result = mysqli_query($conn, $scheme);
    $schemeCount = mysqli_num_rows($scheme_result);

    $jobs = "select jobs_id from tbl_jobs";
    $jobs_result = mysqli_query($conn, $jobs);
    $jobsCount = mysqli_num_rows($jobs_result);

    $users = "select regid from tbl_registration";
    $users_result = mysqli_query($conn, $users);
    $usersCount = mysqli_num_rows($users_result)

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
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

            <div class="cardBox">
                <div class="cards">
                    <div>
                        <div class="numbers"><?php echo $usersCount ?></div>
                        <div class="cardName">Users</div>
                    </div>
                    <div class="iconBox">
                        <i class="bi bi-eye"></i>
                    </div>
                </div>

                <div class="cards">
                    <div>
                        <div class="numbers"><?php echo $jobsCount ?></div>
                        <div class="cardName">Jobs</div>
                    </div>
                    <div class="iconBox">
                    <i class="bi bi-back"></i>
                    </div>
                </div>

                <div class="cards">
                    <div>
                        <div class="numbers"><?php echo $serviceCount; ?></div>
                        <div class="cardName">Servicve</div>
                    </div>
                    <div class="iconBox">
                        <i class="bi bi-bounding-box-circles"></i>
                    </div>
                </div>

                <div class="cards">
                    <div>
                        <div class="numbers"><?php echo $schemeCount ?></div>
                        <div class="cardName">Schemes</div>
                    </div>
                    <div class="iconBox">
                        <i class="bi bi-book"></i>
                    </div>
                </div>
            </div>

        </div>

    </div>

</body>

</html>


