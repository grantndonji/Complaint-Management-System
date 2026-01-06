<?php
session_start();
include("db-connect.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = filter_var(value: $_POST['full_name'], filter: FILTER_SANITIZE_STRING);
    $user_id = filter_var(value: $_POST['user_id'], filter: FILTER_SANITIZE_STRING);
    $email = filter_var(value: $_POST['email'], filter: FILTER_SANITIZE_EMAIL);
    $role = $_POST['role'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($full_name) || empty($user_id) || empty($email) || empty($role) || empty($password) || empty($confirm_password)) {
        echo "<script>alert('All fields are required.'); window.location.href='index.php';</script>";
        exit;
    }

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match.'); window.location.href='index.php';</script>";
        exit;
    }

    if ($role !== 'student' && $role !== 'employee') { 
        echo "<script>alert('Invalid role selected.'); window.location.href='index.php';</script>";
        exit;
    }

    $hashed_password = password_hash(password: $password, algo: PASSWORD_DEFAULT);

    $check_query = "SELECT user_id FROM users WHERE user_id = ? OR email = ?";
    if ($stmt_check = $conn->prepare(query: $check_query)) {
        $stmt_check->bind_param( "ss",  $user_id,  $email);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows > 0) {
            echo "<script>alert('User ID or Email already registered. Please login.'); window.location.href='index.php';</script>";
            $stmt_check->close();
            exit;
        }
        $stmt_check->close();
    } else {
        echo "<script>alert('Registration failed: Database connection error (Check preparation).'); window.location.href='index.php';</script>";
        exit;
    }

    $insert_query = "INSERT INTO users (user_id, full_name, email, password, role) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare(query: $insert_query)) {

        $stmt->bind_param( "sssss",  $user_id,  $full_name, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! You can now log in.'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Registration failed due to a database error. Please try again.'); window.location.href='index.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Registration failed: Could not prepare statement.'); window.location.href='index.php';</script>";
    }

    $conn->close();

} else {
    header(header: "Location: index.php");
    exit;
}
?>