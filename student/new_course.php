<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Course</title>
    <link rel="stylesheet" href="../style.css">
</head>
</html>
<?php
session_start();
include "../db.php";

if (!isset($_POST['new_course'])){
    header("Location: main.php");
    return;
}

$course_id = $_POST['course'];
$course_title = getCourseTitle(intval($course_id));

if (!$course_title){
    echo '<p style="color: red">Unknown course id: '.$course_id.'</p>';
}

$student = $_SESSION['username']; 
$student_id = getStudentId($student);

// Checking if the student is already enroled in the course_id
$dbh = connectDB();
$statement = $dbh -> prepare("SELECT * 
    FROM takes_course 
    WHERE student_id = :student_id
    AND course_id = :course_id");
$statement -> bindParam(":student_id", $student_id);
$statement -> bindParam(":course_id", $course_id);
$statement -> execute();
$row = $statement -> fetchAll();

if (count($row) != 0){
    echo '<p style="color: red">You are already enrolled in'.$course_id.'!</p>';
} else {
    // Use procedure enroll_student
    $statement = $dbh -> prepare("CALL enroll_student(:student_id, :course_id);");
    $statement -> bindParam(":student_id", $student_id);
    $statement -> bindParam(":course_id", $course_id);
    if($statement -> execute()){
        echo "Successfully enrolled in $course_title";
    } else {
        // If the enrollment fails, notify user
        echo '<p style="color: red>Enrollment failed!</p>';
    }
}
?>

<form method="post" action="main.php">
        <input type="submit" value="Go Back" name="go_main">
</form>