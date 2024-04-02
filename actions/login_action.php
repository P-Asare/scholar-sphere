<?php

// Endpoint to handle login of users

include("../settings/connection.php");

// set response headers
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    try {
        // Verify required fields are provided
        if (!isset($data['email']) || !isset($data['password'])) {
            throw new Exception('Email and password are required fields');
        }

        $email = $data['email'];
        $password = $data['password'];

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt === false) {
            throw new Exception('Database error: ' . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        // Check if email address exists
        if (mysqli_num_rows($result) == 0) {
            $response = array('success' => false, 'message' => 'Invalid username or password');
            echo json_encode($response);
            exit;
        }

        $user = mysqli_fetch_assoc($result);

        // Check if user inputted correct password
        if (!password_verify($password, $user['password'])) {
            $response = array('success' => false, 'message' => 'Invalid username or password');
            echo json_encode($response);
            exit;
        }

        session_start(); // start user session

        // store session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role_id'];

        $response = array(
            'success' => true,
            'message' => 'Login successful',
            'session_data' => array(
                'user_id' => $user['id'],
                'role' => $user['role_id']
            )
        );

        // return session variables to regulate ui display
        echo json_encode($response);
    } catch (Exception $e) {
        // Handle exceptions
        $response = array('success' => false, 'message' => $e->getMessage());
        echo json_encode($response);
    }
} else {
    http_response_code(405);
}
?>
