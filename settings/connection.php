<?php
    // connect to database

    $servername = "";
    $username = "";
    $password = "";
    $database = "";

    $conn = new mysqli($servername, $username, $password, $database);

    if($conn->connect_error){
        die("connection failed".$conn->connect_error);
    }
?>