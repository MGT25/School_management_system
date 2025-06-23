<!-- <?php

session_start();
if (!isset($_SESSION['role'])) {
  header("location: login.php ");
} elseif ($_SESSION['role'] == "student") {
  header("location: students_dashboard.php");
} elseif ($_SESSION['role'] == "teacher") {
  header("location: teacher_dashboard.php");
}

?> -->



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Course - School Management</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" />
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
  <link rel="stylesheet" href="css/signup.css">
  <style>

  </style>
</head>


<!-- <?php

require("partials/contodb.php");

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $name = $_POST['courseName'];
  $description = $_POST['courseDescription'];
  $teacher_name= $_POST['teacherName'];
  $teacher_phone = $_POST['teacherPhone'];
  $stmt = $conn->prepare("INSERT INTO `courses`(`name`, `description`, `teacher_name`, `teacher_phone`) VALUES (:name,:description,:teacher_name, :teacher_phone)");
  $stmt->bindParam(":name", $name);
  $stmt->bindParam(":description", $description);
  $stmt->bindParam(":teacher_name", $teacher_name);
  $stmt->bindParam(":teacher_phone", $teacher_phone);
  $stmt->execute();
  echo "data inserted successfully";
  header("location: admin_dashboard.php");
}




?> -->



<body>
  <div class="main-container">
    <div class="main-wrapper"> <!-- ✅ New wrapper for vertical centering -->
      <div class="container">
        <h2>Add Course</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">

          <input type="text" name="courseName"  placeholder="Course Name" required />

          <input type="text" name="courseDescription" placeholder="Course Description" required />

          <input type="text" name="teacherName" id="teacherSubject" placeholder="Teacher Name" required />
          
          <input type="tel" name="teacherPhone" placeholder="Teacher Phone No" required />
          
          <button type="submit" name="register">Add Course</button>

        </form>
      </div>
    </div>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script> -->
</body>

</html>