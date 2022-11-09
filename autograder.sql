DROP TRIGGER IF EXISTS autograder;
DELIMITER //
CREATE TRIGGER autograder
    AFTER UPDATE ON student_answers
    FOR EACH ROW
    BEGIN
    UPDATE takes_exam
    SET score = ( SELECT sum(points * is_correct)
    FROM student_answers NATURAL JOIN questions NATURAL JOIN answers
    WHERE student_id = NEW.student_id )
    WHERE student_id = NEW.student_id;
    END//
DELIMITER ;