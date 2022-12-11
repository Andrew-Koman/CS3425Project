<style><?php include '../style.css'; ?></style>
<?php

include "../web_funcs.php";
session_start();

echo "<pre>";
echo "<p>Post:</p>";
print_r($_POST);
echo "<p>Session:</p>";
print_r($_SESSION);
echo "</pre>";

is_logged_on();

if( !isset($_POST["check_score"]) ) {
    header("Location: main.php");
    return;
}
findExam($_POST["exam"], $_POST["course"]);

function findExam($exam, $course) {
    $dbh = connectDB();
    $statement = $dbh -> prepare("SELECT * FROM exams e JOIN courses c on e.course_id = c.course_id WHERE e.name = :exam AND c.title = :course");
    $statement -> bindParam(":exam", $exam);
    $statement -> bindParam(":course", $course);
    $result = $statement -> fetchAll();

    createTable($result, array(""));
}

?>