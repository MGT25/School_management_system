<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Fauget School Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/styles.css">
    <style>
    </style>
</head>
<body>
    <div class="container">
         <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
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
                                        <td>
                                            {$course['name']}
                                        </td>
                                        <td style='max-width: 300px;'>{$course['description']} </td>
                                        <td>
                                            <button class='btn btn-sm btn-danger' style='min-width: 90px;' onclick='enroll(this)'>
                                                Enroll
                                            </button>
                                        </td>
                                   </tr>
                                
                                ";
                                }
                            }


                            ?>

                        </tbody>
                    </table>
                </div>
    </div>
<script>
    function enroll(button) {
        if (button.innerHTML.trim() === "Enroll") {
            button.innerHTML = "Enrolled";
            button.style.backgroundColor = "green";
            button.style.color = "white";
            button.style.border = "none";
        } else {
            button.innerHTML = "Enroll";
            button.style.backgroundColor = ""; // resets to original (Bootstrap's red)
            button.style.color = "";
            button.style.border = "";
        }
    }
</script>
</body>
</html>

<?php
require("partials/contodb.php");

if ($_SERVER['REQUEST_METHOD'] === "POST") {

  $student_id = $_POST['student_id'];
  $student_name = $_POST['student_name'];
  $course_id = $_POST['course_id'];
  print_r($student_name);
  exit();
 
  $stmt0= $conn->prepare("SELECT name FROM students WHERE id = :student_id");
  $stmt0->bindParam(":student_id", $student_id);
  $stmt0->execute();
  $names = $stmt0->fetchAll(PDO::FETCH_ASSOC);
  if(count($names) < 0){
    echo "student not found";
  }else{
    foreach($names as $name){
        $student_name = $name['name'];
    }
  }

  // Optional: Check if already enrolled
  $check = $conn->prepare("SELECT * FROM enrollments WHERE student_id = :student_id AND course_id = :course_id");
  $check->bindParam(':student_id', $student_id);
  $check->bindParam(':course_id', $course_id);
  $check->execute();

  if ($check->rowCount() > 0) {
    echo "Student already enrolled in this course.";
  } else {
    $stmt = $conn->prepare("INSERT INTO enrollments (student_id, student_name, course_id) VALUES (:student_id, :student_name, :course_id)");
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':student_name', $student_name);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->execute();

    echo "Student successfully enrolled!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Fauget School Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/styles.css">
    <style>
    </style>
</head>

<body>
    <div class="container">
        <form method="POST" action="enrollment.php">
            <label>Student:</label>
            <select name="student_id">
                <?php
                // Get students
                require("partials/contodb.php");
                $students = $conn->query("SELECT id, name FROM students");
                foreach ($students as $student) {
                    echo "<option value='{$student['id']}'>{$student['name']}</option>";
                }
                ?>
            </select>

            <label>Course:</label>
            <select name="course_id">
                <?php
                // Get courses
                $courses = $conn->query("SELECT id, name FROM courses");
                foreach ($courses as $course) {
                    echo "<option value='{$course['id']}'>{$course['name']}</option>";
                }
                ?>
            </select>

            <button type="submit" name="enroll">Enroll Student</button>
        </form>
    </div>
</body>

</html>