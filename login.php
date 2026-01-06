<?php
session_start();
include("db-connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];

    $query = "SELECT user_id, full_name, password, role, email FROM users WHERE user_id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $user_id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $hashed_password_from_db = $row['password'];
                if (password_verify($password, $hashed_password_from_db)) {
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['full_name'] = $row['full_name'];
                    $_SESSION['email'] = $row['email'];
                    $stmt->close();

                    if ($row['role'] == 'manager') {
                        header("Location: manager_dashboard.php");
                    } elseif ($row['role'] == 'employee') { 
                        header("Location: employee_dashboard.php");
                    } else {
                        header("Location: student_dashboard.php"); 
                    }
                    exit();
                }
            }
            $stmt->close();
        }
    } 
    echo "<script>alert('Invalid user ID or password!'); window.location.href='index.php';</script>";
    exit();

} else {
    header("Location: index.php");
    exit();
}
?>