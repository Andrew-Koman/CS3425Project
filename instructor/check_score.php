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

if( !isset($_POST["check_score"]) ) {
    header("Location: main.php");
    die();
}
if (!examExists($_POST["exam"], $_POST["course"])){
    echo "<p style='color: red'>Error. Could not find exam</p>";
}
else {
    echo "<h1>Exam results for " . $_POST["exam"] . "</h1>";
    getExamInfo();
    echo "<br>";
    getSubmissions();
}
?>

<html lang="en">
    <form action="main.php">
        <button type="submit">Go Back</button>
    </form>
</html>
