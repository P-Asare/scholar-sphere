<?php

    // Endpoint to handle registration of user where the data sent is inserted

    include("../settings/connection.php");

    // set response headers
    header('Content-Type: application/json');

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $email = $data['email'];
        $fname = $data['fname'];
        $lname = $data['lname'];
        $dob = $data['dob'];
        $password = $data['password'];
        $confirm_password = $data['confirm-password'];
        $role = $data['role'];
        $program = $data['program'];
        $interests = $data['interest'];

        // check if user has an account
        $check_query = "SELECT id FROM users WHERE email = ?";
        $prepare = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($prepare, "s", $email);
        $confirmation = mysqli_stmt_execute($prepare);
        mysqli_stmt_store_result($prepare);
        mysqli_stmt_fetch($prepare);

        if(mysqli_stmt_num_rows($prepare) > 0){
            $response = array('success' => false, 'message' => 'Username already exists');
            echo json_encode($response);
            exit;
        }

        // Confirm that passwords match
        if($password != $confirm_password){
            $response = array('success' => false, 'message' => 'Passwords do not match');
            echo json_encode($response);
            exit;
        } else {
            $passwd = password_hash($password, PASSWORD_DEFAULT);
        }

        mysqli_begin_transaction($conn);

        // insert into profile table first
        $sql1 = "INSERT INTO profile (program_id) VALUES ($program)";
        $result1 = mysqli_query($conn, $sql1);

        // Get the profile id of the new user inserted
        $profile_id = mysqli_insert_id($conn);

        // Insert fields into users table
        $sql2 = "INSERT INTO users (email, fname, lname, password, dob, role_id, profile_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt, "sssssii", $email, $fname, $lname, $passwd, $dob, $role, $profile_id);
        $result2 = mysqli_stmt_execute($stmt);

        // Get the user id of the new user inserted
        $user_id = mysqli_insert_id($conn);

        // Insert user interests
        $sql_interests = "INSERT INTO user_interests (user_id, interest_id) VALUES (?, ?)";
        $stmt_interests = mysqli_prepare($conn, $sql_interests);

        foreach ($interests as $interest) {
            mysqli_stmt_bind_param($stmt_interests, "ii", $user_id, $interest);
            $result_interests = mysqli_stmt_execute($stmt_interests);
    
            // Rollback if insertion fails for any interest
            if (!$result_interests) {
                mysqli_rollback($conn);
                $response = array('success' => false, 'message' => 'Error registering user');
                echo json_encode($response);
                exit;
            }
        }

        // Check if all insertions were successful
        if($result1 && $result2 && $result_interests){
            mysqli_commit($conn);
            $response = array('success' => true, 'message' => 'User registered successfully');
            echo json_encode($response);
        } else {
            mysqli_rollback($conn);
            $response = array('success' => false, 'message' => 'Error registering user');
            echo json_encode($response);
        }
        
    } else {
        http_response_code(405);
    }    

?>