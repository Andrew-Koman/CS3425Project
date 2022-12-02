<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <script>

    </script>
    <body>
        <form>
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
                        <input type="password" name="pasword" id="password">
                    </div>
                    <br>
                    <input type="submit" value="Login" style="padding: 5px; width: 100%">
                    <p style="visibility: hidden; color: red">Incorrect username or password</p>
            </div>
        </form>
    </body>
</html>
<?php
//    require "db.php";
//
//    session_start();
//    if( isset($_POST["login"])) {
//        if ()
//    }
?>