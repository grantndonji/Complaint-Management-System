<?php
session_start();
include('db-connect.php');

 
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header(header: "Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
     
    $full_name = filter_var(value: $_POST['full_name'], filter: FILTER_SANITIZE_STRING);
    $email = filter_var(value: $_POST['email'], filter: FILTER_SANITIZE_EMAIL);

     
    $sql = "UPDATE users SET full_name=?, email=? WHERE user_id=?";
    
    if ($stmt = $conn->prepare(query: $sql)) {
        $stmt->bind_param( "ssi",  $full_name,  $email, $user_id);
        
        if ($stmt->execute()) {
            $message = "✅ Profile updated successfully!";
            $_SESSION['full_name'] = $full_name;
            $_SESSION['email'] = $email;
        } else {
            $message = "❌ Error updating profile: " . htmlspecialchars(string: $stmt->error);
        }
        $stmt->close();
    } else {
        $message = "❌ Error preparing statement: " . htmlspecialchars(string: $conn->error);
    }
}

 
$user = [];
$fetch_sql = "SELECT full_name, email FROM users WHERE user_id = ?";
if ($stmt_fetch = $conn->prepare(query: $fetch_sql)) {
    $stmt_fetch->bind_param( "i",  $user_id);
    $stmt_fetch->execute();
    $result_fetch = $stmt_fetch->get_result();
    $user = $result_fetch->fetch_assoc();
    $stmt_fetch->close();
}
 
$user['full_name'] = $user['full_name'] ?? '';
$user['email'] = $user['email'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Profile | CUZ Complaint Management System</title>
<style>
     
body {
    font-family: 'Arial', sans-serif;
    background:
        linear-gradient(rgba(0, 123, 255, 0.4), rgba(0, 123, 255, 0.4)),
      url('photos/cuz.jpg');  
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;  
    color: #333;
    margin: 0;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

 
.form-container {
    background-color: #ffffff;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 450px;
}

h2 {
    color: #007bff;  
    text-align: center;
    margin-bottom: 25px;
    font-size: 1.8em;
    border-bottom: 2px solid #e0e0e0;
    padding-bottom: 10px;
}

 
form {
    display: flex;
    flex-direction: column;
    gap: 15px;  
}

label {
    font-weight: 600;
    color: #555;
    margin-bottom: -5px;
    display: block;
}

input[type="text"],
input[type="email"] {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s, box-shadow 0.3s;
}

input:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.2);
}

 
button[type="submit"] {
    background-color: #007bff;  
    color: white;
    padding: 14px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1.1rem;
    font-weight: 700;
    transition: background-color 0.3s ease;
    margin-top: 10px;
}

button[type="submit"]:hover {
    background-color: #08076bff;
}

 
.message {
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
    text-align: center;
    font-weight: bold;
     
    background-color: #fce8a5;  
    color: #333;
    border: 1px solid #ffc107;
}


.form-container > p {
    text-align: center;
    margin-top: 20px;
}

.form-container > p a {
    display: inline-block;
    color: #007bff;
    text-decoration: none;
    font-weight: 600;
    padding: 8px 15px;
    border: 1px solid transparent;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.form-container > p a:hover {
    color: #0056b3;
    text-decoration: underline;
}
</style>
</head>
<body>

<div class="form-container">
  <h2>Update Profile</h2>
  <?php if ($message != "") echo "<p class='message'>".htmlspecialchars(string: $message)."</p>"; ?>

  <form method="POST">
    <label>Full Name:</label>
    <input type="text" name="full_name" value="<?php echo htmlspecialchars(string: $user['full_name']); ?>" required>

    <label>Email:</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars(string: $user['email']); ?>" required>
    
    <button type="submit">Update Profile</button>
  </form>
  
  <p><a href="student_dashboard.php">Back to Dashboard</a></p>
</div>

</body>
</html>