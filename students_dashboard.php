<?php
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: index.php");
    exit();
}

require("partials/contodb.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students - School Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="csstyles.css">
</head>


<?php
require("partials/contodb.php");

$stmt = $conn->prepare("SELECT * FROM `students`");
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$users = $stmt->fetchAll();
$count_students = count($users);


// $stmt = $conn->prepare("SELECT * FROM `teachers`");
// $stmt->execute();
// $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
// $users = $stmt->fetchAll();
// $count_teachers  = count($users);



?>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap me-2"></i>EduManage
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fa-solid fa-table-columns"></i></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fas fa-home me-1"></i> Home</a>
                    </li>
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

                        if($_SESSION["role"]=== "admin"){
                            echo'
                            <li class="nav-item">
                            <a class="nav-link" href="admin_dashboard.php"><i class="fas fa-home me-1"></i>Admin home</a>
                            </li>';
                        }
                    ?>
                </ul>
                <div class="d-flex align-items-center">
                    <?php 
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
                                <a class="nav-link" href="addStudent.php" style="color: #1e3160;"><i class="fas fa-user-plus me-2" class="quick-links"></i> My Timetable</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="addStudent.php" style="color: #1e3160;"><i class="fas fa-user-plus me-2" class="quick-links"></i>My Classes</a>
                            </li> -->
                            <li class="nav-item">
                             <a class="nav-link" href="myCourses.php" style="color: #1e3160;"><i class="fas fa-book me-2" class="quick-links"></i>My Courses</a>
                            </li>
                            <li class="nav-item">
                             <a class="nav-link" href="addStudent.php" style="color: #1e3160;"><i class="fas fa-bullhorn me-2" class="quick-links"></i>Announcements</a>                                                   
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#take-attendance" style="color: #1e3160;"><i class="fas fa-check-circle me-2" class="quick-links"></i>  Timetable</a>
                            </li>
                            </li>
                        </ul>

                        <hr style="width: 170px;">

                        <h5 class="card-title">Statistics</h5>
                        <div class="stat-item">
                            <span class="stat-label"></span>
                            <span class="stat-value"></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label"></span>
                            <span class="stat-value"></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label"></span> <!-- important announcements from school  -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-10">
                <!-- Dashboard Overview -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-white" style="background-color: #1e3160; border:none;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">My Attendance Summary</h6>
                                        <h2 class="mb-0"></h2>
                                    </div>
                                    <i class="fas fa-users fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white" style="background-color: #2d7930; border:none;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">My Courses</h6>
                                        <h2 class="mb-0"></h2>
                                        <?php  
                                            $student_id = $_SESSION['user_id'];
                                            $student_name = $_SESSION['username'];
                                            // Count the number of courses the student is enrolled in
                                            $stmt = $conn->prepare("SELECT COUNT(*) FROM enrollments WHERE student_name = :student_name");
                                            $stmt->bindParam(":student_name", $student_name);
                                            $stmt->execute();
                                            $course_count = $stmt->fetchColumn();
                                            echo '<h2 class="mb-0">' . $course_count . '</h2>';
                                        ?> 
                                        <h2 class="mb-0"></h2>
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
                                        <h6 class="card-title">My Classes Schedule</h6>
                                        <!-- <h2 class="mb-0">32</h2> -->
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
                                        <h6 class="card-title">Recent Grades</h6>
                                        <!-- <h2 class="mb-0">92%</h2> -->
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


    

    <!-- Footer -->
     <!-- Footer -->
    <!-- <footer class="bg-light text-center text-lg-start mt-5">
        <div class="text-center p-3 bg-dark text-white">
            © 2023 EduManage - All Rights Reserved
        </div>
    </footer> -->
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="script.js"></script>
</body>

</html>