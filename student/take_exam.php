<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Take Exam</title>
    <link rel="stylesheet" href="../style.css">
</head>
</html>
<?php
session_start();
include "../exam.php";

if( !isset($_POST["take_exam"]) ) {
    header("Location: main.php");
    die();
}

echo "<pre>";
echo "<p>Post:</p>";
print_r($_POST);
echo "<p>Session:</p>";
print_r($_SESSION);
echo "</pre>";

if (!examExists($_POST["exam"], $_POST["course"])){
    echo "<p style='color: red'>Error. Could not find exam</p>";
} else {
    $exam_id = getExamId($_POST["exam"], $_POST["course"]);
    $questions = getQuestions($exam_id);
}
?>
