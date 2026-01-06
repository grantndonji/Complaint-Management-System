<?php
session_start();
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'manager') {
        header(header: "Location: manager_dashboard.php");
    } elseif ($_SESSION['role'] == 'employee') {
        header(header: "Location: employee_dashboard.php");
    } else {
        header(header: "Location: student_dashboard.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CUZ Complaint Management System - Login/Register</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      background:
        linear-gradient(rgba(0, 123, 255, 0.4), rgba(0, 123, 255, 0.4)),
        url('photos/cuz.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      color: white;
    }

    nav {
      width: 100%;
      background-color: #0d47a1;
      color: white;
      padding: 15px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
      position: fixed;
      top: 0;
      left: 0;
      z-index: 10;
    }

    nav .logo {
      display: flex;
      font-size: 1.5em;
      font-weight: bold;
      letter-spacing: 1px;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 25px;
    }

    nav ul li a {
      text-decoration: none;
      color: white;
      font-weight: 500;
      transition: color 0.3s;
    }

    nav ul li a:hover {
      color: #90caf9;
    }

    .container {
      display: flex;
      width: 100%;
      flex: 1;
      margin-top: 80px;
      padding: 40px 0;
    }

    .left-side, .right-side {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 40px;
    }

    .left-side {
      text-align: center;
    }

    .left-side h1 {
      font-size: 2.5em;
      font-weight: bold;
      margin-bottom: 20px;
      letter-spacing: 1px;
      color: #fff;
    }

    .left-side p {
      font-size: 1.1em;
      max-width: 400px;
      color: #e3f2fd;
      font-weight: bold;
    }

    .right-side {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .auth-box-container {
      width: 80%;
      max-width: 400px;
      background-color: #0d47a1;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }

    .login-box, .register-box {
      width: 100%;
    }

    .hidden {
      display: none;
    }

    .login-box h2, .register-box h2 {
      color: #fff;
      text-align: center;
      margin-bottom: 20px;
    }

    label {
      color: #fff;
      display: block;
      margin-bottom: 5px;
    }

    input[type="text"],
    input[type="password"],
    input[type="email"],
    select {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 5px;
      margin-bottom: 15px;
      outline: none;
      color: #333;
    }

    button {
      width: 100%;
      padding: 10px;
      background-color: #2196f3;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover {
      background-color: #1565c0;
    }

    .remember {
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: #fff;
      font-size: 0.9em;
      margin-bottom: 15px;
    }

    .switch-link {
      text-align: center;
      margin-top: 20px;
      color: #fff;
    }

    .switch-link a {
      color: #90caf9;
      text-decoration: none;
      font-weight: bold;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        text-align: center;
      }
      nav ul {
        display: none;
      }
    }
    nav .logo {
    font-size: 1.5em;
    font-weight: bold;
    letter-spacing: 1px;  
    display: flex; 
    align-items: center;
    }
    nav .logo img {
      height: 40px;
      margin-right: 10px;
      border-radius: 2px;
    }
  </style>
</head>
<body>
  <nav>
    <div class="logo">
      <img src="photos/WhatsApp Image 2025-11-13 at 11.26.04.jpeg" alt="Cavendish University Logo">
      <span>Cavendish University Zambia</span>
    </div>
  </nav>

  <div class="container">
    <div class="left-side">
      <h1>Welcome to Cavendish University Zambia Complaint Management System</h1>
      <p>Here you can share your concerns, report issues, and get assistance directly from the university administration. Your feedback helps us improve the Cavendish experience.</p>
    </div>

    <div class="right-side">
      <div class="auth-box-container">
        <div class="login-box" id="loginView">
          <h2>Login</h2>
          <form id="loginForm" action="login.php" method="post">
            <label for="user_id">User ID</label>
            <input type="text" id="user_id" name="user_id" placeholder="Enter user ID" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter password" required>

            <div class="remember">
              <label><input type="checkbox" id="remember"> Remember me</label>
              <a href="#" style="color:#90caf9; text-decoration:none;">Forgot Password?</a>
            </div>

            <button type="submit" id="loginButton">Login</button>
          </form>

          <p class="switch-link">
            New user? <a href="#" id="showRegister">Register here</a>
          </p>
        </div>

        <div class="register-box hidden" id="registerView">
          <h2>Register New Account</h2>
          <form id="registerForm" action="register.php" method="post">
            <label for="reg_full_name">Full Name</label>
            <input type="text" id="reg_full_name" name="full_name" placeholder="Full Name" required>

            <label for="reg_user_id">User ID Number</label>
            <input type="text" id="reg_user_id" name="user_id" placeholder="Student or Employee ID" required>

            <label for="reg_email">Email</label>
            <input type="email" id="reg_email" name="email" placeholder="Email Address" required>

            <label for="reg_role">Role</label>
            <select name="role" id="reg_role" required>
              <option value="">Select Role</option>
              <option value="student">Student</option>
              <option value="employee">Employee</option>
              <option value="manager">Manager</option>
            </select>

            <label for="reg_password">Create Password</label>
            <input type="password" id="reg_password" name="password" required>

            <label for="reg_confirm_password">Confirm Password</label>
            <input type="password" id="reg_confirm_password" name="confirm_password" required>
            <p id="passwordMatchMessage" style="color: #ffcc00; font-size: 0.8em; margin-top: -10px; margin-bottom: 10px; display:none;">Passwords do not match.</p>

            <button type="submit" id="registerButton">Register</button>
          </form>

          <p class="switch-link">
            Already have an account? <a href="#" id="showLogin">Login</a>
          </p>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginView = document.getElementById('loginView');
        const registerView = document.getElementById('registerView');
        const showRegisterLink = document.getElementById('showRegister');
        const showLoginLink = document.getElementById('showLogin');
        const regPassword = document.getElementById('reg_password');
        const regConfirmPassword = document.getElementById('reg_confirm_password');
        const passwordMatchMessage = document.getElementById('passwordMatchMessage');
        const registerForm = document.getElementById('registerForm');

        function toggleViews(show, hide) {
            show.classList.remove('hidden');
            hide.classList.add('hidden');
        }

        showRegisterLink.addEventListener('click', function(e) {
            e.preventDefault();
            toggleViews(registerView, loginView);
        });

        showLoginLink.addEventListener('click', function(e) {
            e.preventDefault();
            registerForm.reset();
            passwordMatchMessage.style.display = 'none';
            regConfirmPassword.style.borderColor = '';

            toggleViews(loginView, registerView);
        });

        function checkPasswordMatch() {
            if (regPassword.value !== regConfirmPassword.value) {
                passwordMatchMessage.style.display = 'block';
                regConfirmPassword.setCustomValidity('Passwords do not match.');
            } else {
                passwordMatchMessage.style.display = 'none';
                regConfirmPassword.setCustomValidity('');
            }
        }

        regPassword.addEventListener('keyup', checkPasswordMatch);
        regConfirmPassword.addEventListener('keyup', checkPasswordMatch);
        registerForm.addEventListener('submit', function(e) {
             if (regPassword.value !== regConfirmPassword.value) {
                 e.preventDefault();
                 alert('Error: The confirmed password does not match the created password.');
             }
        });
    });
  </script>
</body>
</html>