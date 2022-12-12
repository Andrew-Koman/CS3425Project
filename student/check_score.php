<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Check Score</title>
    <link rel="stylesheet" href="../style.css">
</head>
</html>
<?php
session_start();
require "../exam.php";

if (!isset($_POST['check_score']) || !isset($_POST['exam'])){
    header("Location: main.php");
    return;
}

$course_id = $_POST['course'];
$exam_name = $_POST['exam'];
$student = $_SESSION['username']; 
$student_id = getStudentId($student);

if(!examExists($exam_name, $course_id)){
    header("Location: main.php");
    return;
}
$exam_id = getExamId($exam_name, $course_id);

printExamResults($exam_id, $student_id);

?>


<form method="post" action="main.php">
        <input type="submit" value="Go Back" name="go_main">
</form>