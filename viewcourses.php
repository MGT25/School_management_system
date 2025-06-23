<?php
require("partials/contodb.php");
session_start();
if (!isset($_SESSION['role'])) {
    header("location: login.php ");
} elseif ($_SESSION['role'] == "course") {
    header("location: courses_dashboard.php");
} elseif ($_SESSION['role'] == "teacher") {
    header("location: teacher_dashboard.php");
}


$showAlert = false;
$successMsg = "";

if (isset($_POST['edit_id'])) {
    // $user_id = $_SESSION['user_id'];
    $edit_id = $_POST['edit_id'];
    $courseName = $_POST['courseName'];
    $courseDescription = $_POST['courseDescription'];
    $teacherName = $_POST['teacherName'];
    $teacherPhone = $_POST['teacherPhone'];

    $stmt = $conn->prepare("UPDATE `courses` SET `name`=:name,`description`=:description,`teacher_name`=:teacher_name,`teacher_phone`=:teacher_phone WHERE id = :edit_id");
    $stmt->bindParam(":edit_id", $edit_id);
    $stmt->bindParam(":name", $courseName);
    $stmt->bindParam(":description", $courseDescription);
    $stmt->bindParam(":teacher_name", $teacherName);
    $stmt->bindParam(":teacher_phone", $teacherPhone);
    $result = $stmt->execute();
    if ($result) {
        $showAlert = true;
        $successMsg = "Record updated successfully";
    }
}
if (isset($_POST['delete_id'])) {

    $delete_id = $_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM `courses` WHERE id=:delete_id");
    $stmt->bindParam(":delete_id", $delete_id);
    $result = $stmt->execute();
    if ($result) {
        $showAlert = true;
        $successMsg = "Record deleted successfully";
    }
}
if (isset($_POST['addcourseSubmit'])) {
    // echo "fine";
    // exit();
    // if (!empty($addcourseName) && !empty($addcourseDescription) && !empty($addteacherName) && !empty($addteacherPhone)) {
        $name = $_POST['addcourseName'];
        $description = $_POST['addcourseDescription'];
        $teacher_name = $_POST['addteacherName'];
        $teacher_phone = $_POST['addteacherPhone'];
        $stmt = $conn->prepare("INSERT INTO `courses`(`name`, `description`, `teacher_name`, `teacher_phone`) VALUES (:name,:description,:teacher_name, :teacher_phone)");
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":teacher_name", $teacher_name);
        $stmt->bindParam(":teacher_phone", $teacher_phone);
        $stmt->execute();
        echo "data inserted successfully";
    // }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View courses - School Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <!-- Add Teacher Modal -->
    <div class="modal fade" id="addcourseModal" tabindex="-1" aria-labelledby="addcourseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add course Here</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>




                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="addcourseName" class="form-label">C ourse Name</label>
                            <input type="text" class="form-control form-control-sm" name="addcourseName" placeholder="Course Name" required />
                        </div>
                        <div class="mb-3">
                            <label for="addcourseDescription" class="form-label">Course Email </label>
                            <input type="text" class="form-control form-control-sm" name="addcourseDescription" placeholder="Course Description" required />
                        </div>
                        <div class="mb-3">
                            <label for="addteacherName" class="form-label">Course Contact </label>
                            <input type="text" class="form-control form-control-sm" name="addteacherName" id="teacherSubject" placeholder="Teacher Name" required />
                        </div>
                        <div class="mb-3">
                            <label for="addteacherPhone" class="form-label">Class</label>
                            <input type="tel" class="form-control form-control-sm" name="addteacherPhone" placeholder="Teacher Phone No" required />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="addcourseSubmit" class="btn btn-primary" name="addcourseSubmit" style="background-color: #1e3160; color:white;">Add course</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- update Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
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
                            <label for="courseName" class="form-label">Course Name</label>
                            <input type="text" class="form-control form-control-sm" id="courseName" name="courseName">
                        </div>
                        <div class="mb-3">
                            <label for="courseDescription" class="form-label">Course Description </label>
                            <input type="text" class="form-control form-control-sm" id="courseDescription" name="courseDescription">
                        </div>
                        <div class="mb-3">
                            <label for="teacherName" class="form-label">Teacher Name </label>
                            <input type="text" class="form-control form-control-sm" id="teacherName" name="teacherName">
                        </div>
                        <div class="mb-3">
                            <label for="teacherPhone" class="form-label">Teacher Phone</label>
                            <input type="tel" class="form-control form-control-sm" id="teacherPhone" name="teacherPhone">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="signInSubmit" class="btn btn-primary" name="updateSubmit">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Delete course record</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="delete_id" id="delete_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="signInSubmit" class="btn btn-primary" name="deleteSubmit">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- courses Section -->
    <div class="container my-4">
        <div class="card mb-4 pb-2" id="courses">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title"><i class="fas fa-users me-2"></i> Courses</h5>

                <div class="buttons">
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addcourseModal" style="background-color: #1e3160; border:none;">
                        <i class="fas fa-plus me-1"></i> Add course
                    </button>
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
                                <th>Name</th>
                                <th>Description</th>
                                <th>Teacher Name</th>
                                <th>Teacher Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            require("partials/contodb.php");

                            $stmt = $conn->prepare("SELECT * FROM `courses`");
                            $stmt->execute();
                            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                            $courses = $stmt->fetchAll();
                            $count = count($courses);

                            if (count($courses) > 0) {
                                foreach ($courses as $course) {
                                    $coursejson = json_encode($course);
                                    echo "
                                    <tr>
                                        <td>ST001</td>
                                        <td>
                                            {$course['name']}
                                        </td>
                                        <td style='max-width: 200px;'>{$course['description']} </td>
                                        <td> {$course['teacher_name']}</td>
                                        <td> {$course['teacher_phone']}</td>
                                        <td>
                                            <button class='btn btn-sm btn-outline-primary'><i class='fas fa-eye'></i></button>
                                            <button class='btn btn-sm btn-outline-warning' data-bs-toggle='modal' data-bs-target='#updateModal' onclick='updatedata(`" . $coursejson . "`)'><i class='fas fa-edit'></i></button>
                                            <button class='btn btn-sm btn-outline-danger' data-bs-toggle='modal' data-bs-target='#deleteModal' onclick='deletedata({$course['id']})'>
                                                <i class='fas fa-trash'></i>
                                            </button>
                                        </td>
                                   </tr>
                                
                                ";
                                }
                            }


                            ?>


                            <!-- <tr>
                                <td>ST001</td>
                                <td>
                                    <img src="https://via.placeholder.com/30" class="rounded-circle me-2" alt="course">
                                    John Doe
                                </td>
                                <td>10</td>
                                <td>Michael Doe</td>
                                <td>john.doe@example.com</td>
                                <td><span class="badge bg-success">Active</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr> -->

                        </tbody>
                    </table>
                </div>
                <!-- <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav> -->
            </div>
        </div>
    </div>



    <!-- Bootstrap JS (includes Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deletedata(userid) {
            let deleteinput = document.getElementById("delete_id");
            deleteinput.value = userid;
        }

        function updatedata(coursejson) {
            let user = JSON.parse(coursejson);
            document.getElementById("edit_id").value = user.id;
            document.getElementById("courseName").value = user.name;
            document.getElementById("courseDescription").value = user.description;
            document.getElementById("teacherName").value = user.teacher_name;
            document.getElementById("teacherPhone").value = user.teacher_phone;
        }
    </script>

</body>

</html>

<!-- <img src="https://via.placeholder.com/30" class="rounded-circle me-2" alt="course"> -->