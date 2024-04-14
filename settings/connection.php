<?php
    // connect to database

    $servername = "193.203.166.76";
    $username = "u760419072_asare";
    $password = "p@L@l19asare";
    $database = "u760419072_testing";

    $conn = new mysqli($servername, $username, $password, $database);

    if($conn->connect_error){
        die("connection failed".$conn->connect_error);
    }
?>