<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <title>Home - School Management</title>
    <style>

    .custom {
        color: #fff !important; /* Default color */
    }

    .custom:hover,
    .custom:focus {
        color: #fff !important; /* Prevents it from turning blue */
    }
    </style>

</head>

<body style="background-color: #f5f7f9;">

    <div class="sidebar" style="z-index: 1;">
        <h2>HOME</h2>
        <ul class="nav">
            <li><a href="signup.php">Signup</a></li>
            <li><a href="login.php">Login</a></li>

            <?php
            try {
                session_start();

                if (isset($_SESSION['role'])) {
                    if ($_SESSION['role'] === "teacher") {
                        echo '<li><a href="teacher_dashboard.php"> Dashboard</a></li>';
                        echo '<li><a href="logout.php"> Logout</a></li>';
                    } elseif ($_SESSION['role'] === "student") {
                        echo '<li><a href="students_dashboard.php"> Dashboard</a></li>';
                        echo '<li><a href="logout.php"> Logout</a></li>';
                    } elseif ($_SESSION['role'] === "admin") {
                        echo '                    
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle custom" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Dashboards
                                </a>
                                <ul class="dropdown-menu bg-dark">
                                    <li><a class="dropdown-item custom" href="admin_dashboard.php">Admin Dashboard</a></li>
                                    <li><a class="dropdown-item custom" href="teacher_dashboard.php">Teacher Dashboard</a></li>
                                    <li><a class="dropdown-item custom" href="students_dashboard.php">Student Dashboard</a></li>
                                </ul>
                            </li>
                        ';
                        echo '<li><a href="logout.php"> Logout</a></li>';
                    }
                }
            } catch (Exception $e) {
                // Ideally, log the error or handle it
                error_log("Exception caught: " . $e->getMessage());
            }

            ?>
        </ul>
    </div>
    <div class="main_content">
        <img src="img/logo.png" alt="" class="logo-img" style="width: 300px; height:300px">
        <h3> WELCOME TO FAUGET SCHOOL MANAGEMENT SYSTEM</h3>
        <p>Empowering education through seamless organization and smart technology.</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>