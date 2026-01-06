<?php
session_start();
include('db-connect.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student'){ 
    header(header: "Location: index.php");
    exit; 
}

$student_id = $_SESSION['user_id']; 

$sql = "SELECT c.complaint_id, c.subject, c.description 
        FROM complaints c
        WHERE c.student_id = ? AND c.status = 'Resolved' 
        AND c.complaint_id NOT IN (SELECT complaint_id FROM feedback WHERE student_id = ?)";

$stmt = $conn->prepare(query: $sql);
$stmt->bind_param("ii", $student_id,  $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Feedback</title><link rel="stylesheet" href="../style.css">
    <style>
        :root {
            --brand-blue: #004080;
            --brand-light-blue: #0055a0;
            --white: #ffffff;
            --form-bg: rgba(255,255,255,0.9);
            --card-shadow: 0 4px 8px rgba(0,0,0,0.1);
            --radius: 8px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #007bff;
            color: var(--white);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.5;
        }

        a {
            color: var(--white);
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .topbar {
            width: 100%;
            padding: 10px 20px;
            background-color: #007bff;
            display: flex;
            justify-content: flex-end;
        }

        .topbar .logout {
            background-color: #003060;
            padding: 8px 15px;
            border-radius: var(--radius);
            color: var(--white);
        }

        .topbar .logout:hover {
            background-color: #002050;
        }

        h2 {
            text-align: center;
            margin: 30px 20px 10px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px 40px;
        }

        form {
            background-color: var(--form-bg);
            color: #003060;
            border-radius: var(--radius);
            padding: 20px 25px;
            margin-bottom: 30px;
            box-shadow: var(--card-shadow);
            transition: transform 0.2s ease;
        }

        form:hover {
            transform: translateY(-3px);
        }

        form p {
            margin: 10px 0;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: var(--radius);
            font-size: 1rem;
            font-family: inherit;
        }

        select:focus, textarea:focus {
            outline: none;
            border-color: var(--brand-blue);
            box-shadow: 0 0 4px rgba(0,85,160,0.3);
        }

        button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #007bff;
            color: var(--white);
            font-size: 1rem;
            font-weight: bold;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            transition: background-color 0.2s ease, box-shadow 0.2s ease;
        }

        button:hover {
            background-color: var(--brand-light-blue);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .dashboard-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
        }

        @media (max-width: 600px) {
            h2 {
                font-size: 1.5rem;
                margin-top: 20px;
            }

            button {
                width: 100%;
                padding: 14px;
            }
        }

        select {
            color: blue;
            font-size: 1.2em;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
<h2>Rate Completed Complaints</h2>

<?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()):?>
    
    <form action="submit_feedback.php" method="POST" style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px;">
        <input type="hidden" name="complaint_id" value="<?php echo htmlspecialchars(string: $row['complaint_id']); ?>">
        
        <p><strong>Complaint #<?php echo htmlspecialchars(string: $row['complaint_id']); ?></strong> — <?php echo htmlspecialchars($row['subject']); ?></p>
        <p><?php echo htmlspecialchars(string: $row['description']); ?></p>
        
        <label>Rating:</label>
        <select name="rating" required>
            <option value="">Rate</option>
            <option value="1">★</option>
            <option value="2">★★</option>
            <option value="3">★★★</option>
            <option value="4">★★★★</option>
            <option value="5">★★★★★</option>
        </select>
        
        <label>Comments (Optional):</label>
        <textarea name="comments" rows="3"></textarea>
        
        <button type="submit">Submit Feedback</button>
    </form>
    
    <?php endwhile; ?>
<?php else: ?>
    <p>No resolved complaints awaiting your feedback.</p>
<?php endif; ?>

<p><a href="student_dashboard.php">Back to Dashboard</a></p>
</body>
</html>