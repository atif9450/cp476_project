CREATE TABLE IF NOT EXISTS name (
    student_id VARCHAR(9) NOT NULL,
    student_name VARCHAR(50) NOT NULL,
    UNIQUE(student_id),
    PRIMARY KEY(student_id)
);

CREATE TABLE IF NOT EXISTS course (
    student_id VARCHAR(9) NOT NULL,
    course_id VARCHAR(10) NOT NULL,
    test_1 FLOAT DEFAULT 0,
    test_2 FLOAT DEFAULT 0,
    test_3 FLOAT DEFAULT 0,
    final_exam FLOAT DEFAULT 0,
    FOREIGN KEY(student_id) REFERENCES name(student_id)
);
