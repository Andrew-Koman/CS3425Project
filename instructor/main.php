<style><?php include '../style.css'; ?></style>

<?php
session_start();

echo "<pre>";
print_r($_POST);
print_r($_SESSION);
echo "</pre>";

require "../db.php";

if (!isset($_SESSION["username"]) || $_SESSION["user_type"] != "instructors") {
    header("Location: ./login.php");
    return;
}
echo '<p> Welcome ' . getName($_SESSION["username"]) . '!</p>';
?>
<form method="post" action="../login.php">
    <input type="submit" value="Logout" name="logout">
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
    <input type="text" name="course" id="course">
    <br>
    <label for="exam">Exam</label>
    <input type="text" name="exam" id="exam">
    <br>
    <button type="submit" formaction="check_score.php" value="check_score" name="check_score">Check Score</button>
    <button type="submit" formaction="review_exam.php" value="review_exam" name="review_exam">Review Exam</button>
    <button type="submit" formaction="create_exam.php" value="create_exam" name="create_exam">Create Exam</button>
</form>