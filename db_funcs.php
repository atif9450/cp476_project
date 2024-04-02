<?php

function define_database() { //generates database and initial tables
    include 'config.php'; 
    //create database
    $stmt = "CREATE DATABASE IF NOT EXISTS CP476_PROJECT";
    $result = $conn->query($stmt);

    if ($result === FALSE) {
        echo "FAILED: ". $conn->error;
        exit;
    }

    //select database
    $stmt = "USE CP476_PROJECT";
    $result = $conn->query($stmt);

    if ($result === FALSE) {
        echo "FAILED: ". $conn->error;
        exit;
    }

    //create tables
    $stmt = "CREATE TABLE IF NOT EXISTS NAMES (
        NAME_ID INT PRIMARY KEY,
        STUDENT_NAME VARCHAR(200) NOT NULL
    )";
    $result = $conn->query($stmt);

    if ($result === FALSE) {
        echo "FAILED: ". $conn->error;
        exit;
    }

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
    $result = $conn->query($stmt);

    if ($result === FALSE) {
        echo "FAILED: ". $conn->error;
        exit;
    }
}
function user_login_table(){
    include 'config.php'; 
    // SQL to create user_login table
    $stmt = "CREATE TABLE IF NOT EXISTS user_login (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(30) NOT NULL,
        password VARCHAR(255) NOT NULL
    )";
    $result = $conn->query($stmt);
    
    if ($result === FALSE) {
        echo "FAILED: ". $conn->error;
        exit;
    }
}
function user_registration(){
    include 'config.php'; 
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO user_login (username, password) VALUES ('$username', '$password')";
    if ($conn->query($sql) === TRUE) {
        echo '<script type="text/javascript"> window.onload = function () { alert("Welcome"); } </script>'; 
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
function user_login(){
    include 'config.php'; 
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user_login WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            echo "Login successful";
        } else {
            echo "Incorrect password";
        }
    } else {
        echo "User not found";
    }
}
function verify_user(){
    include 'config.php'; 
        // Retrieve form data
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        // Check if username already exists
        $sql_check = "SELECT * FROM user_login WHERE username='$username'";
        $result_check = $conn->query($sql_check);
        if ($result_check->num_rows > 0) {
            echo "<p class='error-message'>Username already exists. Please choose a different one.</p>";
        } else {
        // SQL to insert data into database
        $sql = "INSERT INTO user_login (username, password) VALUES ('$username', '$password')";

        if ($conn->query($sql) === TRUE) {
            header("Location: signin.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
function parse_data_files($conn, $name_file_path, $course_file_path) { //parses data files
    include 'config.php'; 
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
    $result = $conn->query($stmt);

    if ($result === FALSE) {
        echo "FAILED: ". $conn->error;
        exit;
    }

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
    $result = $conn->query($stmt);

    if ($result === FALSE) {
        echo "FAILED: ". $conn->error;
        exit;
    }

    $stmt = "DELETE FROM NAMES WHERE NAME_ID=0";
    $result = $conn->query($stmt);

    if ($result === FALSE) {
        echo "FAILED: ". $conn->error;
        exit;
    }
}
function calculate_final_grades($conn) { //generates final grades table
    include 'config.php'; 
    //switch to correct database
    $stmt = "USE CP476_PROJECT";
    $result = $conn->query($stmt);

    if ($result === FALSE) {
        echo "FAILED: ". $conn->error;
        exit;
    }

    //create table
    $stmt = "CREATE TABLE FINAL_GRADES AS SELECT * FROM NAMES FULL JOIN COURSE_GRADES ON NAME_ID=STUDENT_ID";
    $result = $conn->query($stmt);

    if ($result === FALSE) {
        echo "FAILED: ". $conn->error;
        exit;
    }

    //set primary and foreign keys
    $stmt = "ALTER TABLE FINAL_GRADES ADD PRIMARY KEY (STUDENT_ID, COURSE_CODE)";
    $result = $conn->query($stmt);

    if ($result === FALSE) {
        echo "FAILED: ". $conn->error;
        exit;
    }

    $stmt = "ALTER TABLE FINAL_GRADES ADD CONSTRAINT FK1 FOREIGN KEY (STUDENT_ID) REFERENCES NAMES(NAME_ID)";
    $result = $conn->query($stmt);

    if ($result === FALSE) {
        echo "FAILED: ". $conn->error;
        exit;
    }

    $stmt = "ALTER TABLE FINAL_GRADES ADD CONSTRAINT FK2 FOREIGN KEY (STUDENT_ID, COURSE_CODE) REFERENCES COURSE_GRADES(STUDENT_ID, COURSE_CODE)";
    $result = $conn->query($stmt);

    if ($result === FALSE) {
        echo "FAILED: ". $conn->error;
        exit;
    }
    
    //create final grades column
    $stmt = "ALTER TABLE FINAL_GRADES ADD COLUMN FINAL_GRADE FLOAT NOT NULL";
    $result = $conn->query($stmt);

    if ($result === FALSE) {
        echo "FAILED: ". $conn->error;
        exit;
    }

    $stmt = "UPDATE FINAL_GRADES SET FINAL_GRADE = 0.2*TEST_1 + 0.2*TEST_2 + 0.2*TEST_3 + 0.4*TEST_FINAL";
    $result = $conn->query($stmt);

    if ($result === FALSE) {
        echo "FAILED: ". $conn->error;
        exit;
    }

    //drop and rename columns
    $stmt = "ALTER TABLE FINAL_GRADES DROP COLUMN NAME_ID, DROP COLUMN TEST_1, DROP COLUMN TEST_2, DROP COLUMN TEST_3, DROP COLUMN TEST_FINAL";
    $result = $conn->query($stmt);

    if ($result === FALSE) {
        echo "FAILED: ". $conn->error;
        exit;
    }

    $stmt = "ALTER TABLE FINAL_GRADES RENAME COLUMN STUDENT_ID TO ID";
    $result = $conn->query($stmt);

    if ($result === FALSE) {
        echo "FAILED: ". $conn->error;
        exit;
    }

    $stmt = "ALTER TABLE FINAL_GRADES RENAME COLUMN STUDENT_NAME TO NAME";
    $result = $conn->query($stmt);

    if ($result === FALSE) {
        echo "FAILED: ". $conn->error;
        exit;
    }
}

?>