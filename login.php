<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <script>

    </script>
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
require "db.php";

session_start();

echo "<pre>";
print_r($_POST);
print_r($_SESSION);
echo "</pre>";

if( isset($_POST["login"])) {
    $auth = authenticate($_POST["username"], $_POST["password"]);
    if ( $auth > 0) {
        $_SESSION["username"] = $_POST["username"];


        if ($auth == 1) {
            $_SESSION["user_type"] = "students";
        }
        else if ($auth == 2) {
            $_SESSION["user_type"] = "instructors";
        }
        if (checkPasswordReset($_SESSION["username"], $_SESSION["user_type"]) ){
            header("Location: changePassword.php");
            return;
        }

        switch ($_SESSION["user_type"]) {
            case "students":
                header("Location: main_student.php");
                break;
            case "instructors":
                header("Location: main_instructor.php");
                break;
            default:
                echo '<p style="color: red"> ERROR: user type not valid </p>';
        }
        return;
    }
    else {
        echo '<p style="color:red">Incorrect username and password</p>';
    }
}

if( isset($_POST["logout"])) {
    session_destroy();
}

?>