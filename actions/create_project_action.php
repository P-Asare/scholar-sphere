<?php

    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, PATCH, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');


    include("../settings/connection.php");

    header('Content-Type: application/json');

    
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            try {
                $title = $data['title'];
                $description = $data['description'];
                $faculty_id = $data['faculty_id'];

                // Begin transaction
                mysqli_autocommit($conn, false);

                // Fetch department ID of the faculty user
                $sql_fetch_department = "SELECT dep_id FROM programs WHERE id IN (SELECT program_id FROM profile WHERE id IN (SELECT profile_id FROM users WHERE id = ?))";
                $stmt_fetch_department = mysqli_prepare($conn, $sql_fetch_department);
                if (!$stmt_fetch_department) {
                    throw new Exception("Error in preparing department fetch statement: " . mysqli_error($conn));
                }
                mysqli_stmt_bind_param($stmt_fetch_department, "i", $faculty_id);
                mysqli_stmt_execute($stmt_fetch_department);
                mysqli_stmt_bind_result($stmt_fetch_department, $department_id);
                mysqli_stmt_fetch($stmt_fetch_department);
                mysqli_stmt_close($stmt_fetch_department);

                // Generate current date and time
                $createdAt = date('Y-m-d H:i:s');

                // Set default status to "in_progress"
                $status = "in_progress";

                // Insert project with department_id fetched above
                $sql_insert_project = "INSERT INTO projects (title, description, createdAt, status, dep_id, faculty_id)
                                    VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_insert_project = mysqli_prepare($conn, $sql_insert_project);
                if (!$stmt_insert_project) {
                    throw new Exception("Error in preparing project insert statement: " . mysqli_error($conn));
                }
                mysqli_stmt_bind_param($stmt_insert_project, "ssssii", $title, $description, $createdAt, $status, $department_id, $faculty_id);
                $result = mysqli_stmt_execute($stmt_insert_project);
                if (!$result) {
                    throw new Exception("Error executing project insert statement: " . mysqli_error($conn));
                }

                // Commit transaction
                mysqli_commit($conn);

                // Project created successfully
                $response = array('success' => true, 'message' => 'Project created successfully');
                echo json_encode($response);

                // Clean up
                mysqli_stmt_close($stmt_insert_project);
                mysqli_close($conn);

            } catch (Exception $e) {
            $response = array('success' => false, 'message' => $e->getMessage());
            echo json_encode($response);
            }
        } else {
            http_response_code(405);
        }
    

?>
