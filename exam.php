<!-- 
    All exam-related functions for both students and instructors
 -->
<?php

require "db.php";

function getExamId(string $exam_name) : int {
    $dbh = connectDB();
    $statement = $dbh -> prepare("SELECT exam_id FROM exams WHERE name = :name");
    $statement -> bindParam(":name", $exam_name);
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

// Submits single answer
function submitAnswer(int $student_id, int $question_id){
    
}

// Marks exam as started
function startExam(int $student_id, int $exam_id){
    
}

// Marks exam as completed
function completeExam(int $student_id, int $exam_id){
    
}


// Gets all info from the takes_exam table
function getStudentExams(int $exam_id){

}


function examExists(string $exam, string $course): bool
{
    $dbh = connectDB();

    $statement = $dbh -> prepare("SELECT * FROM exams e JOIN courses c on e.course_id = c.course_id WHERE name = :exam AND title = :course");
    $statement -> bindParam(":exam", $exam);
    $statement -> bindParam(":course", $course);
    $statement -> execute();

    return count($statement -> fetchAll()) != 0;
}

?>