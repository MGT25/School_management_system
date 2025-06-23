<?php
require("partials/contodb.php");
session_start();
if(!isset($_SESSION['role'])){
    header("location: login.php ");
}elseif($_SESSION['role']=="student"){
    header("location: students_dashboard.php");
}elseif($_SESSION['role']=="teacher"){
    header("location: teacher_dashboard.php");
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    if (isset($_POST['updateSubmit'])) {
        $teacher_id = $_POST['update_teacher_select']; // not $student_id
        $teacherstmt = $conn->prepare("SELECT username FROM users WHERE user_id = :teacher_id");
        $teacherstmt->bindParam(':teacher_id', $teacher_id);
        $teacherstmt->execute();
        $teacher_name = $teacherstmt->fetchColumn();   

        $course_id = $_POST['update_course_select'];

        $coursestmt = $conn->prepare("SELECT name, description FROM courses WHERE id = :course_id");
        $coursestmt->bindParam(':course_id', $course_id);
        $coursestmt->execute();
        $courseData = $coursestmt->fetch(PDO::FETCH_ASSOC);
        $course_name = $courseData['name'];
        $course_description = $courseData['description']; // optional
        $stmt = $conn->prepare("UPDATE `enrolled_teachers` SET `teacher_name`=:teacher_name,`course_id`=:course_id,`course_name`=:course_name WHERE teacher_id =:teacher_id");
        $stmt->bindParam(':teacher_name', $teacher_name);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->bindParam(':course_name', $course_name);
        $stmt->bindParam(':teacher_id', $teacher_id);
        $stmt->execute();
        echo "updated successfully";
    }else if(isset($_POST['delete_id'])){
        $delete_id= $_POST['delete_id'];
        $stmt= $conn->prepare("DELETE FROM `enrolled_teachers` WHERE id=:delete_id");
        $stmt-> bindParam(":delete_id", $delete_id);
        $stmt->execute();
        echo "Deleted successfully";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Enrolled Teachers - School Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <!-- update Modal -->
    <!-- <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Update course record</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="edit_id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Teacher:</label>
                            <select name="update_teacher_select" class="form-select" id="update_teacher_select">
                                <?php
                                // Get students
                                $stmt = $conn->query("SELECT user_id, username FROM users WHERE role='teacher'");
                                $stmt->execute();
                                $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                $no = 0;
                                foreach ($teachers as $teacher) {
                                    $no += 1;
                                    echo "<option value='{$teacher['user_id']}' id='update_user_id-{$no}'>{$teacher['username']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Course:</label>
                            <select name="update_course_select" class="form-select" id="update_course_select">
                                <?php
                                // Get courses
                                $stmt = $conn->prepare("SELECT id, name FROM courses");
                                $stmt->execute();
                                $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($courses as $course) {
                                    echo "<option value='{$course['id']}'>{$course['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="updateSubmit" class="btn btn-primary" name="updateSubmit">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div> -->

    <!-- delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Sign In Here</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="delete_id" id="delete_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="signInSubmit" class="btn btn-primary" name="signInSubmit">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Teachers Section -->
    <div class="container my-4">
        <div class="card mb-4 pb-2" id="students">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title"><i class="fas fa-users me-2"></i> Enrolled Teachers</h5>

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
                                <th>Teacher Name</th>
                                <th>Courses</th>
                                <th>Course Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            require("partials/contodb.php");

                            $stmt = $conn->prepare("SELECT * FROM `enrolled_teachers`");
                            $stmt->execute();
                            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $count = count($courses);


                            if (count($courses) > 0) {
                                foreach ($courses as $course) {

                                    $course_id = $course['course_id'];
                                    $descstmt = $conn->prepare("SELECT description FROM `courses` WHERE id = :course_id");
                                    $descstmt->bindParam(':course_id', $course_id);
                                    $descstmt->execute();
                                    $description = $descstmt->fetchColumn();

                                    $userjson = json_encode($course);
                                    echo "
                                    <tr>
                                        <td>{$course['teacher_id']}</td>
                                        <td>
                                            {$course['teacher_name']}
                                        </td>
                                        <td>{$course['course_name']}</td>
                                        

                                    
                                        <td style='max-width: 200px;'>{$description} </td>";
                                    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                                        echo "
                                            <td>
                                                <button class='btn btn-sm btn-outline-primary'><i class='fas fa-eye'></i></button>
                                                <button class='btn btn-sm btn-outline-warning' data-bs-toggle='modal' data-bs-target='#updateModal' onclick='updatedata(`" . $userjson . "`)'><i class='fas fa-edit'></i></button>
                                                <button class='btn btn-sm btn-outline-danger' data-bs-toggle='modal' data-bs-target='#deleteModal' onclick='deletedata({$course['id']})'>
                                                    <i class='fas fa-trash'></i>
                                                </button>
                                            </td>";
                                    } else {
                                        echo "
                                            <td>
                                                <button class='btn btn-sm btn-success'>enrolled</button>
                                            </td.
                                            ";
                                    }

                                    echo " </tr>
                                
                                ";
                                }
                            } else {
                                echo '<tr>NO COURSES FOUND</tr>';
                            }


                            ?>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deletedata(userid) {
            let deleteinput = document.getElementById("delete_id");
            deleteinput.value = userid;
        }

        function updatedata(coursejson) {
            let user = JSON.parse(coursejson);
            console.log(user);
            document.getElementById("edit_id").value = user.id;
            document.getElementById("update_teacher_select").value = user.teacher_id;
            document.getElementById("update_course_select").value = user.course_id;
        }
    </script>
    <!-- Bootstrap JS (includes Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

<!-- <img src="https://via.placeholder.com/30" class="rounded-circle me-2" alt="Student"> -->