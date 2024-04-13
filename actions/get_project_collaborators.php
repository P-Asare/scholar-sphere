<?php

    // Endpoint to retrieve user IDs, department IDs, and collaborator IDs from project collaborators

    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, PATCH, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

    include("../settings/connection.php");

    header('Content-Type: application/json');

    if($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'OPTIONS'){
        try {
            // Validate required entries
            if(!isset($_GET['proj_id'])){
                throw new Exception('Missing required parameter: project_id');
            }

            $projectId = $_GET['proj_id'];

            // Retrieve user IDs, department IDs, and collaborator IDs from project collaborators
            $sql = "SELECT pc.user_id, u.fname, u.lname, p.dep_id, pc.id AS collaborator_id
                    FROM project_collaborators pc
                    JOIN users u ON pc.user_id = u.id
                    JOIN projects p ON pc.project_id = p.id
                    WHERE pc.project_id = $projectId";
            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) > 0){
                $collaborators = mysqli_fetch_all($result, MYSQLI_ASSOC);
                echo json_encode($collaborators);
            } else {
                throw new Exception('No collaborators found for the specified project ID');
            }
        } catch (Exception $e){
            // Handle exception with error message
            $response = array('success' => false, 'message' => $e->getMessage());
            echo json_encode($response);
        }
    } else {
        $response = array('success' => false, 'message' => 'Request should be a GET request');
        echo json_encode($response);
        http_response_code(405);
    }
?>
