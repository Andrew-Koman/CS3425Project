<?php
session_start();

require "db.php";
if (!isset($_SESSION["username"]) || $_SESSION["user_type"] != "instructors") {
    header("Location: ./login.php");
    return;
}
echo '<p style="text-align: right"> Welcome ' . $_SESSION["username"] . '</p>';
?>
<form method="post" action="login.php">
    <p style="text-align: right">
        <input type="submit" value="logout" name="logout">
    </p>
</form>

<h1>Current Courses:</h1>
<?php
