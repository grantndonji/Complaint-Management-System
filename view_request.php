<?php
session_start();
include('db-connect.php');

 
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student'){ 
    header(header: "Location: index.php");  
    exit; 
}

$student_id = $_SESSION['user_id'];  
$result = false;

 
$sql = "SELECT complaint_id, subject, description, status, date_submitted 
        FROM complaints 
        WHERE student_id = ? 
        ORDER BY date_submitted DESC";

if ($stmt = $conn->prepare(query: $sql)) {
    $stmt->bind_param( "i",  $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
     
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Complaints</title>
    <style>
         
body {
    font-family: 'Arial', sans-serif;
    background-color: #f0f2f5; 
    color: #333;
    margin: 0;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 100vh;
}
 
.table-container {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    width: 100%;
    max-width: 900px;  
    margin-top: 20px;
    overflow-x: auto;
}

h2 {
    color: #007bff;  
    text-align: center;
    margin-bottom: 25px;
    font-size: 1.8em;
    border-bottom: 2px solid #e0e0e0;
    padding-bottom: 10px;
}
 
a[href*="logout.php"] {
    float: right;
    color: #dc3545;  
    text-decoration: none;
    font-weight: bold;
    padding: 5px 10px;
    border: 1px solid #dc3545;
    border-radius: 5px;
    transition: background-color 0.3s;
}

a[href*="logout.php"]:hover {
    background-color: #dc3545;
    color: white;
}
 
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    min-width: 550px;
}

th, td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    font-size: 0.95em;
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
    background-color: #eaf6ff;
}
 
td span[class^="status-"] {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85em;
    font-weight: bold;
    display: inline-block;
}
 
.status-Pending, .status-Assigned {
    background-color: #ffc107; 
    color: #333;
}

.status-In-Progress {
    background-color: #007bff;  
    color: #ffffff;
}
 
.status-Completed, .status-Resolved {
    background-color: #28a745;  
    color: #ffffff;
}
 
p a {
    display: inline-block;
    color: #007bff;
    text-decoration: none;
    font-weight: 600;
    margin-top: 20px;
    padding: 8px 15px;
    border: 1px solid #007bff;
    border-radius: 5px;
    transition: all 0.3s ease;
}

p a:hover {
    background-color: #007bff;
    color: white;
}
    </style>
</head>
<body>
<h2>My Complaints</h2>

<table border="1">
<tr><th>ID</th><th>Subject</th><th>Description</th><th>Status</th><th>Date</th></tr>
<?php if ($result && $result->num_rows > 0): ?>
<?php while($row = $result->fetch_assoc()){ ?>
<tr>
<td><?php echo htmlspecialchars(string: $row['complaint_id']); ?></td>
<td><?php echo htmlspecialchars(string: $row['subject']); ?></td>
<td><?php echo htmlspecialchars(string: $row['description']); ?></td>
<td><?php echo htmlspecialchars(string: $row['status']); ?></td>
<td><?php echo htmlspecialchars(string: $row['date_submitted']); ?></td>
</tr>
<?php } ?>
<?php else: ?>
<tr><td colspan="5">You have not submitted any complaints yet.</td></tr>
<?php endif; ?>
</table>

<?php if ($result) $result->close(); ?>
<?php if (isset($stmt)) $stmt->close(); ?>

<p><a href="student_dashboard.php">Back to Dashboard</a></p>
</body>
</html>