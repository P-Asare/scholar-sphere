<?php

    // Endpoint to fetch the possible interests of users for checkbox

    include('../settings/connection.php');

    header('Content-Type: application/json');

    $sql = "SELECT id, name FROM interests";
    $result = mysqli_query($conn, $sql);
    $user_roles = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode($user_roles);

?>