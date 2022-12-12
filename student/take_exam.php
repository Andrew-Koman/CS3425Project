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

if (!isset($_POST["take_exam"]) && !isset($_POST["submit"]) ||
    isset($_POST["exam"]) && examComplete($_POST["exam"], $_SESSION["username"], $_POST["course"])) {
    header("Location: main.php");
    die();
}


if (!isset($_SESSION["exam"]) && !isset($_SESSION["course"])) {
    if( isset($_POST["exam"]) && isset($_POST["course"])) {
        $_SESSION["exam"] = $_POST["exam"];
        $_SESSION["course"] = $_POST["course"];
    }
    else {
        header("Location: main.php");
        die();
    }
}

switch (examIsOpen($_SESSION["exam"], $_SESSION["course"])){
    case 1 :
        echo "<p style='color: red'>Exam is not open yet</p>";
        echo "<p>Exam " . $_POST['exam'] . " opens at " . getExamOpen($_POST["exam"], $_POST["course"]) . "</p>";
        echo "<form action='main.php'><button type='submit'>Go Back</button></form>";
        die();
    case 2 :
        echo "<p style='color: red'>The exam is already closed</p>";
        echo "<p>Exam " . $_POST['exam'] . " closed at " . getExamClose($_POST["exam"], $_POST["course"]) . "</p>";
        echo "<form action='main.php'><button type='submit'>Go Back</button></form>";
        die();
    case 0 :
    default:
        break;
}

$student_id = getStudentId($_SESSION["username"]);

if ( isset($_SESSION["exam"]) && $_SESSION["exam"] != "" && !examExists($_SESSION["exam"], $_SESSION["course"])){
    echo "<p style='color: red'>Error. Could not find exam</p>";
} else if (!isset($_POST["submit"])){
    $exam_id = getExamId($_SESSION["exam"], $_SESSION["course"]);
    startExam($student_id, $exam_id);

    $questions = getQuestions($exam_id);
    echo "<form method='post' action='take_exam.php'>";

    $index = 0;
    foreach ($questions as $question) {
        echo "<p>Q" . $question["question_id"] . ": " . $question["description"] . " (" . $question["points"] . " points)</p>";

        $choices = getChoices($question["question_id"]);
        echo "<div style='margin-left: 20px'>";
        foreach ($choices as $choice) {
            echo "<input required='required' type='radio' id='" . $question["question_id"] . "' name='" . $question["question_id"] . "' value = '" . $choice["answer_letter"] . "'>";
            echo "<label for='" . $question["question_id"] . "'> " . $choice["answer_letter"] . ": " . $choice["answer"] . "<br>";
        }
        echo "</div>";
        $index++;
    }
    echo "<input type='submit' name='submit'>";
    echo "</form>";
} else {
    completeExam($student_id, getExamId($_SESSION["exam"], $_SESSION["course"]));
    echo "<p>Congratulations. You completed exam " . $_SESSION["exam"] .  "</p>";
    echo "<form action='main.php'><button type='submit'>Go Back</button></form>";
    unset($_SESSION["exam"]);
    unset($_SESSION["course"]);
    $_SESSION["exam_complete"] = TRUE;
    die();
}


?>