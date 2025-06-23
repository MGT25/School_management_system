<?php
require("partials/contodb.php");
session_start();
if (!isset($_SESSION['role'])) {
    header("location: login.php ");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - School Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    
    <!-- Students Section -->
    <div class="container my-4">
        <div class="card mb-4 pb-2" id="students">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title"><i class="fas fa-users me-2"></i>My Courses</h5>

                <div class="buttons">
                    <a class="btn btn-sm" href="admin_dashboard.php" style="background-color: #1e3160; color: white;">
                        <i class="fas fa-home me-1"></i> Dashboard
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Course</th>
                                <th>Course Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            require("partials/contodb.php");

                            $student_id = $_SESSION['user_id'];
                            $student_name = $_SESSION['username'];
                            $stmt = $conn->prepare("SELECT enrollments.*, courses.* FROM `enrollments` INNER JOIN courses ON enrollments.course_id=courses.id WHERE student_name = :student_name");
                            $stmt->bindParam(':student_name', $student_name);
                            $stmt->execute();
                            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $count = count($courses);



                            if (count($courses) > 0) {
                                foreach ($courses as $course) {
                                    $userjson = json_encode($course);
                                    echo "
                                    <tr>
                                        <td>ST001</td>
                                        <td>
                                            {$course['name']}
                                        </td>
                                        <td style='max-width: 200px;'>{$course['description']} </td>";
                                        if(isset($_SESSION['role']) && $_SESSION['role']=== 'admin'){
                                            echo"
                                            <td>
                                                <button class='btn btn-sm btn-outline-primary'><i class='fas fa-eye'></i></button>
                                                <button class='btn btn-sm btn-outline-warning' data-bs-toggle='modal' data-bs-target='#updateModal' onclick='updatedata(`" . $userjson . "`)'><i class='fas fa-edit'></i></button>
                                                <button class='btn btn-sm btn-outline-danger' data-bs-toggle='modal' data-bs-target='#deleteModal' onclick='deletedata({$course['id']})'>
                                                    <i class='fas fa-trash'></i>
                                                </button>
                                            </td>";
                                        }else{
                                            echo"
                                            <td>
                                                <button class='btn btn-sm btn-success'>enrolled</button>
                                            </td.
                                            ";
                                        }

                                  echo" </tr>
                                
                                ";
                                }
                            }else{
                                echo'<tr>NO COURSES FOUND</tr>';
                            }


                            ?>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS (includes Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

<!-- <img src="https://via.placeholder.com/30" class="rounded-circle me-2" alt="Student"> -->