<?php
    include "db.php";
    include "web_funcs.php;";

    session_start();
    is_logged_on();

    // If the user is not a student, exit the session and return to login
    if($_SESSION['user_type'] != 'students'){
        header("Location: login.php");
        session_destroy();
        return;
    }

    $username = $_SESSION['username'];
    $name = getName($username);
    echo "<p>Welcome $name!</p>"; 
?>

<form method="post" action="login.php">
        <input type="submit" value="Logout", name="logout">
</form>

<h1>Enrolled Courses</h1>

<?php
echo "!! table !!";
?>

<h1>Exams</h1>

<?php
echo "!! table !!";
?>

<h1>Available Courses</h1>

<?php
echo "!! table !!";
?>

<p>
    To register for a new course, please type course id, then click "New Course" 
    <br>
    To take an exam, please type course id and the exam name, then click "Take Exam"
    <br>
    To check an exam score, please type the course id and the exam name, then click "Check Score"
</p>
<p>
    <form method="post">
        Course: <input type="text" name="course" id="course">
        <br>
        Exam: <input type="text" name="exam" id="exam">
        <br>
        <button type="submit" formaction="new_course.php" value="new_course" name="new_course">New Course</button>
        <button type="submit" formaction="take_exam.php" value="take_exam" name="take_exam">Take Exam</button>
        <button type="submit" formaction="check_score.php" value="check_score" name="take_exam">Check Score</button>
    </form>
</p>