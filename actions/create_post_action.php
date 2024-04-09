<?php

// Include database connection
include("../settings/connection.php");

// Set response headers
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // Extract post details from the data
        $comment = $data['comment'];
        $project_id = $data['project_id'];

        $sql = "INSERT INTO post (comment, project_id)
                VALUES (?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "si", $comment, $project_id);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $response = array('success' => true, 'message' => 'Post created successfully');
            echo json_encode($response);
        } else {
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
