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


$showAlert= false;
$showErr = false;
$successMsg= "";
$errMsg= "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    

    if (isset($_POST['enroll'])){

        $student_id = $_POST['student_select'];
        $student_name = $_POST['student_select'];
        $course_id = $_POST['course_select'];

        $nameStmt = $conn->prepare("SELECT username FROM users WHERE user_id = :id");
        $nameStmt->bindParam(':id', $student_id);
        $nameStmt->execute();
        $student_name = $nameStmt->fetchColumn();

        $coursestmt= $conn->prepare("SELECT name, description FROM courses WHERE id = :course_id");
        $coursestmt->bindParam(':course_id', $course_id);
        $coursestmt -> execute();
        $course_name= $coursestmt->fetchColumn();


        // Check if student is enrolled
        $check = $conn->prepare("SELECT * FROM enrollments WHERE student_id = :student_id AND course_id = :course_id");
        $check->bindParam(':student_id', $student_id);
        $check->bindParam(':course_id', $course_id);
        $check->execute();

        if ($check->rowCount() > 0) {
            $showErr = true;
            $errMsg= "Student already enrolled in this course.";
        } else {
            $stmt = $conn->prepare("INSERT INTO enrollments (student_id, student_name, course_id, course_name) VALUES (:student_id, :student_name, :course_id, :course_name)");
            $stmt->bindParam(':student_id', $student_id);
            $stmt->bindParam(':student_name', $student_name);
            $stmt->bindParam(':course_id', $course_id);
            $stmt->bindParam(':course_name', $course_name);
            $stmt->execute();

            $showAlert= true;
            $successMsg= "Student successfully enrolled!";
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
        select{
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
        
        select, button {
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
            border : "none" ;
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
        if($showAlert){
            echo '
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> '.$successMsg.'.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            ';
        }

    ?>
    <?php
        if($showErr == true){
            echo '
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> '.$errMsg.'.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            ';
        }
    
    ?>
    <div class="main-wrapper">
        <div class="container">
            <h2>Enroll Student in Course</h2>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
                <div class="mb-3">
                    <label class="form-label">Student:</label>
                    <select name="student_select" class="form-select">
                        <?php
                        // Get students
                        $stmt = $conn->query("SELECT user_id, username FROM users WHERE role='student'");
                        $stmt->execute();
                        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($students as $student) {
                            echo "<option value='{$student['user_id']}'>{$student['username']}</option>";
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



                <button type="submit" name="enroll" id="button" class="btn">Enroll Student</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>