<?php
require("partials/contodb.php");
session_start();
if (!isset($_SESSION['role'])) {
    header("location: login.php ");
} elseif ($_SESSION['role'] == "student") {
    header("location: students_dashboard.php");
} elseif ($_SESSION['role'] == "teacher") {
    header("location: teacher_dashboard.php");
}

$showAlert=false;
$successMsg= "";

if (isset($_POST['edit_id'])) {
    $user_id = 0;
    $edit_id = $_POST['edit_id'];
    $name = $_POST['teacherName'];
    $email = $_POST['teacherEmail'];
    $phone = $_POST['teacherContact'];
    $subject = $_POST['teacherSubject'];
    $status= $_POST['teacherStatus'];

    $stmt = $conn->prepare("UPDATE `teachers` SET `user_id`=:user_id,`name`=:name,`email`=:email,`phone`=:phone,`subject`=:subject, `status`=:status WHERE id = :edit_id");
    $stmt->bindParam(":edit_id", $edit_id);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":phone", $phone);
    $stmt->bindParam(":subject", $subject);
    $stmt->bindParam(":status", $status);
    $result = $stmt->execute();
    if($result){
        $showAlert= true;
        $successMsg="Record updated successfully";
    }
} elseif (isset($_POST['delete_id'])) {

    $delete_id= $_POST['delete_id'];
    $stmt= $conn->prepare("DELETE FROM `teachers` WHERE id=:delete_id");
    $stmt->bindParam(":delete_id", $delete_id);
    $result = $stmt->execute();
    if ($result){
        $showAlert=true;
        $successMsg= "Record deleted successfully";
    }

} elseif (isset($_POST['addTeacherSubmit'])) {
    $addTeacherName = $_POST['addTeacherName'];
    $addTeacherEmail = $_POST['addTeacherEmail'];
    $addTeacherContact = $_POST['addTeacherContact'];
    $addTeacherSubject = $_POST['addTeacherSubject'];
    $status = $_POST['addTeacherStatus'];

    $stmt= $conn->prepare("INSERT INTO `teachers`(`name`, `email`, `phone`, `subject`, `status`) VALUES (:name,:email,:phone,:subject,:status)");
    $stmt->bindParam(":name", $addTeacherName);
    $stmt->bindParam(":email", $addTeacherEmail);
    $stmt->bindParam(":phone", $addTeacherContact);
    $stmt->bindParam(":subject", $addTeacherSubject);
    $stmt->bindParam(":status", $status);
    $stmt->execute();
    $showAlert=true;
    $successMsg="Record inserted successfully";
}





