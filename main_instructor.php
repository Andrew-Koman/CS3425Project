<?php
session_start();

echo "<pre>";
print_r($_POST);
print_r($_SESSION);
echo "</pre>";

require "db.php";
if (!isset($_SESSION["username"]) || $_SESSION["user_type"] != "instructors") {
    header("Location: ./login.php");
    return;
}
echo '<p> Welcome ' . getName($_SESSION["username"]) . '</p>';
?>
<form method="post" action="login.php">
    <input type="submit" value="Logout" name="logout">
</form>

<h1>Current Courses:</h1>
<?php
    print_r(getCoursesIns($_SESSION["username"]));
?>