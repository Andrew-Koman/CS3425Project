<?php
include "db.php";

session_start();
is_logged_on();

if (!isset($_POST['new_course'])){
    header("Location: main_student.php");
    return;
}

$dbh = connectDB();
$statement = $dbh -> prepare("
    SELECT * FROM takes_course ");
?>