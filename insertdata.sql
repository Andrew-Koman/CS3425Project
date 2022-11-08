# Clear All Tables
DELETE FROM student_answers WHERE TRUE;
DELETE FROM answers WHERE TRUE;
DELETE FROM questions WHERE TRUE;
DELETE FROM takes_exam WHERE TRUE;
DELETE FROM exams WHERE TRUE;
DELETE FROM takes_course WHERE TRUE;
DELETE FROM courses WHERE TRUE;
DELETE FROM instructors WHERE TRUE;
DELETE FROM students WHERE TRUE;

# Create Instructors
CALL create_instructor('estark', 'Eddard Stark');
CALL create_instructor('tlannister', 'Tywin Lannister');
CALL create_instructor('starley', 'Robert Baratheon');

# Create Students
CALL create_student('jsnow', 'John Snow');
CALL create_student('starley', 'Samwell Tarley');
CALL create_student('astark', 'Arya Stark');
CALL create_student('sstark', 'Sansa Stark');
CALL create_student('rstark', 'Robb Stark');
CALL create_student('clannister', 'Cersi Lannister');

# Create Courses
CALL create_course('Houses and Sigils', 3);
CALL create_course('Geography', 4);

# Assign Teachers to Courses
CALL assign_teacher(1, 1);
CALL assign_teacher(3, 2);

# Insert the rest of the data
LOAD DATA LOCAL INFILE './sample_data/exams.csv' INTO TABLE exams FIELDS TERMINATED BY ',' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE './sample_data/questions.csv' INTO TABLE questions FIELDS TERMINATED BY ',' ENCLOSED BY '"' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE './sample_data/answers.csv' INTO TABLE answers FIELDS TERMINATED BY ',' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE './sample_data/student_answers.csv' INTO TABLE student_answers FIELDS TERMINATED BY ',' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE './sample_data/takes_course.csv' INTO TABLE takes_course FIELDS TERMINATED BY ',' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE './sample_data/takes_exam.csv'
    INTO TABLE takes_exam
    FIELDS TERMINATED BY ','
    IGNORE 1 LINES
    (student_id, exam_id, @start_time, @end_time, @score)
    SET score = NULL,
        start_time = NULLIF(@start_time, ''),
        end_time = NULLIF(@end_time, '');

# Show data in all tables
SELECT * FROM answers;
SELECT * FROM courses;
SELECT * FROM exams;
SELECT * FROM instructors;
SELECT * FROM questions;
SELECT * FROM student_answers;
SELECT * FROM students;
SELECT * FROM takes_course;
SELECT * FROM takes_exam;