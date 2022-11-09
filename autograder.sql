DROP TRIGGER IF EXISTS autograder;
DELIMITER //
CREATE TRIGGER autograder
    AFTER UPDATE ON takes_exam
    FOR EACH ROW
    IF OLD.start_time IS NOT NULL AND OLD.end_time IS NOT NULL THEN
        UPDATE takes_exam
        SET score = ( SELECT sum(points * is_correct)
        FROM student_answers NATURAL JOIN questions NATURAL JOIN answers
        WHERE student_id = OLD.student_id
        GROUP BY exam_id, student_id ) WHERE student_id = OLD.student_id;
    END IF;
//
DELIMITER ;