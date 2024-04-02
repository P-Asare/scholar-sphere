<?php

    // Endpoint to handl retrieveal of user roles for dropdown on registration page

    include("../settings/connection.php");

    // set reponse headers
    header("Content-Type: application/json");

    $sql = "SELECT id, role FROM roles";
    $result = mysqli_query($conn, $sql);
    $user_roles = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode($user_roles);

?>