DROP TABLE IF EXISTS takes_course;
DROP TABLE IF EXISTS takes_exam;
DROP TABLE IF EXISTS student_answers;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS answers;
DROP TABLE IF EXISTS questions;
DROP TABLE IF EXISTS exams;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS instructors;

CREATE TABLE instructors(
    instructor_id int PRIMARY KEY AUTO_INCREMENT,
    username varchar(20) NOT NULL,
    name varchar(50) NOT NULL,
    password char(64) NOT NULL,
    change_password boolean NOT NULL DEFAULT TRUE
);

CREATE TABLE courses(
    course_id int PRIMARY KEY AUTO_INCREMENT,
    instructor_id int,
    title varchar(50) NOT NULL,
    credits numeric(2,1) NOT NULL,

     FOREIGN KEY(instructor_id) REFERENCES instructors(instructor_id)
                     ON DELETE SET NULL
);

CREATE TABLE exams(
    exam_id int PRIMARY KEY AUTO_INCREMENT,
    course_id int NOT NULL,
    name varchar(50) NOT NULL,
    create_time DATETIME NOT NULL,
    open_time DATETIME,
    close_time DATETIME,

    FOREIGN KEY(course_id) REFERENCES courses(course_id)
);

CREATE TABLE questions(
    question_id int PRIMARY KEY AUTO_INCREMENT,
    exam_id int NOT NULL,
    description TEXT NOT NULL,
    points int NOT NULL,

    FOREIGN KEY(exam_id) REFERENCES exams(exam_id)
);

CREATE TABLE answers(
    answer_id int PRIMARY KEY AUTO_INCREMENT,
    question_id int NOT NULL,
    answer_letter char(1) NOT NULL,
    answer TINYTEXT NOT NULL,
    is_correct boolean NOT NULL,

    FOREIGN KEY(question_id) REFERENCES questions(question_id)
);

CREATE TABLE students(
    student_id int PRIMARY KEY AUTO_INCREMENT,
    username varchar(20) NOT NULL,
    name varchar(50) NOT NULL,
    password char(64) NOT NULL,
    change_password boolean NOT NULL DEFAULT TRUE
);

CREATE TABLE student_answers(
    student_id int NOT NULL,
    answer_id int NOT NULL,
    earned_points int,

    FOREIGN KEY(student_id) REFERENCES students(student_id),
    FOREIGN KEY(answer_id) REFERENCES answers(answer_id)
);

CREATE TABLE takes_exam(
    student_id int NOT NULL,
    exam_id int NOT NULL,
    start_time DATETIME,
    end_time DATETIME,
    score int,

    FOREIGN KEY(student_id) REFERENCES students(student_id),
    FOREIGN KEY(exam_id) REFERENCES exams(exam_id)
);

CREATE TABLE takes_course(
    student_id int NOT NULL,
    course_id int NOT NULL,

    FOREIGN KEY(student_id) REFERENCES students(student_id),
    FOREIGN KEY(course_id) REFERENCES courses(course_id)
);


# CREATE View of Student_id, Course_id, Exam_id, Question_id, Answer_id, if_correct