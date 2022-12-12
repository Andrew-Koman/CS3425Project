<?php
session_start();
include "db.php";



if (!isset($_POST['check_score']) || !isset($_POST['exam'])){
    header("Location: main.php");
    return;
}

$course_id = $_POST['course'];
$exam_name = $_POST['exam'];
$student = $_SESSION['student']; 
$student_id = getStudentId($student);

$dbh = connectDB();
$statement = $dbh -> prepare('
    SELECT score, start_time, end_time, (end_time - start_time)
    FROM takes_exam
    NATURAL JOIN exams
    WHERE course_id = :course_id
    AND exams.name = :exam_name
    AND student_id = :student_id');

$statement -> bindParam(":course_id", $course_id);
$statement -> bindParam(":exam_name", $exam_name);
$statement -> bindParam(":student_id", $student_id);
$statement -> execute();
$val = $statement -> fetch();
$head = array("score", "start_time", "end_time", "time_(sec)");
createTable($val, $head)
?>


<form method="post" action="main.php">
        <input type="submit" value="Go Back" name="go_main">
</form>