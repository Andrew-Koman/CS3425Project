<!-- 
    All exam-related functions for both students and instructors
 -->
<?php

require "db.php";

function getExamId(string $exam_name, int $course_id) : int {
    $dbh = connectDB();
    $statement = $dbh -> prepare("SELECT exam_id FROM exams WHERE name = :name AND course_id = :course_id");
    $statement -> bindParam(":name", $exam_name);
    $statement -> bindParam(":course_id", $course_id);
    $statement -> execute();
    return ($statement -> fetch())[0];
}

function getQuestions(int $exam_id):array{
    $dbh = connectDB();
    $statement = $dbh -> prepare("SELECT question_id, description, points FROM questions WHERE exam_id = :id");
    $statement -> bindParam(":id", $exam_id);
    $statement -> execute();
    return $statement -> fetchAll();
}

function getChoices(int $question_id):array{
    $dbh = connectDB();
    $statement = $dbh -> prepare("SELECT answer_id, answer_letter, answer, is_correct FROM answers WHERE question_id = :id");
    $statement -> bindParam(":id", $question_id);
    $statement -> execute();
    return $statement -> fetchAll();
}


// Marks exam as started
function startExam(int $student_id, int $exam_id){
    $dbh = connectDB();
    $statement = $dbh->prepare("INSERT INTO takes_exam (student_id, exam_id, start_time) VALUES (:student, :exam, NOW())");
    $statement->bindParam(":student", $student_id);
    $statement->bindParam(":exam", $exam_id);
    $statement->execute();
}

// Marks exam as completed and sends answers to database
function completeExam(int $student_id, int $exam_id){
    $dbh = connectDB();
    $questions = getQuestions($exam_id);
    try {
        $dbh->beginTransaction();
        $statement = $dbh->prepare("UPDATE takes_exam SET end_time = NOW() WHERE student_id = :student AND exam_id = :exam");
        $statement->bindParam(":student", $student_id);
        $statement->bindParam(":exam", $exam_id);
        $statement->execute();

        foreach ($questions as $question) {
            $answer_id = getAnswerId($_POST[$question["question_id"]], $question["question_id"]);

            $statement = $dbh->prepare("INSERT INTO student_answers (student_id, answer_id) VALUES (:student, :answer)");
            $statement->bindParam(":student", $student_id);
            $statement->bindParam(":answer", $answer_id);
            $statement->execute();
        }
        $dbh->commit();
    }
    catch (PDOException $exception) {
        $dbh -> rollBack();
        echo "<p style='color: red'>ERROR. Exam could not be submitted</p>";
        echo "<pre style='color: red'>";
        print_r($exception);
        echo "</pre>";
        echo "<form action='student/main.php'><button type='submit'>Go Back</button></form>";
        die();
    }
}

function getAnswerId(string $answer_letter, int $question_id) : int{
    $dbh = connectDB();
    $statement = $dbh->prepare("SELECT answer_id FROM answers WHERE question_id = :question AND answer_letter = :answer");
    $statement->bindParam(":question", $question_id);
    $statement->bindParam(":answer", $answer_letter);
    $statement->execute();
    return ($statement->fetch())[0];
}

function getExamName(int $exam_id) : string {
    $dbh = connectDB();
    $statement = $dbh -> prepare("SELECT name FROM exams WHERE exam_id = :exam");
    $statement->bindParam(":exam", $exam_id);
    $statement->execute();
    return ($statement->fetch())[0];
}

