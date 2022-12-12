<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review Exam</title>
    <link rel="stylesheet" href="../style.css">
</head>
</html>
<?php
session_start();
include "../exam.php";

if( !isset($_POST["review_exam"]) ) {
    header("Location: main.php");
    die();
}

if (!examExists($_POST["exam"], $_POST["course"])){
    echo "<p style='color: red'>Error. Could not find exam</p>";
}
else {
    echo "<h1>Here are the questions for exam " . $_POST["exam"] . "</h1>";

    $exam_id = getExamId($_POST["exam"], $_POST["course"]);
    $questions = getQuestions($exam_id);

    echo "<div>";

    foreach ($questions as $question) {
        echo "<p>Q" . $question["question_id"] . ": " . $question["description"] . " (" . $question["points"] . " points)</p>";

        $choices = getChoices($question["question_id"]);
        echo "<div style='margin-left: 20px'>";
        foreach ($choices as $choice) {
            echo "<p>" . $choice["answer_letter"] . ": " . $choice["answer"] . " " . ($choice["is_correct"] ? "(Correct)" : "") . "</p>";
        }
        echo "</div>";
    }

    echo "</div>";
}
?>

<html lang="en">
<form action="main.php">
    <button type="submit">Go Back</button>
</form>
</html>
