<?php
    session_start();

    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student'){ 
        header(header: "Location: index.php"); 
        exit; 
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <style>
        
        :root {
            --brand-blue: #003366;        
            --brand-light-blue: #0055a0;  
            --white: #ffffff;
            --gray-light: #f4f4f4;
            --shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: #007bff;
            padding: 20px 30px;
            text-align: center;
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--white);
            letter-spacing: 1px;
            box-shadow: var(--shadow);
        }

        .logout {
            position: absolute;
            top: 20px;
            right: 30px;
            background-color: #007bff;
            padding: 4px 8px;
            border-radius: var(--radius);
            text-decoration: none;
            color: var(--white);
            font-weight: bold;
            transition: background-color 0.3s ease;
            font-size: 20px;
        }

        .logout:hover {
            background-color: #001a3a;
        }

        .container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .dashboard-box {
            background-color: var(--white);
            color: var(--brand-blue);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 40px 50px;
            max-width: 500px;
            text-align: center;
            width: 100%;
        }

        .dashboard-box h2 {
            color: #007bff;
            margin-bottom: 10px;
        }

        .dashboard-box p {
            margin-bottom: 25px;
            font-size: 1rem;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            margin: 15px 0;
        }

        ul li a {
            display: block;
            background-color: #007bff;
            color: var(--white);
            text-decoration: none;
            font-weight: bold;
            padding: 12px;
            border-radius: var(--radius);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        ul li a:hover {
            background-color: var(--brand-light-blue);
            transform: translateY(-3px);
        }

        footer {
            background-color: #007bff;
            text-align: center;
            padding: 15px;
            font-size: 0.9rem;
            color: var(--white);
            box-shadow: var(--shadow);
        }

        @media (max-width: 600px) {
            .dashboard-box {
                padding: 30px 20px;
            }

            ul li a {
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>

    <header>
        Cavendish University Zambia - Student Dashboard
        <a href="logout.php" class="logout">Log out</a>
    </header>

    <div class="container">
        <div class="dashboard-box">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h2>
            <p>This is your dashboard. You can log complaints or check request status here.</p>
            <ul>
                <li><a href="new_request.php">Submit New Complaint</a></li>
                <li><a href="view_request.php">View My Complaints</a></li>
                <li><a href="feedback.php">Submit Feedback</a></li>
                <li><a href="update_profile.php">Update Profile</a></li>
            </ul>
        </div>
    </div>

    <footer>
        © <?php echo date("Y"); ?> Cavendish University Zambia. All rights reserved.
    </footer>

</body>
</html>