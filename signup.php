<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - School Management</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
  <link rel="stylesheet" href="css/signup.css">
  <style>

</style>
</head>


<?php

require("partials/contodb.php");

$success = false;
$err = false;
$successMsg = "";
$errMsg = "";
if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $username = $_POST["signupUsername"];
  $password = $_POST["signinPassword"];

  $stmt= $conn->prepare("SELECT * FROM `users` WHERE username=:username AND password=:password");
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':password', $password);
  $stmt->execute();
  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
  $users = $stmt->fetchAll();

  if(count($users)>0){
    echo "user already exists";
  }
  elseif (isset($_POST["signupUsername"])) {
    $username = $_POST['signupUsername'];
    $email = $_POST['signupEmail'];
    $password = $_POST['signinPassword'];
    $cpassword = $_POST['cpassword'];
    $role = $_POST['role'];
    if ($password == $cpassword) {
      $stmt = $conn->prepare("INSERT INTO `users`(`username`, `email`, `password`, `role`) VALUES (:username,:email,:password,:role)");
      $stmt->bindParam(":username", $username);
      $stmt->bindParam(":email", $email);
      $stmt->bindParam(":password", $password);
      $stmt->bindParam(":role", $role);
      $stmt->execute();
      $success = true;
      $successMsg = "data inserted successfully";
    } else {
      $err = true;
      $errMsg = "passwords does not match";
    }
  }
}



?>



<body>
  <div class="main-container">

    <div class="alerts">
      <?php 
        if ($success){
        echo' <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong>  '.$successMsg.'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }   
        if ($err){
        echo' <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Error!</strong>  '.$errMsg.'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        } 
      ?> 

    </div>

    <div class="main-wrapper"> <!-- ✅ New wrapper for vertical centering -->
      <div class="container">
        <h2>Create Your Account</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
          <input type="text" name="signupUsername" placeholder="Username" required />
          <input type="email" name="signupEmail" placeholder="Email" required />
          <input type="password" name="signinPassword" placeholder="Password" required />
          <input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password" required />
          <small class="note">Make sure to enter same password</small>
          <select name="role" class="role" required>
            <option value="">Select Role</option>
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
          </select>
          <button type="submit" name="register">Register</button>
        </form>
        <p class="link">Already have an account? <a href="login.php">Login here</a></p>
      </div>
    </div>

  </div>

  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script> -->
</body>

</html>