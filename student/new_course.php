<?php
include "../db.php";

session_start();
is_logged_on();

if (!isset($_POST['new_course'])){
    header("Location: main.php");
    return;
}

$course_id = $_POST['new_course'];
$course_title = getCourseTitle(intval($course_id));
$student = $_SESSION['student']; 
$student_id = getStudentId($student);

// Checking if the student is already enroled in the course_id
$dbh = connectDB();
$statement = $dbh -> prepare("SELECT * 
    FROM takes_course 
    WHERE student_id = :student
    AND course_id = :course_id");
$statement -> bindParam(":student", $student);
$statement -> bindParam(":course_id", $course_id);
$statement -> execute();
$row = $statement -> fetch();

if (!$row){
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