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
    $showErr = false;
    $successMsg = "";
    $errMsg = "";

    if ($_SERVER['REQUEST_METHOD'] === "POST") {


        if (isset($_POST['enroll'])) {

            $teacher_id = $_POST['teacher_select'];
            $course_id = $_POST['course_select'];

            // Fetch teacher name and course name in one query
            $infoStmt = $conn->prepare("SELECT u.username AS teacher_name, c.name AS course_name FROM users u, courses c WHERE u.user_id = :teacher_id AND c.id = :course_id");
            $infoStmt->bindParam(':teacher_id', $teacher_id);
            $infoStmt->bindParam(':course_id', $course_id);
            $infoStmt->execute();
            $info = $infoStmt->fetch(PDO::FETCH_ASSOC);

            if ($info) {
                $teacher_name = $info['teacher_name'];
                $course_name = $info['course_name'];

                // Check if already enrolled
                $check = $conn->prepare("SELECT * FROM enrolled_teachers WHERE teacher_id = :teacher_id AND course_id = :course_id");
                $check->bindParam(':teacher_id', $teacher_id);
                $check->bindParam(':course_id', $course_id);
                $check->execute();
                $joined = $check->fetchAll();

                if (count($joined) > 0) {
                    $showErr = true;
                    $errMsg = "Teacher '{$teacher_name}' (ID: {$teacher_id}) is already assigned to the course '{$course_name}'.";
                } else {
                    // Insert
                    $stmt = $conn->prepare("INSERT INTO enrolled_teachers (teacher_id, user_id, teacher_name, course_id, course_name) VALUES (:teacher_id, :user_id, :teacher_name, :course_id, :course_name)");
                    $stmt->bindParam(':teacher_id', $teacher_id); 
                    $stmt->bindParam(':user_id', $teacher_id);
                    $stmt->bindParam(':teacher_name', $teacher_name);
                    $stmt->bindParam(':course_id', $course_id);
                    $stmt->bindParam(':course_name', $course_name);
                    $stmt->execute();

                    $showAlert = true;
                    $successMsg = "Teacher successfully enrolled!";
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
        <title>Enrollment - School Management</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <!-- Custom CSS -->
        <link rel="stylesheet" href="css/styles.css">
        <style>
            .main-container {
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
                max-width: 500px;
                text-align: center;
            }

            h2 {
                margin-bottom: 1.5rem;
                font-weight: 600;
                color: #132043;
            }

            input,
            select {
                cursor: pointer;
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

            select:focus {
                outline: none;
                border-color: #132043;
                box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
            }

            #button {
                background: #132043;
                border: "none";
                color: white;
                border: none;
                cursor: pointer;
            }

            .form-label {
                text-align: left;
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 500;
            }
        </style>
    </head>

    <body>
        <?php
        if ($showAlert) {
            echo '
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> ' . $successMsg . '.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                ';
        }

        ?>
        <?php
        if ($showErr == true) {
            echo '
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> ' . $errMsg . '.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                ';
        }

        ?>
        <div class="main-wrapper">
            <div class="container">
                <h2>Assign Classes to Teachers</h2>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                    <div class="mb-3">
                        <label class="form-label">Teacher:</label>
                        <select name="teacher_select" class="form-select">
                            <?php
                            // Get students
                            $stmt = $conn->query("SELECT user_id, username FROM users WHERE role='teacher'");
                            $stmt->execute();
                            $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($teachers as $teacher) {
                                echo "<option value='{$teacher['user_id']}'>{$teacher['username']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Course:</label>
                        <select name="course_select" class="form-select">
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



                    <button type="submit" name="enroll" id="button" class="btn">Enroll Teacher</button>
                </form>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>