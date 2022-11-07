DROP PROCEDURE IF EXISTS create_instructor;
DROP PROCEDURE IF EXISTS create_student;
DROP PROCEDURE IF EXISTS create_course;
DROP PROCEDURE IF EXISTS assign_teacher;

DELIMITER //
SET @master_password = 'CS3425';

-- Create Instructors account with temp password
CREATE PROCEDURE create_instructor(IN ins_username VARCHAR(20), IN ins_name VARCHAR(50))
BEGIN
    INSERT INTO Instructors VALUES(NULL, ins_username, ins_name, SHA2(@master_password, 256));
    RETURN @master_password;
END//

-- Create Instructors account with temp password
CREATE PROCEDURE create_student(IN stu_username VARCHAR(20), IN stu_name VARCHAR(50))
BEGIN
    INSERT INTO Students VALUES(NULL, stu_username, stu_name, SHA2(@master_password, 256));
    RETURN @master_password;
END//

-- Create course
CREATE PROCEDURE create_course(IN cTitle VARCHAR(50), IN cCredits numeric(2,1))
BEGIN
    -- instructor_id is by default NULL
    INSERT INTO Courses VALUES(NULL, NULL, cTitle, cCredits);
END//

-- Assign instructor to create course
CREATE PROCEDURE assign_teacher(IN ins_id INT, IN crse_id INT)
BEGIN
    UPDATE Courses
    SET instructor_id = ins_id
    WHERE course_id = crse_id;
END//

DELIMITER ;