# Clear All Tables
DELETE FROM Answers WHERE TRUE;
DELETE FROM Courses WHERE TRUE;
DELETE FROM Exams WHERE TRUE;
DELETE FROM Instructors WHERE TRUE;
DELETE FROM Questions WHERE TRUE;
DELETE FROM student_answers WHERE TRUE;
DELETE FROM Students WHERE TRUE;
DELETE FROM takes_course WHERE TRUE;
DELETE FROM takes_exam WHERE TRUE;

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
CALL assign_teacher( 1, 1 );
CALL assign_teacher(2, 3);

# Insert the rest of the data
