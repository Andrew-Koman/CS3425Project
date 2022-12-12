<style><?php include '../style.css'; ?></style>
<?php

require "../db.php";
session_start();

echo "<pre>";
echo "<p>Post:</p>";
print_r($_POST);
echo "<p>Session:</p>";
print_r($_SESSION);
echo "</pre>";

if( !isset($_POST["check_score"]) ) {
    header("Location: main.php");
    return;
}
getExamInfo($_POST["exam"], $_POST["course"]);

function getExamInfo($exam, $course) {
    $dbh = connectDB();
    $dbh -> beginTransaction();

    $statement = $dbh -> prepare("SELECT course_id, total, title, completed, min, max, avg FROM (
            ( SELECT c.course_id, c.title FROM exams e JOIN courses c on e.course_id = c.course_id JOIN instructors i on c.instructor_id = i.instructor_id WHERE i.username = :username AND e.name = :exam AND c.title = :course) a
            JOIN
            ( SELECT count(*) completed, min(score) min, max(score) max, avg(score) avg FROM takes_course NATURAL JOIN courses NATURAL JOIN takes_exam WHERE title = :course AND score != 0) b
            JOIN
            (SELECT count(*) total FROM takes_course t NATURAL JOIN courses c WHERE title = :course) c
        )");

    $statement -> bindParam(":exam", $exam);
    $statement -> bindParam(":course", $course);
    $statement -> bindParam(":username", $_SESSION["username"]);
    $statement -> execute();
    $dbh -> commit();
    $result = $statement -> fetchAll();

    if( count($result) == 0 ){
        echo "<p style='color: red'>Could not find course or exam</p>";
    }

    $headers = array("c_id", "total", "exam_name", "Completed", "Minimum", "Maximum", "Average");
    createTable($result, $headers);
    $dbh = null;
}

?>

<html lang="en">
    <form action="main.php">
        <button type="submit">Go Back</button>
    </form>
</html>
