<?php
require("partials/contodb.php");
session_start();

if($_SERVER["REQUEST_METHOD"] === "POST"){
  if (isset($_POST['loginSubmit'])){
    $username = $_POST["loginUsername"];
    $password = $_POST["loginPassword"];

    $stmt= $conn->prepare("SELECT * FROM `users` WHERE username=:username AND password=:password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $users = $stmt->fetchAll();

    $stmtS= $conn->prepare("SELECT * FROM `students` WHERE name = :student_name AND assigned_password= :assigned_password");
    $stmtS-> bindParam(":student_name", $username);
    $stmtS-> bindParam(":assigned_password", $password);
    $stmtS->execute();
    $resultS = $stmtS->setFetchMode(PDO::FETCH_ASSOC);
    $usersS = $stmtS->fetchAll();
    if( count($users) ){
      $user= $users[0];
        $_SESSION['role']=$user["role"];
        $_SESSION['is_logged_in']=true;
        $_SESSION['user_id']= $user['user_id'];
        $_SESSION['username'] = $user['username'];

        // if($user["role"] === "teacher"){
          
        //   $teacher_id =$_SESSION['user_id'];
        //   $stmtT= $conn->prepare("SELECT course_name,teacher_id FROM `enrolled_teachers` WHERE teacher_id = :teacher_id");
        //   $stmtT->bindParam(":teacher_id", $teacher_id);
        //   $stmtT->execute();
        //   $teachers=$stmtT->setFetchMode(PDO::FETCH_ASSOC);
        //   $teachers=$stmtT->fetchAll();
        //   if(count($teachers)>0){
        //     $teacher= $teachers[0];
        //     $_SESSION['teacher_course']=$teacher['course_name'];
        //   }
        // }

        $userrole= $user['role'];
        switch($userrole){
          case 'admin':
            header("Location: admin_dashboard.php");
            break;
            case 'teacher':
              header("Location: teacher_dashboard.php");
              break;
              case 'student':
                header("Location: students_dashboard.php");
                break;
              }
              
              // if ($_SESSION['role'] == "teacher"){
              //   header("location: teacher_dashboard.php");
              // }
    }else if ( count($usersS) > 0){
        $userS= $usersS[0];
        $_SESSION['is_logged_in']=true;
        $_SESSION['user_id']= $userS['user_id'];
        $_SESSION['username'] = $userS['name'];
        $_SESSION['role']= "student";
        header("location: students_dashboard.php");

    }
    else{
        echo "The user does not exists with this username.";
    }
  }
}




?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - School Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
      * {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
  font-family: 'Inter', sans-serif;
}


body {
  background: linear-gradient(135deg, #e0ebfa, #f1f8e9);
  font-family: 'Inter', sans-serif;
  margin-top: 1rem;
}

.main-container{
  margin-top: 2rem;
  display: flex;
  flex-direction: column;
}

.main-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  flex-direction: column;
}

.container {
  background: white;
  padding: 2.5rem;
  border-radius: 1rem;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 420px;
  text-align: center;
}

.alert {
  width: 100%;
  max-width: 420px;
  margin: 1rem auto;
}

h2 {
  margin-bottom: 1.5rem;
  font-weight: 600;
  color: #132043;
}

input,
select,
button {
  width: 100%;
  padding: 0.75rem 1rem;
  margin-bottom: 1rem;
  border-radius: 0.5rem;
  border: 1px solid #ccc;
  transition: all 0.3s ease;
  font-size: 1rem;
}

input:focus,
select:focus {
  outline: none;
  border-color: #132043;
  box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
}

button {
  background: #132043;
  color: white;
  font-weight: 600;
  border: none;
  cursor: pointer;
}

button:hover {
  background: #132043;
}

.link {
  font-size: 0.9rem;
  color: #333;
}

.link a {
  color: #132043;
  text-decoration: none;
  font-weight: 500;
}

.link a:hover {
  text-decoration: underline;
}
    </style>  
</head>
<body>
  <div class="main-wrapper">
    <div class="container">
      <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <h2>Login</h2>
        <input type="text" name="loginUsername" placeholder="Username" required>
        <input type="password" name="loginPassword" placeholder="Password" required>

        <button type="submit" name="loginSubmit">Login</button>
        <p>Don't have an account? <a href="signup.php">Register</a></p>
      </form>
    </div>
  </div>
</body>
</html>