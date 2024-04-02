<?php

    // Endpoint to fetch the programs from database for a dropdown

    include("../settings/connection.php");

    // set response headers
    header('Content-Type: application/json');

    $sql = "SELECT id, name FROM programs";
    $result = mysqli_query($conn, $sql);
    $user_roles = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode($user_roles);

?>