// prints the results tables
function printExamResults($exam_id, $student_id){
    $dbh = connectDB();
    $dbh -> beginTransaction();
    $statement = $dbh -> prepare("SELECT score, start_time, end_time, TIMESTAMPDIFF(SECOND, start_time, end_time) durration_in_sec
                            FROM takes_exam WHERE exam_id = :exam AND student_id = :student");
    $statement->bindParam(":student", $student_id);
    $statement->bindParam(":exam", $exam_id);
    $statement->execute();
    $stats_results = $statement->fetchAll();

    $statement = $dbh -> prepare('SELECT question_id, description, answer,
       (SELECT answer FROM questions NATURAL JOIN answers WHERE q.question_id = question_id AND is_correct = 1 LIMIT 1),
       (answers.is_correct * q.points) as your_points
    FROM questions q
    NATURAL JOIN answers
    NATURAL JOIN student_answers
    NATURAL RIGHT JOIN exams
    WHERE student_id = :student_id
    AND exams.exam_id = :exam_id');
    $statement -> bindParam(":student_id", $student_id);
    $statement -> bindParam(":exam_id", $exam_id);
    $statement->execute();
    $question_results = $statement->fetchAll();

    $dbh->commit();
    if (count($question_results) == 0) {
        echo "<p>Sorry. You have yet to take exam " . getExamName($exam_id) . "</p>";
        echo "<form method='post' action='student/main.php'><input type='submit' value='Go Back' name='go_main'></form>";
        die();
    }
    $headers = array("score", "start_time", "end_time", "duration_in_sec");
    createTable($stats_results, $headers);
    echo "<br>";
    $headers = array("q_id", "description", "answer", "correct_answer", "points_earned");
    createTable($question_results, $headers);
}

function getExamOpen(string $exam, int $course_id) {
    try {
        $dbh = connectDB();
        $sqlstring = "select open_time from exams where course_id = :c_id and name=:exam";
        $statement = $dbh->prepare($sqlstring);
        $statement->bindParam(":c_id", $course_id);
        $statement->bindParam(":exam", $exam);
        $statement->execute();
        $x = $statement->fetch();

        return $x[0];
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function getExamClose(string $exam, int $course_id) {
    try {
        $dbh = connectDB();
        $sqlstring = "select close_time from exams where course_id = :c_id and name=:exam";
        $statement = $dbh->prepare($sqlstring);
        $statement->bindParam(":c_id", $course_id);
        $statement->bindParam(":exam", $exam);
        $statement->execute();
        $x = $statement->fetch();

        return $x[0];
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function examIsOpen(string $exam, int $course_id) : int {
    try {
        $dbh = connectDB();
        $sqlstring = "select open_time, close_time from exams where course_id = :c_id and name=:exam";
        $statement = $dbh->prepare($sqlstring);
        $statement->bindParam(":c_id", $course_id);
        $statement->bindParam(":exam", $exam);
        $statement->execute();
        $x = $statement->fetch();

        $start_time = strtotime($x[0]);
        $end_time = strtotime($x[1]);
        $now = strtotime(date('Y-m-d H:i:s'));

        if ($now > $end_time) { // Exam is closed
            return 2;
        } else if ($now < $start_time) { // Exam is not opened yet
            return 1;
        } else {
            return 0;
        }
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function examComplete(string $exam_name, string $username, int $course) : bool {
    $exam_id = getExamId($exam_name, $course);
    $student_id = getStudentId($username);
    $dbh = connectDB();
    $statement = $dbh->prepare("SELECT end_time FROM takes_exam WHERE exam_id = :exam AND student_id = :student");
    $statement->bindParam(":exam", $exam_id);
    $statement->bindParam(":student", $student_id);
    $statement->execute();
    return count($statement -> fetchAll()) != 0;
}
function examExists(string $exam, string $course): bool
{
    try {
        $dbh = connectDB();

        $statement = $dbh->prepare("SELECT * FROM exams e JOIN courses c on e.course_id = c.course_id WHERE name = :exam AND c.course_id = :course");
        $statement->bindParam(":exam", $exam);
        $statement->bindParam(":course", $course);
        $statement->execute();

        return count($statement->fetchAll()) != 0;
    }
    catch (PDOException $exception) {
        echo "<pre style='color: red'>";
        print_r($exception);
        echo "</pre>";
    }
    return FALSE;
}

function getExamInfo() {
    $dbh = connectDB();
    $dbh -> beginTransaction();
    $statement = $dbh -> prepare("SELECT 
        course_id, 
        (SELECT COUNT(student_id) FROM takes_course WHERE course_id = :course_id), 
        exams.name, 
        COUNT(student_id), 
        MIN(score), 
        MAX(score),
        AVG(score)
    FROM exams
    NATURAL JOIN takes_exam
    WHERE course_id = :course_id
    AND exam_id = :exam_id");

    $course_id = intval($_POST["course"]);
    $exam_id = getExamId($_POST["exam"], $course_id);

    $statement -> bindParam(":exam_id", $exam_id);
    $statement -> bindParam(":course_id", $course_id);
    $statement -> execute();
    $dbh -> commit();
    $result = $statement -> fetchAll();

    $headers = array("c_id", "total", "exam_name", "Completed", "Minimum", "Maximum", "Average");
    createTable($result, $headers);
    $dbh = null;
}

function getSubmissions(){
    $dbh = connectDB();
    $dbh -> beginTransaction();
    $statement = $dbh -> prepare("SELECT s.student_id, s.name, start_time, end_time, score
        FROM takes_exam te JOIN students s ON te.student_id = s.student_id JOIN exams e ON te.exam_id = e.exam_id JOIN courses c on e.course_id = c.course_id
        WHERE e.name = :exam AND c.course_id = :course");
    $statement -> bindParam(":exam", $_POST["exam"]);
    $statement -> bindParam(":course", $_POST["course"]);
    $statement -> execute();
    $result = $statement -> fetchAll();


    $headers = array("id", "name", "start_time", "end_time", "score");
    createTable($result, $headers);
    $dbh = null;
}

?>