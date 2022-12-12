<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Instructors</title>
    <link rel="stylesheet" href="../style.css">
</head>
</html>
<?php
session_start();

echo "<pre>";
print_r($_POST);
print_r($_SESSION);
echo "</pre>";

require "../db.php";

if ($_SESSION["user_type"] != "instructors") {
    header("Location: ../login.php");
    return;
}
echo '<p> Welcome ' . getName($_SESSION["username"]) . '!</p>';
?>
<form method="post">
    <button type="submit" name="logout" formaction="../login.php">Logout</button>
    <button type="submit" name="pwchange" formaction="../changePassword.php">Change Password</button>
</form>

<h1>Current Courses:</h1>
<?php
    print_r(getCoursesInstructor($_SESSION["username"]));
?>

<p>
    Please enter the course id and the exam name to see the score of students.
</p>
<form method="post">
    <label for="course">Course:</label>
    <input type="text" name="course" id="course" required="required">
    <br>
    <label for="exam">Exam:</label>
    <input type="text" name="exam" id="exam" required="required">
    <br>
    <button type="submit" formaction="check_score.php" value="check_score" name="check_score">Check Score</button>
    <button type="submit" formaction="review_exam.php" value="review_exam" name="review_exam">Review Exam</button>
    <button type="submit" formaction="create_exam.php" value="create_exam" name="create_exam">Create Exam</button>
</form>