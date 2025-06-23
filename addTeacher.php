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
  <title>Add Teacher - School Management</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" />
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
  <link rel="stylesheet" href="css/signup.css">
  <style>

  </style>
</head>


<?php

require("partials/contodb.php");

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  
  $name = $_POST['teacherName'];
  $email = $_POST['teacherEmail'];
  $phone = $_POST['teacherPhone'];
  $subject = $_POST['teacherSubject'];
  $teacherPhoto = $_POST['teacherPhoto'];

  $teacherStmt= $conn->prepare("SELECT user_id FROM `users` WHERE teacherName=:teacherName");
  $teacherStmt->bindParam(":teacherName", $teacherName);
  $teacherStmt->execute();
  $result= $teacherStmt->setFetchMode(PDO::FETCH_ASSOC);
  $teacher_user_id= $teacherStmt->fetchAll();
  print_r($teacher_user_id);
  

  $stmt = $conn->prepare("INSERT INTO `teachers`(`user_id` ,`name`, `email`, `phone`, `course_name`, `teacher_img`) VALUES (:user_id,:name,:email,:phone,:subject, :teacherphoto)");
  $stmt->bindParam(":name", $name); 
  $stmt->bindParam(":email", $email);
  $stmt->bindParam(":phone", $phone);
  $stmt->bindParam(":subject", $subject);
  $stmt->bindParam(":teacherphoto", $teacherPhoto);
  $stmt->execute();
  echo "data inserted successfully";
  header("location: admin_dashboard.php");
}




?>



<body>
  <div class="main-container">
    <div class="main-wrapper"> <!-- ✅ New wrapper for vertical centering -->
      <div class="container">
        <h2>Add Teacher</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">

          <input type="text" name="teacherName" placeholder="Teacher Name" required />

          <input type="email" name="teacherEmail" placeholder="Teacher Email" required />

          <input type="tel" name="teacherPhone" placeholder="Phone No" required />

          <input type="text" name="teacherSubject" id="teacherSubject" placeholder="Subject Name" required />
          
          <button type="submit" name="register">Add Teacher</button>

        </form>
      </div>
    </div>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
    <script>
      $(document).ready(function() {
        $('select').select2(); // if you're using Select2
      });
    </script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script> -->
</body>

</html>