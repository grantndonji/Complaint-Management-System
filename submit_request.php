<?php
session_start();
include('db-connect.php');  

 
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student'){ 
    header(header: "Location: index.php");  
    exit; 
}

 
$student_id = $_SESSION['user_id'];  
$category = filter_var(value: $_POST['category'], filter: FILTER_SANITIZE_STRING); 
$description = filter_var(value: $_POST['description'], filter: FILTER_SANITIZE_STRING);

 
$sql = "INSERT INTO complaints (student_id, subject, description) VALUES (?, ?, ?)";
$stmt = $conn->prepare(query: $sql);

 
$stmt->bind_param("iss", $student_id,$category, $description);

if ($stmt->execute()) {
     
    $subject = "Complaint Submitted Successfully";
    $message = "<h2>Complaint Received</h2><p>Your complaint has been submitted successfully.</p>
                <p><strong>Category:</strong> {$category}<br><strong>Description:</strong> {$description}</p>";
     

    echo "<script>alert('Complaint submitted successfully!'); window.location='view_request.php';</script>";
} else {
     
    echo "<script>alert('Error submitting complaint! Please try again. Error: " . htmlspecialchars(string: $stmt->error) . "'); window.location='new_request.php';</script>";
}

$stmt->close();
$conn->close();
?>