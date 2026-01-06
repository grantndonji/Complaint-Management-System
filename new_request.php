<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student'){ 
    header(header: "Location: index.php"); 
    exit; 
}
?>
<!DOCTYPE html>
<html>
  <head>
  <title>New Complaint</title>
  <link rel="stylesheet" href="../style.css">
  </head>
  <body>
    <div class="container form-container">
      <header>
        <h1>Cavendish University Zambia</h1>
        <h2>Complaint Submission Form</h2>
        <p>Please provide accurate and detailed information to help us address your concern quickly and effectively.</p>
      </header>
      <form action="submit_request.php" method="POST" class="complaint-form">
        <fieldset>
          <div class="form-group">
            <legend>Complaint Details</legend>
            <label for="category">Complaint Category <span class="required"></span></label>
            <select id="category" name="category" required>
              <option value="">Select a Category</option>
              <option value="Academics">Academics / Lecturer Issue</option>
              <option value="Finance">Fees / Finance / Payments</option>
              <option value="Facilities">Campus Facilities / Maintenance</option>
              <option value="IT">IT Support / E-Learning / Moodle</option>
              <option value="Misconduct">Staff / Student Misconduct</option>
              <option value="Other">Other (Specify in Description)</option>
            </select>
          </div>
          <div class="form-group">
            <label for="title">Complaint Subject/Title <span class="required"></span></label>
            <input type="text" id="title" name="title" required placeholder="Brief summary of the issue">
          </div>
          <div class="form-group">
            <label for="description">Detailed Description <span class="required"></span></label>
            <textarea id="description" name="description" rows="6" required placeholder="Describe the incident, date, and any parties involved."></textarea>
          </div>
          <div class="form-group">
            <label for="attachment">Supporting Attachment (Optional)</label>
            <input type="file" id="attachment" name="attachment" accept=".pdf,.doc,.docx,.jpg,.png">
            <small>Max file size: 5MB (PDF, Word, or Image files)</small>
          </div>
          <a href="view_request.php">
          <button type="submit" class="submit_complaint">Submit Complaint</button>
            </a>
        </fieldset>
        <a href="student_dashboard.php" class="btn secondary full-width-btn back-btn">
          Back to dashboard
        </a>
      </form>
    </div>
  </body>
</html>
<style>
  .submit_complaint {
  border-radius: 5px;
  width: 100px;
  align-items: right;
  text-align: center;
  color: white;
  background-color: #007bff;
  }
  .submit_complaint:hover {
  cursor: pointer;
  background-color: white;
  color: #1a237e;
  }

  body {
  background: 
    linear-gradient(rgba(0, 123, 255, 0.4), rgba(0, 123, 255, 0.4)),
    url('photos/cuz.jpg');
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
  }

  header {
  color: white;
  }
  .form-container {
  text-align: left;
  padding-bottom: 60px;
  }
  .form-container header {
  text-align: center;
  margin-bottom: 30px;
  }
  .complaint-form fieldset {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 30px;
  background-color: #f5f5fcff;
  }
  .complaint-form legend {
  font-size: 1.2em;
  font-weight: bold;
  color: #007bff;
  padding: 0 10px;
  text-align: center;
  }
  .form-group {
  margin-bottom: 15px;
  }
  .form-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: 600;
  color: #333;
  }
  .form-group input[type="text"],
  .form-group input[type="email"],
  .form-group select,
  .form-group textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  box-sizing: border-box;
  font-size: 1em;
  transition: border-color 0.3s;
  }
  .form-group input:focus,
  .form-group select:focus,
  .form-group textarea:focus {
  border-color: #1976d2;
  outline: none;
  }
  .form-group textarea {
  resize: vertical;
  }
  .required {
  color: red;
  font-weight: bold;
  margin-left: 2px;
  }
  .form-group small {
  display: block;
  color: #777;
  margin-top: 5px;
  }
  .full-width-btn {
  width: 100%;
  margin-bottom: 15px;
  display: block;
  text-align: center;
  }
  .back-btn {
  text-decoration: none;
  color: white;
  }
</style>
