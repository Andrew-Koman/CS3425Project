<!-- 
    All exam-related functions for both students and instructors
 -->
<?php

require "db.php";


function getQuestions(int $exam_id):array{
    return [];
}

function getChoices(int $question_id):array{
    return [];
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


?>