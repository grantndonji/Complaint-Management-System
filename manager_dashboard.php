<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'manager') {
    header(header: "Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard | CUZ Complaint Management System</title>
    <link rel="stylesheet" href="manager_style.css">
    <style>
    :root {
        --brand-blue: #003366;
        --brand-light-blue: #0055A0;
        --white: #ffffff;
        --shadow: 0 6px 12px rgba(0,0,0,0.1);
        --radius: 8px;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background: 
            linear-gradient(rgba(0, 123, 255, 0.4), rgba(0, 123, 255, 0.4)),
            url('photos/cuz.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        color: var(--white);
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .dashboard-container {
        background-color: var(--white);
        color: var(--brand-blue);
        text-align: center;
        padding: 50px 40px;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        max-width: 500px;
        width: 90%;
    }

    .dashboard-container h2 {
        color: var(--brand-light-blue);
        margin-bottom: 40px;
        font-size: 1.8rem;
    }

    .menu {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .menu-btn {
        display: block;
        background-color: var(--brand-blue);
        color: var(--white);
        text-decoration: none;
        font-weight: bold;
        padding: 14px;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
    }

    .menu-btn:hover {
        background-color: var(--brand-light-blue);
        transform: translateY(-3px);
    }

    .logout {
        background-color: #b30000;
    }

    .logout:hover {
        background-color: #990000;
    }

    footer {
        position: absolute;
        bottom: 15px;
        width: 100%;
        text-align: center;
        color: var(--white);
        font-size: 0.9rem;
        opacity: 0.8;
    }

    @media (max-width: 600px) {
        .dashboard-container {
            padding: 35px 25px;
        }

        .menu-btn {
            font-size: 0.95rem;
        }
    }
</style>
  </head>
  <body>

    <div class="dashboard-container">
      <h2>Welcome, <?php echo htmlspecialchars(string: $_SESSION['full_name']); ?></h2>

      <div class="menu">
        <a href="view_all_complaints.php" class="menu-btn">View All Complaints</a>
        <a href="assign_task.php" class="menu-btn">Assign Tasks to Employees</a>
        <a href="logout.php" class="menu-btn logout">Logout</a> 
      </div>
    </div>

  </body>
</html>