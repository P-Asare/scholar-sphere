<?php

header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, PATCH, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

include("../settings/connection.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    try {
        
        $sql = "SELECT id, name, dep_id FROM programs";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $programs = mysqli_fetch_all($result, MYSQLI_ASSOC);
            echo json_encode($programs);
        } else {
            throw new Exception("Error fetching departments: " . mysqli_error($conn));
        }
    } catch (Exception $e) {
        echo json_encode("Fetching Error: " . $e->getMessage());
    }
} else {
    http_response_code(405);
}

?>
