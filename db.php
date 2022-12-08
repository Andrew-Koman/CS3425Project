<?php
function connectDB(): PDO
{
    $config = parse_ini_file("db.ini");
    $dbh = new PDO($config['dsn'], $config['username'], $config['password']);
    $dbh -> setAttribute(PDo::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
function authenticate($user, $password): int
{
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

function checkPasswordReset($username, $userType ) {
    $dbh = connectDB();
    $statement = $dbh -> prepare("SELECT change_password FROM $userType WHERE username = :username");
    $statement -> bindParam(":username", $username);
    $statement -> execute();
    $row = $statement -> fetch();

    return $row[0];
}

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
function getName(string $username): string
{
    $dbh = connectDB();

    $statement = $dbh -> prepare("SELECT name FROM instructors UNION ALL students WHERE username = :username");
    $statement -> bindParam(":username", $username);
    $statement -> execute();
    $row = $statement -> fetch();
    return $row[0];
}