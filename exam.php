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
        unset($_SESSION["exam"]);
        unset($_SESSION["course"]);
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

// prints the results tables
function printResults(){
    $dbh = connectDB();
    $student = getStudentId($_SESSION["username"]);
    $exam = getExamId($_SESSION["exam"], $_SESSION["course"]);
    $dbh -> beginTransaction();
    $statement = $dbh -> prepare("SELECT score, start_time, end_time, TIMESTAMPDIFF(SECOND, start_time, end_time) durration_in_sec
                            FROM takes_exam WHERE exam_id = :exam AND student_id = :student");
    $statement->bindParam(":student", $student);
    $statement->bindParam(":exam", $exam);
    $statement->execute();
    $stats_results = $statement->fetchAll();

    $statement = $dbh -> prepare("SELECT a.question_id, description, a.answer_letter, s.earned_points,
                                (SELECT ans.answer_letter FROM answers ans WHERE ans.is_correct = TRUE AND ans.question_id = q.question_id) correct
                                FROM student_answers s JOIN answers a on s.answer_id = a.answer_id
                                JOIN questions q on a.question_id = q.question_id
                                WHERE student_id = :student");
    $statement->bindParam(":student", $student);
    $statement->execute();
    $question_results = $statement->fetchAll();

    $dbh->commit();
    $headers = array("score", "start_time", "end_time", "durraction_in_sec");
    createTable($stats_results, $headers);
    echo "<br>";
    $headers = array("q_id", "description", "yourAnswer", "yourPoints", "CorrectAnswer");
    createTable($question_results, $headers);
}


// Gets all info from the takes_exam table
// function getStudentExams(int $exam_id){

// }


function examExists(string $exam, string $course): bool
{
    $dbh = connectDB();

    $statement = $dbh -> prepare("SELECT * FROM exams e JOIN courses c on e.course_id = c.course_id WHERE name = :exam AND c.course_id = :course");
    $statement -> bindParam(":exam", $exam);
    $statement -> bindParam(":course", $course);
    $statement -> execute();

    return count($statement -> fetchAll()) != 0;
}

?>