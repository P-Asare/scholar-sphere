<?php

    // Endpoint to handle user request to join project

    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, PATCH, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

    include("../settings/connection.php");

    header('content-Type: application/json');

    if($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'OPTIONS'){

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        try {

            // Validate required entries
            if(!isset($data['userId']) || !isset($data['projectId'])){
                throw new Exception('Empty fields');
            }

            $userId = $data['userId'];
            $projectId = $data['projectId'];

            $sql = "INSERT into pending_collaborators (project_id, user_id) VALUES ($projectId, $userId)";
            $result = mysqli_query($conn, $sql);

            if($result){
                $response = array('success' => true, 'message' => 'Request Successful');
                echo json_encode($response);
            }
        } catch (Exception $e){
            // Handle exception with error message
            $response = array('success' => false, 'message' => $e->getMessage());
            echo json_encode($response);
        }
    } else {
        $response = array('success' => false, 'message' => 'Request should be a POST request');
        echo json_encode($response);
        http_response_code(405);
    }
?>