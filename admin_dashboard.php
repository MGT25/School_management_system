<?php

session_start();
if(!isset($_SESSION['role'])){
    header("location: login.php ");
}elseif($_SESSION['role']=="student"){
    header("location: students_dashboard.php");
}elseif($_SESSION['role']=="teacher"){
    header("location: teacher_dashboard.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Fauget School Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/styles.css">
</head>


<?php
require("partials/contodb.php");

$stmt = $conn->prepare("SELECT * FROM `students`");
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$users = $stmt->fetchAll();
$count_students = count($users);


$stmt = $conn->prepare("SELECT * FROM `teachers`");
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$users = $stmt->fetchAll();
$count_teachers  = count($users);



?>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-graduation-cap me-2"></i>Fauget School
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="admin_dashboard.php"><i class="fas fa-home me-1"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="students_dashboard.php"><i class="fas fa-users me-1"></i> Students</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="teacher_dashboard.php"><i class="fas fa-chalkboard-teacher me-1"></i> Teachers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#classes"><i class="fas fa-door-open me-1"></i> Classes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#attendance"><i class="fas fa-clipboard-check me-1"></i> Attendance</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center">
                    <?php 
                        $student_name= $_SESSION['username'];
                        $sql= $conn->prepare("SELECT role, username FROM `users` WHERE username=:student_name");
                        $sql->bindParam(":student_name", $student_name);
                        $sql->execute();
                        $result = $sql->fetch(PDO::FETCH_ASSOC);
                        if($result){

                            $user_name= ucfirst($result['username']);
                            $user_role= $result['role'];
                        };
                        if (isset($_SESSION['role'])){
                            echo'<a class="dropdown-item text-light mx-2" href="logout.php"><i class="fas fa-sign-out-alt me-2 text-light"></i> Sign out</a>';
                            echo '<p class="text-light mx-4 my-2">'.$user_name. '('.$user_role.')</p>' ;
                        }else{

                           echo' <a class="dropdown-item text-light mx-2" href="#"><i class="fas fa-sign-out-alt me-2 text-light"></i> Sign in</a>';

                        }
                    ?>
                </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 d-none d-lg-block ">
                <div class="card sidebar mb-4 ">
                    <div class="card-body" style="width: 230px;">
                        <h5 class="card-title">Quick Links</h5>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="addStudent.php" style="color: #1e3160;"><i class="fas fa-user-plus me-2" class="quick-links"></i> Add Student</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="addTeacher.php" style="color: #1e3160;"><i class="fas fa-user-tie me-2" class="quick-links"></i> Add Teacher</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="viewstudents.php" style="color: #1e3160;"><i class="fas fa-users me-1" class="quick-links"></i> View Students</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="viewteachers.php" style="color: #1e3160;"><i class="fas fa-chalkboard-teacher me-1" class="quick-links"></i> View Teachers</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="addCourse.php" style="color: #1e3160;"><i class="fas fa-plus-circle me-2" class="quick-links"></i> Add Course</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="viewcourses.php" style="color: #1e3160;"><i class="fa-solid fa-eye"></i> View Courses</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="enrollment.php" style="color: #1e3160;"><i class="fa-solid fa-plus"></i> Enrollment</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="viewEnrollments.php" style="color: #1e3160;"><i class="fa-solid fa-users-viewfinder"></i> View Enrollments</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="enrollteacher.php" style="color: #1e3160;"><i class="fa-solid fa-plus"></i> Enroll Teacher</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="viewEnrolledteachers.php" style="color: #1e3160;"><i class="fa-solid fa-users-viewfinder mx-1"></i>Enrolled Teachers</a>
                            </li>
                        </ul>
                        <hr style="width: 170px;">
                        <h5 class="card-title">Statistics</h5>
                        <div class="stat-item">
                            <span class="stat-label">Total Students</span>
                            <span class="stat-value"><?php echo $count_students; ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Total Teachers</span>
                            <span class="stat-value"><?php echo $count_teachers; ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Classes</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-10">
                <!-- Dashboard Overview -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-white" style="background-color: #1e3160;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Students</h6>
                                        <h2 class="mb-0"><?php echo $count_students ?></h2>
                                    </div>
                                    <i class="fas fa-users fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white" style="background-color: #2d7930;  border:none;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Teachers</h6>
                                        <h2 class="mb-0"><?php echo $count_teachers ?></h2>
                                    </div>
                                    <i class="fas fa-chalkboard-teacher fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white" style="background-color: #067268; border:none;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Classes</h6>
                                        <h2 class="mb-0">32</h2>
                                    </div>
                                    <i class="fas fa-door-open fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white" style="background-color: #e7cc05; border:none;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Attendance Today</h6>
                                        <h2 class="mb-0">92%</h2>
                                    </div>
                                    <i class="fas fa-clipboard-check fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Teachers Section -->
                
            </div>
        </div>
    </div>


    <!-- Add Teacher Modal -->
    <!-- footer -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="script.js"></script>
</body>

</html>