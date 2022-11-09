DROP TRIGGER IF EXISTS autograder;
DELIMITER //
CREATE TRIGGER autograder
    BEFORE INSERT ON student_answers
    FOR EACH ROW
    BEGIN
        SET NEW.earned_points = (
            SELECT points * is_correct
            FROM questions NATURAL JOIN answers
            WHERE answers.answer_id = NEW.answer_id
        );

        UPDATE takes_exam
        SET score = (
            SELECT sum(earned_points)
                FROM
                student_answers
                NATURAL JOIN
                answers
                NATURAL JOIN
                questions
                NATURAL JOIN
                exams
                WHERE exams.exam_id = takes_exam.exam_id AND takes_exam.student_id = student_answers.student_id
                GROUP BY student_id
                )
            WHERE takes_exam.student_id = NEW.student_id;
    END//
DELIMITER ;