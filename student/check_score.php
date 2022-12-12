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

printResults($exam_id, $student_id);

//$dbh = connectDB();
//$statement = $dbh -> prepare('
//    SELECT score, start_time, end_time, (end_time - start_time)
//    FROM takes_exam
//    NATURAL JOIN exams
//    WHERE course_id = :course_id
//    AND exams.name = :exam_name
//    AND student_id = :student_id');
//
//$statement -> bindParam(":course_id", $course_id);
//$statement -> bindParam(":exam_name", $exam_name);
//$statement -> bindParam(":student_id", $student_id);
//$statement -> execute();
//$val = $statement -> fetchAll();
//$head = array("score", "start_time", "end_time", "time_(sec)");
//createTable($val, $head);
//
//echo "<br>";
//
//$statement = $dbh -> prepare('SELECT
//    question_id,
//    description,
//    answer,
//    (SELECT answer FROM questions NATURAL JOIN answers WHERE q.question_id = question_id AND is_correct = 1 LIMIT 1),
//    (answers.is_correct * q.points) as your_points
//FROM questions q
//NATURAL JOIN answers
//NATURAL JOIN student_answers
//NATURAL RIGHT JOIN exams
//WHERE student_id = :student_id
//AND exams.name = :exam_name');
//$statement -> bindParam(":exam_name", $exam_name);
//$statement -> bindParam(":student_id", $student_id);
//$statement -> execute();
//$val = $statement -> fetchAll();
//$head = array("question_id", "description", "answer", "correct_answer", "points_earned");
//createTable($val, $head);
?>


<form method="post" action="main.php">
        <input type="submit" value="Go Back" name="go_main">
</form>