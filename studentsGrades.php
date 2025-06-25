    <?php
    session_start();
    if(!isset($_SESSION['role'])){
        header("location: login.php ");
    }

    require("partials/contodb.php");

    $teacher_id= $_SESSION['user_id'];
    $teacher_name= $_SESSION['username'];

    $stmt = $conn->prepare("SELECT course_name, teacher_name FROM `enrolled_teachers` WHERE teacher_id=:teacher_id");
    $stmt-> bindParam(":teacher_id", $teacher_id);
    $stmt->execute();
    $result= $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $coursee = $stmt->fetchAll();
    if (!empty($coursee)) {
        $teacher_course = $coursee[0]['course_name'];   
    } else {
        echo " No course found for teacher.";
        $teacher_course = ''; 
    }
    if ($_SERVER['REQUEST_METHOD'] === "POST") {





        if (isset($_POST['delete_id'])) {

            $delete_id= $_POST['delete_id'];
            $stmt= $conn->prepare("DELETE FROM `students_grades` WHERE id=:delete_id");
            $stmt->bindParam(":delete_id", $delete_id);
            $result = $stmt->execute();
            if ($result){
                $showAlert=true;
                $successMsg= "Record deleted successfully";
            }

        }else if (isset($_POST['edit_id'])) {
                $student_name = $_POST['student_name'];
                $course_name = $_POST['course_name'];


                $idstmt= $conn->prepare("SELECT e.id, e.student_id, e.student_name, e.course_name, e.course_id FROM enrollments e LEFT JOIN students_grades g ON e.student_id = g.student_id AND e.course_name = g.course_name WHERE e.student_name= :student_name");
                $idstmt->bindParam(":student_name", $student_name);
                $idstmt->execute();
                $result= $idstmt->setFetchMode(PDO::FETCH_ASSOC);
                $ids= $idstmt->fetchAll();
                $id = $ids[0];
                $student_id= $id['student_id'];
                $student_name= $id['student_name'];
                $course_id= $id['course_id'];
                $course_name= $id['course_name'];

                $edit_id = $_POST['edit_id'];
                $grade = $_POST['grade'];

                $selectstmt= $conn->prepare("SELECT * FROM `students_grades` WHERE student_id = :student_id AND course_name = :course_name");
                $selectstmt->bindParam(":student_id", $student_id);
                $selectstmt->bindParam(":course_name", $course_name);
                $selectstmt->execute();
                $result= $selectstmt->setFetchMode(PDO::FETCH_ASSOC);
                $exists= $selectstmt->fetchAll();

                if (count($exists)){
                    if ($exists[0]['grade'] !== $grade) {
                        $updatestmt = $conn->prepare("UPDATE `students_grades` SET `grade` = :grade WHERE student_id = :student_id AND course_name = :course_name");
                            $updatestmt->bindParam(":student_id", $student_id);
                            $updatestmt->bindParam(":course_name", $course_name);
                            $updatestmt->bindParam(":grade", $grade);
                        $result = $updatestmt->execute();
                        if ($result) {
                            $showAlert = true;
                            $successMsg = "Record updated successfully";
                        } 
                    }
                    

                }else{
                    if (isset($_POST['student_name'], $_POST['course_name'], $_POST['grade'])) {
                        $insertstmt = $conn->prepare("INSERT INTO `students_grades`(`student_id`, `student_name`, `course_id`, `course_name`, `grade`) VALUES ( :student_id, :student_name, :course_id, :course_name, :grade)");
                        $insertstmt->bindParam(":student_id", $student_id);
                        $insertstmt->bindParam(":student_name", $student_name);
                        $insertstmt->bindParam(":course_id", $course_id);
                        $insertstmt->bindParam(":course_name", $course_name);
                        $insertstmt->bindParam(":grade", $grade);
                        $insertstmt->execute();
                    }
                }


    }
}
    


    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Assign grades - School Management System</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <!-- Custom CSS -->
        <link rel="stylesheet" href="css/styles.css">
    </head>

    <body>

    
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
                            <label for="student_name" class="form-label">Student Name</label>
                            <input type="text" class="form-control form-control-sm" id="student_name" name="student_name">
                        </div>
                        <div class="mb-3">
                            <label for="course_name" class="form-label">Courset Name</label>
                            <input type="text" class="form-control form-control-sm" id="course_name" name="course_name">
                        </div>

                        <div class="mb-3">
                            <label for="grade" class="form-label">Student Grade</label>
                            <select name="grade" id="grade">
                                <option value="A+">A+</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
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
        <!-- Students Section -->
        <div class="container my-4">
            <div class="card mb-4 pb-2" id="students">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title"><i class="fas fa-users me-2"></i> Assign Grades</h5>

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
                                    <th>Student Name</th>
                                    <th>Course</th>
                                    <th>Grade</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                require("partials/contodb.php");

                                $stmt = $conn->prepare("SELECT e.student_id, e.student_name, e.course_name, g.grade, g.id FROM enrollments e LEFT JOIN students_grades g ON e.student_id = g.student_id WHERE e.course_name = :course_name");
                                $stmt->bindParam(":course_name", $teacher_course);
                                $stmt->execute();
                                $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                $students= $stmt->fetchAll();
                                $count = count($students);


                                if (count($students) > 0) {
                                    foreach ($students as $student) {
                                        

                                        $userjson = json_encode($student);
                                        echo "
                                        <tr>
                                            <td>{$student['student_id']}</td>
                                        
                                            <td>
                                                {$student['student_name']}
                                            </td>
                                            <td>
                                                {$student['course_name']}
                                            </td>
                                            <td class= 'grade-cell text-center'>
                                                {$student['grade']}
                                            </td>
                                            <td class= 'text-center'>
                                                <button class='btn btn-sm btn-outline-primary'><i class='fas fa-eye'></i></button>
                                                <button class='btn btn-sm btn-outline-warning' data-bs-toggle='modal' data-bs-target='#updateModal' onclick='updatedata(`" . $userjson . "`)'><i class='fas fa-edit'></i></button>
                                                <button class='btn btn-sm btn-outline-danger' data-bs-toggle='modal' data-bs-target='#deleteModal' onclick='deletedata({$student['id']})'>
                                                    <i class='fas fa-trash'></i>
                                                </button>
                                            </td>
                                            ";
                                        

                                        echo " </tr>
                                    
                                    ";
                                    }
                                } else {
                                    echo '<tr><td colspan="5" class="text-center text-muted">No students or courses found.</td></tr>';
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

            function updatedata(userjson) {
                let user = JSON.parse(userjson);
                console.log(user);
                document.getElementById("edit_id").value = user.id;
                document.getElementById("student_name").value = user.student_name;
                document.getElementById("course_name").value = user.course_name;
                document.getElementById("grade").value = user.grade;
            }
            document.addEventListener("DOMContentLoaded", function () {
                 const gradeCells = document.querySelectorAll(".grade-cell");
                gradeCells.forEach(cell => {
        const grade = cell.innerText.trim();

        switch (grade) {
            case "A+":
                cell.style.backgroundColor = "#28a745";

                cell.style.color = "#fff";
                break;
            case "A":
                cell.style.backgroundColor = "#7ed957";
                cell.style.color = "#000";
                break;
            case "B":
                cell.style.backgroundColor = "#ffc107";
                cell.style.color = "#000";
                break;
            case "C":
                cell.style.backgroundColor = "#fd7e14"; 
                cell.style.color = "#000";
                break;
            case "D":
                cell.style.backgroundColor = "#dc3545";
                cell.style.color = "#fff";
                break;
            default:
                cell.style.backgroundColor = "#f0f0f0";
                cell.style.color = "#000";
         }
        })   
    }) 
            
        </script>
        <!-- Bootstrap JS (includes Popper.js) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    </body>

    </html>
