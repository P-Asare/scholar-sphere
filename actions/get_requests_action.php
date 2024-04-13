<?php

header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, PATCH, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

include("../settings/connection.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    try {
        // Check if the project ID is provided in the URL parameters
        if (!isset($_GET['pr_id'])) {
            throw new Exception('Project ID is missing');
        }

        $pr_id = $_GET['pr_id'];

        // Construct the SQL query with the provided project ID
        $sql = "SELECT pc.id AS id, u.id AS user_id, u.fname AS fname, u.lname AS lname, d.id AS dep_id 
                FROM pending_collaborators pc 
                JOIN users u ON pc.user_id = u.id 
                JOIN profile p ON u.profile_id = p.id 
                JOIN programs pr ON p.program_id = pr.id 
                JOIN department d ON pr.dep_id = d.id 
                WHERE pc.project_id = $pr_id";

        // Execute the query
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $pending_collaborators = mysqli_fetch_all($result, MYSQLI_ASSOC);
            if (!empty($pending_collaborators)) {
                echo json_encode($pending_collaborators);
            } else {
                // If no pending collaborators found, return a message
                $response = array('success' => false, 'message' => 'No pending collaborators found');
                echo json_encode($response);
            }
        } else {
            throw new Exception("Error fetching pending collaborators: " . mysqli_error($conn));
        }

        mysqli_close($conn);

    } catch (Exception $e) {
        $response = array('success' => false, 'message' => $e->getMessage());
        echo json_encode($response);
    }
} else {
    http_response_code(405);
}

?>
