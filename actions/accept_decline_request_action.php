<?php

    // Endpoint to handle moving pending collaborators to project collaborators or deleting them

    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, PATCH, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

    include("../settings/connection.php");

    header('Content-Type: application/json');

    if($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'OPTIONS'){
        try {
            // Validate required entries
            if(!isset($_GET['action']) || !isset($_GET['pen_id'])){
                throw new Exception('Missing required parameters');
            }

            $action = $_GET['action'];
            $pendingId = $_GET['pen_id'];

            // Check if action is valid
            if ($action != 1 && $action != 2) {
                throw new Exception('Invalid action value. Action value should be 1 or 2.');
            }

            // Move pending collaborator to project collaborator
            if ($action == 1) {
                $sql = "SELECT * FROM pending_collaborators WHERE id = $pendingId";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_assoc($result);
                    $projectId = $row['project_id'];
                    $userId = $row['user_id'];
                    $insertSql = "INSERT INTO project_collaborators (project_id, user_id) VALUES ($projectId, $userId)";
                    $deleteSql = "DELETE FROM pending_collaborators WHERE id = $pendingId";
                    mysqli_query($conn, $insertSql);
                    mysqli_query($conn, $deleteSql);
                    $response = array('success' => true, 'message' => 'Pending collaborator moved to project collaborator successfully');
                    echo json_encode($response);
                } else {
                    throw new Exception('Pending collaborator not found');
                }
            } else {
                // Delete pending collaborator
                $deleteSql = "DELETE FROM pending_collaborators WHERE id = $pendingId";
                $result = mysqli_query($conn, $deleteSql);
                if ($result) {
                    $response = array('success' => true, 'message' => 'Pending collaborator deleted successfully');
                    echo json_encode($response);
                } else {
                    throw new Exception('Failed to delete pending collaborator');
                }
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
