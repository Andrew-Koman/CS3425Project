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

if( !isset($_POST["take_exam"]) && !isset($_POST["submit"])) {
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
} else if (!isset($_POST["submit"])){
    $exam_id = getExamId($_POST["exam"], $_POST["course"]);
    $questions = getQuestions($exam_id);
    startExam(getStudentId($_POST["username"]), getExamId($_POST["exam"], $_POST["course"]));
    echo "<form method='post' action='take_exam.php'>";

    $index = 0;
    foreach ($questions as $question) {
        echo "<p>Q" . $question["question_id"] . ": " . $question["description"] . " (" . $question["points"] . " points)</p>";

        $choices = getChoices($question["question_id"]);
        echo "<div style='margin-left: 20px'>";
        foreach ($choices as $choice) {
            echo "<input type='radio' id='" . $question["question_id"] . "' name='" . $question["question_id"] . "' value = '" . $choice["answer_letter"] . "'>";
            echo "<label for='" . $question["question_id"] . "'> " . $choice["answer_letter"] . ": " . $choice["answer"] . "<br>";
        }
        echo "</div>";
        $index++;
    }
    echo "<input type='submit' name='submit'>";
    echo "</form>";
} else {
    completeExam(getStudentId($_SESSION["username"]), getExamId($_POST["exam"], $_POST["course"]));
    echo "<form action='main.php'><button type='submit'>Go Back</button></form>";
}


?>
