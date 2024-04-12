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
        // $userId = $_SESSION['user_id'];
        $userId = 10;

        $sql = "SELECT p.id, p.title, p.description, p.createdAt, p.status
                FROM projects p
                LEFT JOIN users u ON p.faculty_id = u.id
                WHERE p.status = 'in_progress' AND u.id = $userId";
        
        $result = mysqli_query($conn, $sql);

        if($result){

            $project = mysqli_fetch_all($result, MYSQLI_ASSOC);

            if(!empty($project)){
                echo json_encode($project);
            }
            // } else {
            //     $response = array('success' => true, 'message' => 'No projects');
            //     echo json_encode($response);
            // } 
        } else {
            throw new Exception("Error fetching project");
        }
        
    } catch (Exception $e) {
        echo json_encode("Fetching Error: ". $e->getMessage());
    }
} else {
    http_response_code(405);
}

?>