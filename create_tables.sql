CREATE TABLE Instructors(
    instructor_id int PRIMARY KEY AUTO_INCREMENT,
    username varchar(20) NOT NULL,
    name varchar(50) NOT NULL,
    password char(64) NOT NULL
);

CREATE TABLE Courses(
    course_id int PRIMARY KEY AUTO_INCREMENT,
    instructor_id int,
    title varchar(50) NOT NULL,
    credits numeric(2,1) NOT NULL,

    FOREIGN KEY(instructor_id) REFERENCES Instructors(instructor_id)
                    ON DELETE SET NULL
);

CREATE TABLE Exams(
    exam_id int PRIMARY KEY AUTO_INCREMENT,
    course_id int NOT NULL,
    name varchar(50) NOT NULL,
    create_time DATETIME NOT NULL,
    open_time DATETIME,
    close_time DATETIME,

    FOREIGN KEY(course_id) REFERENCES Courses(course_id)
);

CREATE TABLE Questions(
    question_id int PRIMARY KEY AUTO_INCREMENT,
    exam_id int NOT NULL,
    description TEXT NOT NULL,
    points int NOT NULL,

    FOREIGN KEY(exam_id) REFERENCES Exams(exam_id)
);

CREATE TABLE Answers(
    answer_id int PRIMARY KEY AUTO_INCREMENT,
    question_id int NOT NULL,
    answer_letter char(1) NOT NULL,
    answer TINYTEXT NOT NULL,
    is_correct boolean NOT NULL,

    FOREIGN KEY(question_id) REFERENCES Questions(question_id)
);

CREATE TABLE Students(
    student_id int PRIMARY KEY AUTO_INCREMENT,
    username varchar(20) NOT NULL,
    name varchar(50) NOT NULL,
    password char(64) NOT NULL
);

CREATE TABLE student_answers(
    student_id int NOT NULL,
    answer_id int NOT NULL,

    FOREIGN KEY(student_id) REFERENCES Students(student_id),
    FOREIGN KEY(answer_id) REFERENCES Answers(answer_id)
);

CREATE TABLE takes_exam(
    student_id int NOT NULL,
    exam_id int NOT NULL,
    start_time DATETIME,
    end_time DATETIME,
    exam_grade numeric(3,2),

    FOREIGN KEY(student_id) REFERENCES Students(student_id),
    FOREIGN KEY(exam_id) REFERENCES Exams(exam_id)
);

CREATE TABLE takes_course(
    student_id int NOT NULL,
    course_id int NOT NULL,

    FOREIGN KEY(student_id) REFERENCES Students(student_id),
    FOREIGN KEY(course_id) REFERENCES Courses(course_id)
);


# CREATE View of Student_id, Course_id, Exam_id, Question_id, Answer_id, if_correct