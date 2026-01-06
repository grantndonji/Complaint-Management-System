<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'employee') {
    header(header: "Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Employee Dashboard | CUZ Complaint Management System</title>
<link rel="stylesheet" href="styles/staff_style.css">
<style>
  body {
    background:
        linear-gradient(rgba(0, 123, 255, 0.4), rgba(0, 123, 255, 0.4)),
      url('photos/cuz.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: rgba(0, 0, 0, 0.4);
    background-attachment: fixed; 
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

h2 {
    color: #007bff;
    margin-bottom: 30px;
    font-size: 1.8em;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
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

@media (max-width: 500px) {
    .dashboard-container {
        padding: 30px 20px;
    }
    h2 {
        font-size: 1.5em;
    }
    .menu-btn {
        padding: 12px 15px;
        font-size: 1em;
    }
}
</style>
</head>
<body>

<div class="dashboard-container">
  <h2>Welcome, <?php echo htmlspecialchars(string: $_SESSION['full_name']); ?></h2>

  <div class="menu">
    <a href="view_assigned_tasks.php" class="menu-btn">View Assigned Tasks</a>
    <a href="update_task.php" class="menu-btn">Update Task Status</a>
    <a href="logout.php" class="menu-btn logout">Logout</a> 
  </div>
</div>

</body>
</html>