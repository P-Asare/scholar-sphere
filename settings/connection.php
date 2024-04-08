<?php
    // Connect to database
    // $servername = "app-231-server";
    // $username = "hnwinzykdi";
    // $password = "3L5KGOH330EM85XX$";
    // $database = "farm_database";

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "scholarsphere";

    $conn = new mysqli($servername, $username, $password, $database);

    if($conn->connect_error){
        die("connection failed".$conn->connect_error);
    }
?>