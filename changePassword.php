<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
</head>
<script>

</script>
<body>
<h1>Create New Password</h1>
<div style="border: thin black solid; width: fit-content; padding: 5px">
    <form method="post" action="changePassword.php">
        <div>
            <label for="password1">New Password:</label>
            <br>
            <input type="password" name="password1" id="password1">
        </div>
        <br>
        <div>
            <label for="password2">Repeat Password:</label>
            <br>
            <input type="password" name="password2" id="password2">
        </div>
        <br>
        <input type="submit" name="pwchange" style="padding: 5px; width: 100%">
        <!--                    <p style="visibility: hidden; color: red">Incorrect username or password</p>-->
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

if( isset($_POST["pwchange"]) && isset($_SESSION["username"])) {
    if( $_POST["password1"] == $_POST["password2"]){
        changePassword($_SESSION["username"], $_SESSION["user_type"], $_POST["password1"]);
        session_destroy();
        header("Location: login.php");
    }
    else{
        echo '<p style="color:red">Passwords do not match</p>';
    }
}
?>