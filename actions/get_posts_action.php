<?php

    include("../settings/connection.php");

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

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
            $response = array('success' => false, 'message' => 'Error fetching posts');
            echo json_encode($response);
        }

        mysqli_close($conn);
    } else {
        http_response_code(405);
    }

?>