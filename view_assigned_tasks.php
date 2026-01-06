<?php
session_start();
include('db-connect.php');

 
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'employee') {
    header(header: "Location: index.php");  
    exit();
}

$employee_id = $_SESSION['user_id'];
$tasks = false;

 
$sql = "
  SELECT t.*, c.subject, c.description, u.full_name AS student_name
  FROM tasks t
  JOIN complaints c ON t.complaint_id = c.complaint_id
  JOIN users u ON c.student_id = u.user_id
  WHERE t.assigned_to = ?
  ORDER BY t.task_id DESC
";

if ($stmt = $conn->prepare(query: $sql)) {
    $stmt->bind_param( "i",  $employee_id);
    $stmt->execute();
    $tasks = $stmt->get_result();
     
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Assigned Tasks | CUZ Complaint Management System</title>
<style>
  
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: #007bff; 
}

.dashboard-container {
    background-color: rgba(255, 255, 255, 0.95);
    padding: 40px 30px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    width: 90%;
    max-width: 450px;
    text-align: center;
    backdrop-filter: blur(3px);
}

.table-container {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 1100px;
    margin-top: 20px;
    overflow-x: auto;  
}

h2 {
    color: #007bff;  
    text-align: center;
    margin-bottom: 25px;
    border-bottom: 3px solid #e0e0e0;
    padding-bottom: 10px;
    font-size: 1.8em;
}

 
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    min-width: 800px;  
}

th, td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    vertical-align: top;  
}

th {
    background-color: #007bff;  
    color: #ffffff;
    font-weight: bold;
    text-transform: uppercase;
    font-size: 14px;
}

tr:nth-child(even) {
    background-color: #f9f9f9; 
}

tr:hover {
    background-color: #e6f6f8;  
}

.menu {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.menu-btn {
    display: block;
    text-decoration: none;
    background-color: #007bff;
    color: white;
    padding: 15px 20px;
    border-radius: 8px;
    font-weight: bold;
    font-size: 1.1em;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.menu-btn:hover {
    background-color: #007bff;
    transform: translateY(-2px);
}

.menu-btn.logout {
    background-color: #dc3545;
    margin-top: 25px;
}

.menu-btn.logout:hover {
    background-color: #c82333;
}

 
p a {
    display: inline-block;
    background-color: #007bff;  
    color: white;
    padding: 10px 15px;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    margin-top: 15px;
}

p a:hover {
    background-color: #050441ff;
}

td .status-In-Progress {
    background-color: #007bff; 
    color: #ffffff;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.9em;
    font-weight: bold;
}
 
td .status-On-Hold {
    background-color: #ffc107;  
    color: #333;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.9em;
    font-weight: bold;
}

td .status-Resolved {
    background-color: #28a745;  
    color: #ffffff;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.9em;
    font-weight: bold;
}

@media (max-width: 768px) {
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
  <h2>My Assigned Tasks</h2>

  <table>
    <tr>
      <th>Task ID</th>
      <th>Student</th>
      <th>Complaint Subject</th>
      <th>Description</th>
      <th>Status</th>
      <th>Notes</th>
    </tr>
    <?php if ($tasks && $tasks->num_rows > 0): ?>
    <?php while($row = $tasks->fetch_assoc()): ?>
    <tr>
      <td><?php echo htmlspecialchars(string: $row['task_id']); ?></td>
      <td><?php echo htmlspecialchars(string: $row['student_name']); ?></td>
      <td><?php echo htmlspecialchars(string: $row['subject']); ?></td>
      <td><?php echo htmlspecialchars(string: $row['description']); ?></td>
      <td><?php echo htmlspecialchars(string: $row['task_status']); ?></td>
      <td><?php echo htmlspecialchars(string: $row['notes']); ?></td>
    </tr>
    <?php endwhile; ?>
    <?php else: ?>
    <tr><td colspan="6">You have no tasks assigned yet.</td></tr>
    <?php endif; ?>
  </table>
  
  <?php if ($tasks) $tasks->close(); ?>
  <?php if (isset($stmt)) $stmt->close(); ?>
  
  <p><a href="employee_dashboard.php">Back to Dashboard</a></p>
</div>

</body>
</html>