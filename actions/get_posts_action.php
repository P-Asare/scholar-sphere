<?php

    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, PATCH, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

    include("../settings/connection.php");

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        try{
            $sql = "SELECT post.id AS post_id, post.comment, post.project_id, projects.faculty_id AS user_id
                    FROM post
                    INNER JOIN projects ON post.project_id = projects.id";

            $result = mysqli_query($conn, $sql);

            if ($result) {
            
                $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
                if (!empty($posts)) {
                    echo json_encode($posts);
                } else {
                    $response = array('success' => false, 'message' => 'No posts found');
                    echo json_encode($response);
                }
            } else {
                throw new Exception("Error fetching posts");
            }

            mysqli_close($conn);

        } catch(Exception $e){
            $response = array('success' => false, 'message' => $e->getMessage());
            echo json_encode($response);
        }
    } else {
        http_response_code(405);
    }

?>