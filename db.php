<?php

require "web_funcs.php";
function connectDB(): PDO
{
    $config = parse_ini_file("db.ini");
    $dbh = new PDO($config['dsn'], $config['username'], $config['password']);
    $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}

/**
 * @param $user
 * @param $password
 * @return int
 * 0 if no user found
 * 1 if student found
 * 2 if instructor found
 */
function authenticate($user, $password): int {
    try {
        $dbh = connectDB();
        $statement = $dbh -> prepare("SELECT count(*) FROM students WHERE username= :username AND password = sha2(:password, 256)");
        $statement -> bindParam(":username", $user);
        $statement -> bindParam(":password", $password);

        $statement -> execute();
        $row = $statement -> fetch();
        if ($row[0] > 0) {  // If Student was found
            return 1;
        }

        $statement = $dbh -> prepare("SELECT count(*) FROM instructors WHERE username= :username AND password = sha2(:password, 256)");
        $statement -> bindParam(":username", $user);
        $statement -> bindParam(":password", $password);
        $statement -> execute();
        $row = $statement -> fetch();
        $dbh = null;

        if ($row[0] > 0) {  //If Instructor was found
            return 2;
        }
        return 0;   // If no users were found
    }
    catch (PDOException $e){
        print "Error!" . $e -> getMessage() . "<br>";
        die();
    }
}

/**
 * Checks if the user needs to reset their password, 
 * and redirects to the password reset if they do
 */
function checkPasswordReset($username, $userType ):void {
    $dbh = connectDB();
    $statement = $dbh -> prepare("SELECT change_password FROM $userType WHERE username = :username");
    $statement -> bindParam(":username", $username);
    $statement -> execute();
    $row = $statement -> fetch();
    if ($row[0]){
        header("Location: changePassword.php");
    }
}

/**
 * Changes password for given user id
 */
function changePassword($username, $userType, $password) {
    $dbh = connectDB();
    $statement = $dbh -> prepare("UPDATE $userType " .
        "SET password = sha2(:password, 256), change_password = 0 " .
        "WHERE username = :username");
    $statement -> bindParam(":username", $username);
    $statement -> bindParam(":password", $password);

    $statement -> execute();
}

/**
 * Get full name for username
 * @param $username string Account username
 * @return string Full name corresponding to username
 */
function getName(string $username): string {
    $dbh = connectDB();

    $statement = $dbh -> prepare("SELECT name
        FROM
        (SELECT name, username FROM instructors
        UNION
        SELECT name, username FROM students) as x
        WHERE username = :username");
    $statement -> bindParam(":username", $username);
    $statement -> execute();
    $row = $statement -> fetch();
    return $row[0];
}

function getInstructorId(string $username):int{
    $dbh = connectDB();
    $statement = $dbh -> prepare("SELECT instructor_id FROM instructors WHERE username = :username");
    $statement -> bindParam(":username", $username);
    $statement -> execute();
    return ($statement -> fetch())[0];
}

function getStudentId(string $username):int{
    $dbh = connectDB();
    $statement = $dbh ->prepare("SELECT student_id FROM students WHERE username = :username");
    $statement -> bindParam(":username", $username);
    $statement -> execute();
    return ($statement -> fetch())[0];
}

function getCourseTitle(int $course_id):string{
    $dbh = connectDB();
    $statement = $dbh ->prepare("SELECT title FROM courses WHERE course_id = :course_id");
    $statement -> bindParam(":course_id", $course_id);
    $statement -> execute();
    $val = $statement -> fetch();
    if (!$val){
        return FALSE; 
    } else {
        return $val[0];
    }
}

/**
 * Gets the course information for the courses taught by the given instructor
 */
function getCoursesInstructor(string $username): ?string
{
    $dbh = connectDB();

    $statement = $dbh -> prepare("SELECT 
    courses.course_id, 
    title, 
    credits, 
    exams.name, 
    open_time,
    close_time,
    (SELECT sum(points) FROM questions WHERE questions.exam_id = exams.exam_id ) AS total_points
        FROM instructors
        NATURAL JOIN courses
        JOIN exams 
        ON courses.course_id = exams.course_id
        WHERE instructor_id = :id; ");

    $id = getInstructorId($username);
    $statement -> bindParam(":id", $id);
    $statement -> execute();
    $result = $statement -> fetchAll();
    if( !$result ){
        return "<p>No Courses Found!</p>";
    }
    $headers = array("id", "title", "credit", "exam_name", "open_time", "close_time", "total_points");
    createTable($result, $headers);
    return null;
}

?>