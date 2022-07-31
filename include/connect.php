<?php

    $host = "localhost";
    $passwd = "";
    $name = "root";
    $dbname = "employment";

    $conn = mysqli_connect($host, $name, $passwd, $dbname);

    if(!$conn){
        die("Database error".mysqli_error());
    }
?>