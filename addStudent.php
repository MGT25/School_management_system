<?php

session_start();
if (!isset($_SESSION['role'])) {
  header("location: login.php ");
} elseif ($_SESSION['role'] == "student") {
  header("location: students_dashboard.php");
} elseif ($_SESSION['role'] == "teacher") {
  header("location: teacher_dashboard.php");
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Student - School Management</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
  <link rel="stylesheet" href="css/signup.css">
  <style>

  </style>
</head>


<?php

require("partials/contodb.php");

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $name = $_POST['studentName'];
  $email = $_POST['studentEmail'];
  $phone = $_POST['studentPhone'];
  $subject = $_POST['studentClass'];
  $enroll_password = $_POST['enroll_password'];

  $stmt = $conn->prepare("INSERT INTO `students`(`name`, `email`, `phone`, `class`, assigned_password) VALUES (:studentName,:studentEmail,:studentPhone,:studentClass, :assigned_password)");
  $stmt->bindParam(":studentName", $name);
  $stmt->bindParam(":studentEmail", $email);
  $stmt->bindParam(":studentPhone", $phone);
  $stmt->bindParam(":studentClass", $subject);
  $stmt->bindParam(":assigned_password", $enroll_password);
  $stmt->execute();
  echo "data inserted successfully";
  header("location: admin_dashboard.php");

  $username = strtolower(str_replace(' ', '', $name)); // Create username from name
  $password = password_hash('defaultpassword', PASSWORD_DEFAULT); // Set a default password

  // Insert into users table
  $userStmt = $conn->prepare("INSERT INTO `users` (username, password, role, email) VALUES (:username, :password, 'student', :email)");
  $userStmt->bindParam(":username", $username);
  $userStmt->bindParam(":password", $password);
  $userStmt->bindParam(":email", $email);
  $userStmt->execute();

  // Get the user_id
  $user_id = $conn->lastInsertId();

  // Update the student record with the user_id
  $updateStmt = $conn->prepare("UPDATE `students` SET user_id = :user_id WHERE id = :student_id");
  $updateStmt->bindParam(":user_id", $user_id);
  $updateStmt->bindParam(":student_id", $student_id); // You'll need to get this from the first insert
  $updateStmt->execute();
}




?>



<body>
  <div class="main-container">
    <div class="main-wrapper"> <!-- ✅ New wrapper for vertical centering -->
      <div class="container">
        <h2>Add Student</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">

          <input type="text" name="studentName" placeholder="Student Name" required />

          <input type="email" name="studentEmail" placeholder="Student Email" required />

          <input type="tel" name="studentPhone" placeholder="Phone No" required />

          <select name="studentClass" id="studentClass">
            <option value="7th">7th</option>
            <option value="8th">8th</option>
            <option value="9th">9th</option>
            <option value="10th">10th</option>
          </select>

          <input type="text" name="enroll_password" id="enroll_password" placeholder="Assign Password" required/>

          <button type="submit" name="register">Add student</button>

        </form>
      </div>
    </div>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script> -->
</body>

</html>