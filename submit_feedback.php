<?php
session_start();
include('db-connect.php'); 

 
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student'){ 
    header(header: "Location: ../index.php"); 
    exit; 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
     
    $complaint_id = filter_var(value: $_POST['complaint_id'], filter: FILTER_VALIDATE_INT); 
    $rating = filter_var(value: $_POST['rating'], filter: FILTER_VALIDATE_INT);
    $comments = filter_var(value: $_POST['comments'], filter: FILTER_SANITIZE_STRING);
    $student_id = $_SESSION['user_id'];

     
    if (!$complaint_id || !$rating || $rating < 1 || $rating > 5) {
        echo "<script>alert('Invalid rating or complaint data.'); window.location.href='feedback.php';</script>";
        exit;
    }
 
    $sql = "INSERT INTO feedback (complaint_id, student_id, rating, comments) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare(query: $sql);
    
     
    $stmt->bind_param( "iiis",  $complaint_id,  $student_id, $rating, $comments);

    if ($stmt->execute()) {
         
        echo "<script>alert('Thank you for your feedback!'); window.location='view_request.php';</script>";
    } else {
         
        echo "<script>alert('Error submitting feedback. You may have already submitted feedback for this complaint.'); window.location='feedback.php';</script>";
    }

    $stmt->close();
    $conn->close();

} else {
    header(header: "Location: feedback.php");
    exit;
}
?>