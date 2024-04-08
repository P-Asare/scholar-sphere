<?php

// Include database connection
include("../settings/connection.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $sql = "SELECT users.id, users.email, users.fname, users.lname, department.name AS department
            FROM users
            INNER JOIN profile ON users.profile_id = profile.id
            INNER JOIN programs ON profile.program_id = programs.id
            INNER JOIN department ON programs.dep_id = department.id";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
            echo json_encode($users);
        } else {
            $response = array('success' => false, 'message' => 'No users found');
            echo json_encode($response);
        }
    } else {
        $response = array('success' => false, 'message' => 'Error fetching users');
        echo json_encode($response);
    }

    mysqli_close($conn);
} else {

    http_response_code(405);
}

?>