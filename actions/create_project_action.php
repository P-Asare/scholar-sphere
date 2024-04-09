<?php

    include("../settings/connection.php");

    header('Content-Type: application/json');

    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            $title = $data['title'];
            $description = $data['description'];
            $createdAt = $data['createdAt'];
            $department_id = $data['department_id'];
            $faculty_id = $data['faculty_id'];

            // Set default status to "in_progress"
            $status = "in_progress";

            $sql = "INSERT INTO projects (title, description, createdAt, dep_id, faculty_id, status)
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param($stmt, "ssssis", $title, $description, $createdAt, $department_id, $faculty_id, $status);
            $result = mysqli_stmt_execute($stmt);

            if ($result) {
                $response = array('success' => true, 'message' => 'Project created successfully');
                echo json_encode($response);
            } else {
                throw new Exception('Error creating project');
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