?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Teachers - School Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <!-- Add Teacher Modal -->
    <div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Teacher Here</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="addTeacherName" class="form-label">Teacher Name</label>
                            <input type="text" class="form-control form-control-sm" id="addTeacherName" name="addTeacherName">
                        </div>
                        <div class="mb-3">
                            <label for="addTeacherEmail" class="form-label">Teacher Email </label>
                            <input type="email" class="form-control form-control-sm" id="addTeacherEmail" name="addTeacherEmail">
                        </div>
                        <div class="mb-3">
                            <label for="addTeacherContact" class="form-label">Teacher Contact </label>
                            <input type="tel" class="form-control form-control-sm" id="addTeacherContact" name="addTeacherContact">
                        </div>
                        <div class="mb-3">
                            <label for="addTeacherSubject" class="form-label">Subject</label>
                            <input type="text" class="form-control form-control-sm" id="addTeacherSubject" name="addTeacherSubject">
                        </div>
                        <div class="mb-3">
                            <label for="addTeacherStatus" class="form-label">Status</label>
                            <select name="addTeacherStatus" id="addTeacherStatus">
                                <option value="active">Active</option>
                                <option value="onleave">onleave</option>
                                </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="addTeacherSubmit" class="btn btn-primary" name="addTeacherSubmit" style="background-color: #1e3160; color:white;">Add Teacher</button>
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
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Sign In Here</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="edit_id" id="edit_id">
                        <div class="mb-3">
                            <label for="teacherName" class="form-label">Teacher Name</label>
                            <input type="text" class="form-control form-control-sm" id="teacherName" name="teacherName">
                        </div>
                        <div class="mb-3">
                            <label for="teacherEmail" class="form-label">Teacher Email </label>
                            <input type="email" class="form-control form-control-sm" id="teacherEmail" name="teacherEmail">
                        </div>
                        <div class="mb-3">
                            <label for="teacherContact" class="form-label">Teacher Contact </label>
                            <input type="tel" class="form-control form-control-sm" id="teacherContact" name="teacherContact">
                        </div>
                        <div class="mb-3">
                            <label for="teacherSubject" class="form-label">Subject</label>
                            <input type="text" class="form-control form-control-sm" id="teacherSubject" name="teacherSubject">
                        </div>

                        <div class="mb-3">
                            <label for="teacherStatus" class="form-label">Status</label>
                            <select name="teacherStatus" id="teacherStatus">
                                <option value="active">Active</option>
                                <option value="onleave">onleave</option>
                            </select>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="signInSubmit" class="btn btn-primary" name="signInSubmit">Update</button>
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


    <div class="container my-4">
        <?php
            if($showAlert){
                echo"
                    <div class='alert alert-success alert-dismissible fade show' role='alert'>
                        <strong>Success</strong> $successMsg.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                ";
            }
        
        ?>
        <div class="card mb-4" id="teachers">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title"><i class="fas fa-chalkboard-teacher me-2"></i> Teachers</h5>
                <div class="buttons">
                    <button class="btn btn-sm" data-bs-toggle='modal' data-bs-target='#addTeacherModal' style="background-color: #1e3160; color: white;">
                        <i class="fas fa-plus me-1"></i> Add Teacher
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
                                <th>Subject</th>
                                <!-- <th>Classes</th> -->
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            require("partials/contodb.php");

                            $stmt = $conn->prepare("SELECT * FROM `teachers`");
                            $stmt->execute();
                            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                            $teachers = $stmt->fetchAll();
                            $count = count($teachers);

                            if (count($teachers) > 0) {
                                foreach ($teachers as $teacher) {
                                    $userjson = json_encode($teacher);
                                    echo "
                                    <tr>
                                                    <td>TC001</td>
                                                    <td>
                                                        {$teacher['name']}
                                                    </td>
                                                    <td>{$teacher['subject']}</td>
                                                    <td>{$teacher['email']}</td>
                                                    <td>{$teacher['phone']}</td>
                                                    ";
                                                    if($teacher['status'] == "active"){
                                                        echo "<td><span class='badge' style='background-color: #2d7930;'>{$teacher['status']}</span></td>";
                                                    }elseif($teacher['status'] == "onleave"){
                                                        echo "<td><span class='badge' style='background-color:rgb(180, 182, 54);'>{$teacher['status']}</span></td>";
                                                    }
                                                  echo  "<td>
                                                        <button class='btn btn-sm btn-outline-primary' onclick='hide()'><i class='fas fa-eye'></i></button>
                                                        <button class='btn btn-sm btn-outline-warning' data-bs-toggle='modal' data-bs-target='#updateModal' onclick='updaterecord(`" . $userjson . "`)'><i class='fas fa-edit'></i></button>
                                                        <button class='btn btn-sm btn-outline-danger' data-bs-toggle='modal' data-bs-target='#deleteModal' onclick='deletedata({$teacher['id']})'><i class='fas fa-trash'></i></button>
                                                        </td>
                                                </tr>
                                
                                ";
                                }
                            }


                            ?>
                            <!-- <tr>
                                <td>ST001</td>
                                <td>
                                    <img src="https://via.placeholder.com/30" class="rounded-circle me-2" alt="Student">
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
                <nav aria-label="Page navigation">
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
                </nav>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>

        function hide(){
            document.getElementById
        }

        function deletedata(userid) {
            let deleteinput = document.getElementById("delete_id");
            deleteinput.value = userid;
        }

        function updaterecord(userjson) {
            let user = JSON.parse(userjson);
            document.getElementById("edit_id").value = user.id;
            document.getElementById("teacherName").value = user.name;
            document.getElementById("teacherEmail").value = user.email;
            document.getElementById("teacherContact").value = user.phone;
            document.getElementById("teacherSubject").value = user.subject;
        }
    </script>
</body>

</html>

<!-- <img src="https://via.placeholder.com/30" class="rounded-circle me-2" alt="Teacher"> -->
<!-- <td>10A, 10B, 11A</td> -->