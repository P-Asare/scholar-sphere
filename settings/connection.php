<?php
    // Connect to database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "scholarsphere";

    $conn = new mysqli($servername, $username, $password, $database);

    if($conn->connect_error){
        die("connection failed".$conn->connect_error);
    }
?>