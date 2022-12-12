<style><?php include '../style.css'; ?></style>
<?php
    session_start();
    include "../db.php";

    // If the user is not a student, exit the session and return to log in
    if( $_SESSION['user_type'] != 'students'){
        header("Location: login.php");
        session_destroy();
        return;
    }

    // echo "<pre>";
    // print_r($_POST);
    // print_r($_SESSION);
    // echo "</pre>";

    $username = $_SESSION['username'];
    $name = getName($username);
    $student_id = getStudentId(($username));
    echo "<p>Welcome $name!</p>"; 
?>

<form method="post" action="../login.php">
        <input type="submit" value="Logout" name="logout">
</form>

<h1>Enrolled Courses</h1>
<?php
$header = array("course_id", "title", "credits", "instructor");
$dbh = connectDB();
$statement = $dbh -> prepare("SELECT course_id, title, credits, instructors.name
    FROM courses
    NATURAL JOIN instructors
    WHERE course_id IN (
        SELECT course_id
        FROM takes_course
        WHERE student_id = :student_id
        )");
$statement -> bindParam(":student_id", $student_id);
$statement -> execute();
$vals = $statement -> fetchAll();
createTable($vals, $header);
?>

<h1>Exams</h1>
<?php
$header = array("course_id", "exam_name", "open_time", "close_time", "total_points", "start_time", "end_time");
$statement = $dbh -> prepare("SELECT course_id, name, open_time, close_time, score, start_time, end_time
    FROM exams
    NATURAL JOIN takes_course
    NATURAL LEFT JOIN takes_exam
    WHERE student_id = :student_id");
$statement -> bindParam(":student_id", $student_id);
$statement -> execute();
$vals = $statement -> fetchAll();
createTable($vals, $header);
?>

<h1>Available Courses</h1>
<?php
$header = array("course_id", "title", "credits", "instructor");
$statement = $dbh -> prepare("SELECT course_id, title, credits, instructors.name
    FROM courses
    NATURAL JOIN instructors
    WHERE course_id NOT IN (
        SELECT course_id
        FROM takes_course
        WHERE student_id = :student_id
        )");
$statement -> bindParam(":student_id", $student_id);
$statement -> execute();
$vals = $statement -> fetchAll();
createTable($vals, $header);
?>

<p>
    To register for a new course, please type course id, then click "New Course" 
    <br>
    To take an exam, please type course id and the exam name, then click "Take Exam"
    <br>
    To check an exam score, please type the course id and the exam name, then click "Check Score"
</p>
<form method="post">
    <label for="course">Course:</label>
    <input type="text" name="course" id="course" required="required">
    <br>
    <label for="exam">Exam:</label>
    <input type="text" name="exam" id="exam">
    <br>
    <button type="submit" formaction="new_course.php" value="new_course" name="new_course">New Course</button>
    <button type="submit" formaction="take_exam.php" value="take_exam" name="take_exam">Take Exam</button>
    <button type="submit" formaction="check_score.php" value="check_score" name="check_score">Check Score</button>
</form>
