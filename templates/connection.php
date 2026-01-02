<?php
    $server = "localhost";
    $host = "root";
    $password = "";
    $database = "project";

    $conn = mysqli_connect($server, $host, $password, $database);

    if(!$conn) {
        die("Database Connection Failed ". $conn->connect_error);
    }
?>