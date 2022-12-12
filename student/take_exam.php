<?php
session_start();
include "../db.php";

    if(isset($_POST["submit"])){
        echo "Your answers are <br>";
        foreach(array_keys($_POST) as $x){
            if($x != "submit"){
                echo $x .":" .$_POST[$x] ."<br>";
            }
        }
        return;
    }
?>

<html lang="en">
<body>
    <h1>Quiz</h1>
    <form method=post action="take_exam.php">
        <p>
            Q1: The pace of this course
            <br>
            <input type="radio" id="Q1a" name="Q1" value="A">
            <label for="Q1a">A: is too slow</label><br>
            <input type="radio" id="Q1b" name="Q1" value="B">
            <label for="Q1b">B: is just right</label><br>
            <input type="radio" id="Q1c" name="Q1" value="C">
            <label for="Q1c">C: is too fast</label><br>
            <input type="radio" id="Q1d" name="Q1" value="D">
            <label for="Q1d">D: I don't know</label><br>
            <br>

            Q2: The feedback from homework assignment grading
            <br>
            <input type="radio" id="Q2a" name="Q2" value="A">
            <label for="Q2a">A: too few</label><br>
            <input type="radio" id="Q2b" name="Q2" value="B">
            <label for="Q2b">B: sufficient</label><br>
            <input type="radio" id="Q2c" name="Q2" value="C">
            <label for="Q2c">C: I don't know</label><br>
            <br>

            Q3: Anything you like about the teaching of this course?
            <label for="Q3"></label>
            <br>
            <textarea rows="4" cols="50" id="Q3" name="Q3"></textarea>
            <br>
            <br>
            <input type="submit" name="submit">
        </p>
    </form>
</body>
</html>
