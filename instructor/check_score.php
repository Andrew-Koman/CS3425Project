<?php

include "../db.php";
session_start();

echo "<pre>";
print_r($_POST);
print_r($_SESSION);
echo "</pre>";

function findExam() {
    return;
}

?>