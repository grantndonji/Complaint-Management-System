<?php
session_start();
include('db-connect.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'manager') {
    header(header: "Location: index.php");
    exit();
}

$message = "";

if (isset($_POST['assign'])) {
    $complaint_id = filter_var(value: $_POST['complaint_id'], filter: FILTER_VALIDATE_INT);
    $employee_id = filter_var(value: $_POST['employee_id'], filter: FILTER_VALIDATE_INT);

    if (!$complaint_id || !$employee_id) {
        $message = "❌ Invalid Complaint or Employee ID provided.";
    } else {
        $conn->begin_transaction();

        try {
            $check_sql = "SELECT task_id FROM tasks WHERE complaint_id = ?";
            $stmt_check = $conn->prepare(query: $check_sql);
            $stmt_check->bind_param( "i",  $complaint_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                $message = "❌ This complaint is already assigned.";
                $conn->rollback();
            } else {
                $insert_sql = "INSERT INTO tasks (complaint_id, assigned_to) VALUES (?, ?)";
                $stmt_insert = $conn->prepare(query: $insert_sql);
                $stmt_insert->bind_param( "ii",  $complaint_id,  $employee_id);
                $stmt_insert->execute();

                $update_sql = "UPDATE complaints SET status = 'In Progress' WHERE complaint_id = ?";
                $stmt_update = $conn->prepare(query: $update_sql);
                $stmt_update->bind_param(  "i",  $complaint_id);
                $stmt_update->execute();

                $conn->commit();
                $message = "✅ Task assigned successfully!";
            }
        } catch (Exception $e) {
            $conn->rollback();
            $message = "❌ Assignment failed: " . $e->getMessage();
        }
    }
}

$complaints = $conn->query(query: "SELECT complaint_id, subject FROM complaints WHERE status='Pending' ORDER BY date_submitted DESC");

$employees = $conn->query(query: "SELECT user_id, full_name FROM users WHERE role='employee' ORDER BY full_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Assign Task | CUZ Complaint Board</title>
<link rel="stylesheet" href="styels/manager_style.css">
<style>

body {
    font-family: 'Arial', sans-serif;
    background-color: #004080;
    margin: 0;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

h2 {
    color: #004080;
    text-align: center;
    margin-bottom: 25px;
    border-bottom: 3px solid #e0e0e0;
    padding-bottom: 10px;
}


.form-container {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 500px;
}

form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

label {
    font-weight: bold;
    color: #333;
    margin-bottom: 5px;
    display: block;
}

select {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    background-color: #f9f9f9;
    appearance: none; 
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="%23333" d="M7 10l5 5 5-5z"/></svg>');
    background-repeat: no-repeat;
    background-position: right 10px center;
}

select:focus {
    border-color: #0056b3;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 86, 179, 0.2);
}

button[type="submit"] {
    background-color: #007bff;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;
    font-weight: bold;
    transition: background-color 0.3s ease;
    margin-top: 20px;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

.message {
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
    text-align: center;
    font-weight: bold;
    color: #333;
}


.message:contains('✅') {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.message:contains('❌') {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.form-container > p {
    text-align: center;
    margin-top: 20px;
}

.form-container > p a {
    display: inline-block;
    color: #6c757d;
    text-decoration: none;
    font-weight: 500;
    padding: 5px 10px;
    border-radius: 4px;
    transition: color 0.3s ease;
}

.form-container > p a:hover {
    color: #0056b3;
    text-decoration: underline;
}
</style>
</head>
<body>

<div class="form-container">
  <h2>Assign Tasks</h2>
  <?php if ($message != "") echo "<p class='message'>".htmlspecialchars(string: $message)."</p>"; ?>

  <form method="POST">
    <label>Select Complaint:</label>
    <select name="complaint_id" required>
      <option value=""> Select Complaint </option>
      <?php while($c = $complaints->fetch_assoc()) {
        echo "<option value='".htmlspecialchars(string: $c['complaint_id'])."'>".htmlspecialchars(string: $c['subject'])."</option>";
      } ?>
    </select>

    <label>Select Employee:</label>
    <select name="employee_id" required>
      <option value=""> Select Employee </option>
      <?php while($e = $employees->fetch_assoc()) {
        echo "<option value='".htmlspecialchars(string: $e['user_id'])."'>".htmlspecialchars(string: $e['full_name'])."</option>";
      } ?>
    </select>
    
    <button type="submit" name="assign">Assign Task</button>
  </form>
  
  <p><a href="manager_dashboard.php">Back to Dashboard</a></p>
</div>

</body>
</html>