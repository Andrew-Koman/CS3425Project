<?php
include "../db.php";
include "../exam.php";

session_start();

if (!is_logged_on()) {
    header("Location: ../login.php");
}


?>