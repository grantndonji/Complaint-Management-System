<?php
session_start();
include('db-connect.php');

 
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'employee') {
    header(header: "Location: index.php");
    exit();
}

$employee_id = $_SESSION['user_id'];
$message = "";

if (isset($_POST['update'])) {
     
    $task_id = filter_var(value: $_POST['task_id'], filter: FILTER_VALIDATE_INT);
    $status = filter_var(value: $_POST['task_status'], filter: FILTER_SANITIZE_STRING);
    $notes = filter_var(value: $_POST['notes'], filter: FILTER_SANITIZE_STRING);
    
    if (!$task_id) {
        $message = "❌ Invalid Task ID provided.";
    } else {
         
        $sql = "UPDATE tasks SET task_status=?, notes=? WHERE task_id=? AND assigned_to=?";
        
        if ($stmt = $conn->prepare(query: $sql)) {
            $stmt->bind_param( "ssii",  $status,  $notes, $task_id, $employee_id);
            
            if ($stmt->execute()) {
                 
                if ($status === 'Completed') {
                     
                    $complaint_id_sql = "SELECT complaint_id FROM tasks WHERE task_id = ?";
                    $stmt_cid = $conn->prepare(query: $complaint_id_sql);
                    $stmt_cid->bind_param( "i",  $task_id);
                    $stmt_cid->execute();
                    $result_cid = $stmt_cid->get_result()->fetch_assoc();
                    $complaint_id = $result_cid['complaint_id'];
                    $stmt_cid->close();

                     
                    $update_complaint_sql = "UPDATE complaints SET status='Resolved' WHERE complaint_id=?";
                    $stmt_uc = $conn->prepare(query: $update_complaint_sql);
                    $stmt_uc->bind_param( "i", $complaint_id);
                    $stmt_uc->execute();
                    $stmt_uc->close();
                }
                $message = "✅ Task updated successfully!";
            } else {
                $message = "❌ Error updating task: " . htmlspecialchars(string: $stmt->error);
            }
            $stmt->close();
        } else {
            $message = "❌ Error preparing statement: " . htmlspecialchars(string: $conn->error);
        }
    }
}

 
$tasks = false;
$fetch_sql = "
  SELECT t.*, c.subject, c.description, c.status AS complaint_status
  FROM tasks t
  JOIN complaints c ON t.complaint_id = c.complaint_id
  WHERE t.assigned_to = ?
  ORDER BY t.task_id DESC
";

if ($stmt_fetch = $conn->prepare(query: $fetch_sql)) {
    $stmt_fetch->bind_param( "i",  $employee_id);
    $stmt_fetch->execute();
    $tasks = $stmt_fetch->get_result();
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Task | CUZ Complaint Management System</title>
<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    background-color: #007bff;  
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

.message {
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
    text-align: center;
    font-weight: bold;
}
.message:contains('✅') { 
    background-color: #d4edda;
    color: #007bff;
    border: 1px solid #c3e6cb;
}
.message:contains('❌') { 
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
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

td span[class^="status-"] {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.9em;
    font-weight: bold;
    display: inline-block;
}
.status-Assigned, .status-In-Progress {
    background-color: #007bff; 
    color: #ffffff;
}
.status-Completed {
    background-color: #007bff;  
    color: #ffffff;
}

td form {
    display: flex;
    flex-direction: column;
    gap: 5px;
    max-width: 250px;
}

td select {
    width: 100%;
    padding: 6px 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    background-color: #fff;
    appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="%23333" d="M7 10l5 5 5-5z"/></svg>');
    background-repeat: no-repeat;
    background-position: right 8px center;
}

td textarea {
    width: 100%;
    padding: 6px 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    resize: vertical;
}

td button[type="submit"] {
    background-color: #007bff; 
    color: white;
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
    transition: background-color 0.2s ease;
    margin-top: 5px;
}

td button[type="submit"]:hover {
    background-color: #170a61ff;
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
    background-color: #04054eff;
}
</style>
</head>
<body>

<div class="table-container">
  <h2>Update Task Status</h2>
  <?php if ($message != "") echo "<p class='message'>".htmlspecialchars(string: $message)."</p>"; ?>

  <table>
    <tr>
      <th>Task ID</th>
      <th>Complaint Subject</th>
      <th>Description</th>
      <th>Current Status</th>
      <th>Updates</th>
    </tr>
    <?php if ($tasks && $tasks->num_rows > 0): ?>
    <?php while($row = $tasks->fetch_assoc()): ?>
    <tr>
      <td><?php echo htmlspecialchars(string: $row['task_id']); ?></td>
      <td><?php echo htmlspecialchars(string: $row['subject']); ?></td>
      <td><?php echo htmlspecialchars(string: $row['description']); ?></td>
      <td><?php echo htmlspecialchars(string: $row['task_status']); ?></td>
      <td>
        <form method="POST">
          <input type="hidden" name="task_id" value="<?php echo htmlspecialchars(string: $row['task_id']); ?>">
          
          <select name="task_status" required>
            <option value="Assigned" <?php if($row['task_status'] == 'Assigned') echo 'selected'; ?>>Assigned</option>
            <option value="In Progress" <?php if($row['task_status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
            <option value="Completed" <?php if($row['task_status'] == 'Completed') echo 'selected'; ?>>Completed</option>
          </select>
          
          <textarea name="notes" rows="1" placeholder="Add Notes..."><?php echo htmlspecialchars(string: $row['notes'] ?? ''); ?></textarea>
          
          <button type="submit" name="update">Update</button>
        </form>
      </td>
    </tr>
    <?php endwhile; ?>
    <?php else: ?>
    <tr><td colspan="5">You have no tasks assigned yet.</td></tr>
    <?php endif; ?>
  </table>
  
  <?php if ($tasks) $tasks->close(); ?>
  <?php if (isset($stmt_fetch)) $stmt_fetch->close(); ?>
  
  <p><a href="employee_dashboard.php">Back to Dashboard</a></p>
</div>

</body>
</html>