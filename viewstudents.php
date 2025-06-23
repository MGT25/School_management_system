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


$showAlert = false;
$successMsg = "";

if (isset($_POST['edit_id'])) {
    $user_id = 0;
    $edit_id = $_POST['edit_id'];
    $name = $_POST['studentName'];
    $email = $_POST['studentEmail'];
    $phone = $_POST['studentContact'];
    $class = $_POST['studentClass'];

    $stmt = $conn->prepare("UPDATE `students` SET `user_id`=:user_id, `name`=:name,`email`=:email,`phone`=:phone,`class`=:class WHERE id = :edit_id");
    $stmt->bindParam(":user_id", $user_id);
    $stmt->bindParam(":edit_id", $edit_id);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":phone", $phone);
    $stmt->bindParam(":class", $class);
    $result = $stmt->execute();
    if ($result) {
        $showAlert = true;
        $successMsg = "Record updated successfully";
    }
}
if (isset($_POST['delete_id'])) {

    $delete_id = $_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM `students` WHERE id=:delete_id");
    $stmt->bindParam(":delete_id", $delete_id);
    $result = $stmt->execute();
    if ($result) {
        $showAlert = true;
        $successMsg = "Record deleted successfully";
    }
} elseif (isset($_POST['addStudentSubmit'])) {
    // if (!empty($addStudentName) && !empty($addStudentEmail) && !empty($addStudentContact) && !empty($addStudentClass)) {
    $user_id = 0;
    $addStudentName = $_POST['addStudentName'];
    $addStudentEmail = $_POST['addStudentEmail'];
    $addStudentContact = $_POST['addStudentContact'];
    $addStudentClass = $_POST['addStudentClass'];

    $stmt = $conn->prepare("INSERT INTO `students`(`user_id`, `name`, `email`, `phone`, `class`) VALUES (:user_id, :name, :email, :phone, :class)");

    $stmt->bindParam(":user_id", $user_id);
    $stmt->bindParam(":name", $addStudentName);
    $stmt->bindParam(":email", $addStudentEmail);
    $stmt->bindParam(":phone", $addStudentContact);
    $stmt->bindParam(":class", $addStudentClass);
    $stmt->execute();
    $showAlert = true;
    $successMsg = "Record inserted successfully";
    // } 
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students - School Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <!-- Add Teacher Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Student Here</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="addStudentName" class="form-label">Student Name</label>
                            <input type="text" class="form-control form-control-sm" id="addStudentName" name="addStudentName">
                        </div>
                        <div class="mb-3">
                            <label for="addStudentEmail" class="form-label">Student Email </label>
                            <input type="email" class="form-control form-control-sm" id="addStudentEmail" name="addStudentEmail">
                        </div>
                        <div class="mb-3">
                            <label for="addStudentContact" class="form-label">Student Contact </label>
                            <input type="tel" class="form-control form-control-sm" id="addStudentContact" name="addStudentContact">
                        </div>
                        <div class="mb-3">
                            <label for="addStudentClass" class="form-label">Class</label>
                            <select name="addStudentClass" id="addStudentClass">
                                <option value="7th">7th</option>
                                <option value="8th">8th</option>
                                <option value="9th">9th</option>
                                <option value="10th">10th</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="addStudentSubmit" class="btn btn-primary" name="addStudentSubmit" style="background-color: #1e3160; color:white;">Add Student</button>
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
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Update student record</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="edit_id" id="edit_id">
                        <div class="mb-3">
                            <label for="studentName" class="form-label">Student Name</label>
                            <input type="text" class="form-control form-control-sm" id="studentName" name="studentName">
                        </div>
                        <div class="mb-3">
                            <label for="studentEmail" class="form-label">Student Email </label>
                            <input type="email" class="form-control form-control-sm" id="studentEmail" name="studentEmail">
                        </div>
                        <div class="mb-3">
                            <label for="studentContact" class="form-label">Student Contact </label>
                            <input type="tel" class="form-control form-control-sm" id="studentContact" name="studentContact">
                        </div>
                        <div class="mb-3">
                            <label for="studentClass" class="form-label">Class</label>
                            <select name="studentClass" id="studentClass">
                                <option value="7th">7th</option>
                                <option value="8th">8th</option>
                                <option value="9th">9th</option>
                                <option value="10th">10th</option>
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
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Delete Student record</h1>
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

    <!-- Students Section -->
    <div class="container my-4">
        <div class="card mb-4 pb-2" id="students">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title"><i class="fas fa-users me-2"></i> Students</h5>
                
                <div class="buttons">
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal" style="background-color: #1e3160; border:none;">
                        <i class="fas fa-plus me-1"></i> Add Student
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
                                <th>Email</th>
                                <th>Phone No</th>
                                <th>Class</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            require("partials/contodb.php");

                            $stmt = $conn->prepare("SELECT * FROM `students`");
                            $stmt->execute();
                            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                            $users = $stmt->fetchAll();
                            $count = count($users);

                            if (count($users) > 0) {
                                foreach ($users as $user) {
                                    $userjson = json_encode($user);
                                    echo "
                                    <tr>
                                        <td>ST001</td>
                                        <td>
                                            {$user['name']}
                                        </td>
                                        <td>{$user['email']} </td>
                                        <td> {$user['phone']}</td>
                                        <td> {$user['class']}</td>
                                        <td>
                                            <button class='btn btn-sm btn-outline-primary'><i class='fas fa-eye'></i></button>
                                            <button class='btn btn-sm btn-outline-warning' data-bs-toggle='modal' data-bs-target='#updateModal' onclick='updatedata(`" . $userjson . "`)'><i class='fas fa-edit'></i></button>
                                            <button class='btn btn-sm btn-outline-danger' data-bs-toggle='modal' data-bs-target='#deleteModal' onclick='deletedata({$user['id']})'>
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

        function updatedata(userjson) {
            let user = JSON.parse(userjson);
            document.getElementById("edit_id").value = user.id;
            document.getElementById("studentName").value = user.name;
            document.getElementById("studentEmail").value = user.email;
            document.getElementById("studentContact").value = user.phone;
            document.getElementById("studentClass").value = user.class;
        }
    </script>

</body>

</html>

<!-- <img src="https://via.placeholder.com/30" class="rounded-circle me-2" alt="Student"> -->