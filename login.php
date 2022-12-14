<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <h1>Login</h1>
    <div style="border: thin black solid; width: fit-content; padding: 5px">
        <form method="post" action="login.php">
            <div>
                <label for="username">Username:</label>
                <br>
                <input type="text" name="username" id="username">
            </div>
            <br>
            <div>
                <label for="password">Password:</label>
                <br>
                <input type="password" name="password" id="password">
            </div>
            <br>
            <input type="submit" value="Login" name="login" style="padding: 5px; width: 100%">
        </form>
    </div>
    </body>
</html>
<?php
session_start();
require "db.php";


if( isset($_POST["logout"])) {
    session_destroy();
}

if( isset($_SESSION["user_type"])){
    switch ($_SESSION["user_type"]){
        case "instructors":
            header("Location: ./instructor/main.php");
            break;
        case "students":
            header("Location: ./student/main.php");
            break;
    }
}

if( isset($_POST["login"])) {
    $auth = authenticate($_POST["username"], $_POST["password"]);
    if ( $auth > 0) {
        $_SESSION["username"] = $_POST["username"];

        // Setting user type and edit header depending on user type
        if ($auth == 1) {
            $_SESSION["user_type"] = "students";
            header("Location: ./student/main.php");
        }
        else if ($auth == 2) {
            $_SESSION["user_type"] = "instructors";
            header("Location: ./instructor/main.php");
        }

        checkPasswordReset($_SESSION["username"], $_SESSION["user_type"]);

        return;
    }
    else {
        echo '<p style="color:red">Incorrect username and password</p>';
    }
}

?>