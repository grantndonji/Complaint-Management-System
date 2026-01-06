<?php
session_start();
include('db-connect.php');

 
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'manager') {
    header(header: "Location: index.php");  
    exit();
}

 
$complaints = $conn->query(query: "
  SELECT c.complaint_id, c.subject, c.status, c.date_submitted, u.full_name AS student_name
  FROM complaints c
  JOIN users u ON c.student_id = u.user_id
  ORDER BY c.complaint_id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>All Complaints | CUZ Complaint Management System</title>
<link rel="stylesheet" href="manager_style.css">
<style>
  
body {
    font-family: 'Arial', sans-serif;
    background-color: #0056b3; 
    margin: 0;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
}

.table-container {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 1000px;
    margin-top: 20px;
    overflow-x: auto; 
}

h2 {
    color: #004080; 
    text-align: center;
    margin-bottom: 25px;
    border-bottom: 3px solid #e0e0e0;
    padding-bottom: 10px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    min-width: 600px;
}

th, td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #0056b3; 
    color: #ffffff;
    font-weight: bold;
    text-transform: uppercase;
    font-size: 14px;
}

tr:nth-child(even) {
    background-color: #f9f9f9;  
}

tr:hover {
    background-color: #eef4ff;  
    cursor: pointer;
}

td:nth-child(4) {
    font-weight: bold;
}

 
.status-Pending {
    background-color: #ffc107;  
    color: #333;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85em;
}

.status-In-Progress {
    background-color: #17a2b8;  
    color: #ffffff;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85em;
}
 
.status-Completed {
    background-color: #28a745;  
    color: #ffffff;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85em;
}

 
p a {
    display: inline-block;
    background-color: #0056b3;  
    color: white;
    padding: 10px 15px;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

p a:hover {
    background-color: #070642ff;
    color: #eaedf0ff;
}

@media (max-width: 768px) {
    body {
        padding: 10px;
    }
    .table-container {
        padding: 15px;
        margin-top: 10px;
    }
    th, td {
        padding: 10px;
        font-size: 13px;
    }
}
</style>
</head>
<body>

<div class="table-container">
  <h2>All Complaints</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>Student</th>
      <th>Subject</th>
      <th>Status</th>
      <th>Date Submitted</th>
    </tr>
    <?php while($row = $complaints->fetch_assoc()) { ?>
    <tr>
      <td><?php echo htmlspecialchars(string: $row['complaint_id']); ?></td>
      <td><?php echo htmlspecialchars(string: $row['student_name']); ?></td>
      <td><?php echo htmlspecialchars(string: $row['subject']); ?></td>
      <td><?php echo htmlspecialchars(string: $row['status']); ?></td>
      <td><?php echo htmlspecialchars(string: $row['date_submitted']); ?></td>
    </tr>
    <?php } ?>
  </table>
  
  <p><a href="manager_dashboard.php">Back to Dashboard</a></p>
</div>

</body>
</html>