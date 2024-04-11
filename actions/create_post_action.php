<?php

header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, PATCH, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Include database connection
include("../settings/connection.php");

// Set response headers
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // Extract post details from the data
        $comment = $data['comment'];
        $project_id = $data['project_id'];

        mysqli_begin_transaction($conn);

        $sql = "INSERT INTO post (comment, project_id)
                VALUES (?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "si", $comment, $project_id);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $response = array('success' => true, 'message' => 'Post created successfully');
            echo json_encode($response);
        } else {
            mysqli_rollback($conn);
            throw new Exception('Error creating post');
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    } else {
        throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    http_response_code(405);
    $response = array('success' => false, 'message' => $e->getMessage());
    echo json_encode($response);
}

?>
