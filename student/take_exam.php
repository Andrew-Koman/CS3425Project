<?php
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

<html>
<body>
    <h1>Quiz</h1>
    <form method=post action="quiz.php">
        <p>Q1: The pace of this course
            <br>
            <input type="radio", id="Q1a" value=A name="Q1">
                <label for="Q1a">A: Is too slow</label><br>
            <input type="radio", id="Q1b" value=B name="Q1">
                <label for="Q1b">B: Is just right</label><br>
            <input type="radio", id="Q1c" value=C name="Q1">
                <label for="Q1c">C: Is too fast</label><br>
            <input type="radio", id="Q1d" value=D name="Q1">
                <label for="Q1d">D: I don't know</label><br>
        </p>
        <p>Q2: The feedback from homework assignment grading
            <br>
            <input type="radio", id="Q2a" value=A name="Q2">
                <label for="Q2a">A: Too few</label><br>
            <input type="radio", id="Q2b" value=B name="Q2">
                <label for="Q2b">B: Sufficient</label><br>
            <input type="radio", id="Q2c" value=C name="Q2">
                <label for="Q2c">C: I don't know</label><br> 
        </p>
        <p>Q3: Anything you like about the teaching of this course?
            <br>
            <textarea name="Q3" id="" cols="30" rows="5"></textarea>
        </p>
        <input type="submit" name=submit value="submit">
    </form>
</body>
</html>
