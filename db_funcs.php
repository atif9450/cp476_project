<?php

function define_database($conn) { //generates database and initial tables
    //create database
    $stmt = "CREATE DATABASE IF NOT EXISTS CP476_PROJECT";
    $conn->query($stmt);

    //select database
    $stmt = "USE CP476_PROJECT";
    $conn->query($stmt);

    //create tables
    $stmt = "CREATE TABLE IF NOT EXISTS NAMES (
        NAME_ID INT PRIMARY KEY,
        STUDENT_NAME VARCHAR(200) NOT NULL
    )";
    $conn->query($stmt);

    $stmt = "CREATE TABLE IF NOT EXISTS COURSE_GRADES (
        STUDENT_ID INT NOT NULL,
        COURSE_CODE VARCHAR(200) NOT NULL,
        TEST_1 FLOAT NOT NULL,
        TEST_2 FLOAT NOT NULL,
        TEST_3 FLOAT NOT NULL,
        TEST_FINAL FLOAT NOT NULL,
        PRIMARY KEY (STUDENT_ID, COURSE_CODE),
        FOREIGN KEY (STUDENT_ID) REFERENCES NAMES(NAME_ID)
    )";
    $conn->query($stmt);
}

function parse_data_files($conn, $name_file_path, $course_file_path) { //parses data files
    //init variables for parameter binding
    $id = 0;
    $name = "John Doe";
    $code = "00ZZZ";
    $g1 = 0.0;
    $g2 = 0.0;
    $g3 = 0.0;
    $g4 = 0.0;

    //switch to correct database
    $stmt = "USE CP476_PROJECT";
    $conn->query($stmt);

    //read and parse NameFile.txt
    $stmt = $conn->prepare("INSERT INTO NAMES (NAME_ID, STUDENT_NAME) VALUES (?,?)");
    $stmt->bind_param("is", $id, $name);

    $file = fopen($name_file_path, "r");
    while (!feof($file)) {
        $str = fgets($file);
        $parts = explode(", ", $str);
        $id = isset($parts[0]) ? $parts[0] : 0;
        $name = isset($parts[1]) ? $parts[1] : "John Doe";
        $stmt->execute();
    }
    fclose($file);

    //read and parse CourseFile.txt
    $stmt = $conn->prepare("INSERT INTO COURSE_GRADES (STUDENT_ID, COURSE_CODE, TEST_1, TEST_2, TEST_3, TEST_FINAL) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("isdddd", $id, $code, $g1, $g2, $g3, $g4);

    $file = fopen($course_file_path, "r");
    while (!feof($file)) {
        $str = fgets($file);
        $parts = explode(", ", $str);
        $id = isset($parts[0]) ? $parts[0] : 0;
        $code = isset($parts[1]) ? $parts[1] : "00ZZZ";
        $g1 = isset($parts[2]) ? $parts[2] : 0.0;
        $g2 = isset($parts[3]) ? $parts[3] : 0.0;
        $g3 = isset($parts[4]) ? $parts[4] : 0.0;
        $g4 = isset($parts[5]) ? $parts[5] : 0.0;
        $stmt->execute();
    }
    fclose($file);

    //delete dummy rows
    $stmt = "DELETE FROM COURSE_GRADES WHERE STUDENT_ID=0";
    $conn->query($stmt);

    $stmt = "DELETE FROM NAMES WHERE NAME_ID=0";
    $conn->query($stmt);
}

function calculate_final_grades($conn) { //generates final grades table
    //switch to correct database
    $stmt = "USE CP476_PROJECT";
    $conn->query($stmt);

    //create table
    $stmt = "CREATE TABLE FINAL_GRADES AS SELECT * FROM NAMES FULL JOIN COURSE_GRADES ON NAME_ID=STUDENT_ID";
    $conn->query($stmt);

    //set primary and foreign keys
    $stmt = "ALTER TABLE FINAL_GRADES ADD PRIMARY KEY (STUDENT_ID, COURSE_CODE)";
    $conn->query($stmt);

    $stmt = "ALTER TABLE FINAL_GRADES ADD CONSTRAINT FK1 FOREIGN KEY (STUDENT_ID) REFERENCES NAMES(NAME_ID)";
    $conn->query($stmt);

    $stmt = "ALTER TABLE FINAL_GRADES ADD CONSTRAINT FK2 FOREIGN KEY (STUDENT_ID, COURSE_CODE) REFERENCES COURSE_GRADES(STUDENT_ID, COURSE_CODE)";
    $conn->query($stmt);
    
    //create final grades column
    $stmt = "ALTER TABLE FINAL_GRADES ADD COLUMN FINAL_GRADE FLOAT NOT NULL";
    $conn->query($stmt);

    $stmt = "UPDATE FINAL_GRADES SET FINAL_GRADE = 0.2*TEST_1 + 0.2*TEST_2 + 0.2*TEST_3 + 0.4*TEST_FINAL";
    $conn->query($stmt);

    //drop and rename columns
    $stmt = "ALTER TABLE FINAL_GRADES DROP COLUMN NAME_ID, DROP COLUMN TEST_1, DROP COLUMN TEST_2, DROP COLUMN TEST_3, DROP COLUMN TEST_FINAL";
    $conn->query($stmt);

    $stmt = "ALTER TABLE FINAL_GRADES RENAME COLUMN STUDENT_ID TO ID";
    $conn->query($stmt);

    $stmt = "ALTER TABLE FINAL_GRADES RENAME COLUMN STUDENT_NAME TO NAME";
    $conn->query($stmt);
}

?>