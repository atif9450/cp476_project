<?php declare(strict_types= 1); 


include_once "header.php";

function connectToMySQL(): object { 
    $connection = null;
    
    mysqli_report(MYSQLI_REPORT_ERROR|MYSQLI_REPORT_STRICT);

    try{
    
        $connection = new mysqli(SERVER_NAME, USER_NAME, PASSWORD);
    
        if($connection->connect_error){
            die("FAILED: connecting to mysqli ". $connection->connect_error ."\n");
        }
    
        print("SUCCESS: connecting to mysqli\n");
    
        $connection->set_charset("utf8mb4");

        return $connection;
    
    }catch(Exception $e){
        error_log($e->getMessage());
        exit("\n<br><br>ERROR: connecting to mysqli. try change the password");
    }
}


function cleanDatabases(object $connection){
    
    $sql = "DROP DATABASE IF EXISTS ". DATABASE_NAME ;

    if($connection ->query($sql) === TRUE){
        echo "SUCCESS: deleting the database\n";
    }else{
        echo "FAILED: deleting the database ". $connection->error;
    }
}


function createDatabase(object $connection){
 
    $sql = "CREATE DATABASE IF NOT EXISTS " . DATABASE_NAME;
    
    if($connection ->query($sql) === TRUE){
        echo "SUCCESS: creating the database\n";
    }else{
        echo "FAILED: creating the database ". $connection->error;
    }

}



function connectToMyDatabase(): object{ 
    $connection = null;
    
    mysqli_report(MYSQLI_REPORT_ERROR|MYSQLI_REPORT_STRICT);

    try{
    
       
        $connection = new mysqli(SERVER_NAME, USER_NAME, PASSWORD, DATABASE_NAME);
    
        if($connection->connect_error){
            die("FAILED: connecting to " . DATABASE_NAME . $connection->connect_error ."\n");
        }
    
        print("SUCCESS: connecting to " . DATABASE_NAME . "\n");
    
        $connection->set_charset("utf8mb4");

        return $connection;
    
    }catch(Exception $e){
        error_log($e->getMessage());
        exit("\nERROR");
    }
}



function createTables(object $connection){

   
    $sql= "CREATE TABLE ". NAME_TABLE_NAME . "(Student_ID INT(9) PRIMARY KEY, Student_Name VARCHAR(30) NOT NULL)";

    if($connection->query($sql)===TRUE){
        echo "SUCCESS: table created\n";
    }else{
        echo "FAILED: creating table: ". $connection->error;
    }
    

  

    $sql= "CREATE TABLE ". COURSE_TABLE_NAME . "(Student_ID INT(9) , Course_Code VARCHAR(5) NOT NULL, Test_1 INT(2) NOT NULL, Test_2 INT(2) NOT NULL, Test_3 INT(2) NOT NULL, Final_Exam INT(2) NOT NULL)";
   
    if($connection->query($sql)===TRUE){
        echo "SUCCESS: table created\n";
    }else{
        echo "FAILED: creating table: ". $connection->error;
    }


    
    $sql= "CREATE TABLE ". FINAL_GRADE_OUTPUT_TABLE_NAME . "(Student_ID INT(9) , Student_Name VARCHAR(30) NOT NULL, Course_Code VARCHAR(5) NOT NULL, Final_Grade FLOAT(2) NOT NULL)";

    if($connection->query($sql)===TRUE){
        echo "SUCCESS: table created\n";
    }else{
        echo "FAILED: creating table: ". $connection->error;
    }

}



function dataBaseReader(string $fileName1, string $fileName2, object $connection ){

    $fileHandler = fopen($fileName1, "r") or die("Unable to open the file");

    while(!feof($fileHandler)){ //traverse a file
        
       

        $line = fgets($fileHandler);
        if ($line !="") { 
            $words = explode(", ", $line);

            $nameStudentID = $words[0];
            
            $studentName = trim($words[1]); 
            print($nameStudentID);
            print($studentName);

        
           

           
            $sql = $connection->prepare("INSERT INTO ". NAME_TABLE_NAME . "(Student_ID, Student_Name) VALUES(?,?)");
            $sql ->bind_param("ss", $nameStudentID, $studentName);
            $sql->execute();
   
        }

    }

    $words= null;
    fclose($fileHandler);



    $fileHandler = fopen($fileName2, "r") or die("Unable to open the file");

    while(!feof($fileHandler)){ 
        

        $line = fgets($fileHandler);
        if ($line !="") { 
            $words = explode(", ", $line);

            $courseStudentID = $words[0];
            $courseCode = $words[1];
            $test1= $words[2];
            $test2 = $words[3];
            $test3 = $words[4];
            $finalExam = $words[5];

            print($courseStudentID);
            print($courseCode);
            print("\n");

    
           
            $sql = $connection->prepare("INSERT INTO ". COURSE_TABLE_NAME . "(Student_ID, Course_Code,Test_1,Test_2,Test_3, Final_Exam ) VALUES(?,?,?,?,?,?)");
            $sql ->bind_param("ssssss", $courseStudentID, $courseCode, $test1, $test2, $test3, $finalExam);
            $sql->execute();

        }
    }
    fclose($fileHandler);

}



function outputDatabase(object $connection){
    
   
    $sql = "SELECT C.Student_ID, N.Student_Name, C.Course_Code, C.Test_1,C.Test_2, C.Test_3, C.Final_Exam FROM " . COURSE_TABLE_NAME . " AS C INNER JOIN ". NAME_TABLE_NAME . " AS N ON C.Student_ID = N.Student_ID";
    $result = $connection->query($sql);

    if($result->num_rows>0){


        while($row = $result->fetch_assoc()){
            

            $finalStudentID = $row["Student_ID"];
            $finalStudentName = $row["Student_Name"];
            $finalCourseCode = $row["Course_Code"];

            $finalGrade = $row["Test_1"] *0.20 + $row["Test_2"] *0.20 + $row["Test_3"] *0.20 +$row["Final_Exam"] *0.40;


            $sql_insert= $connection->prepare("INSERT INTO " .  FINAL_GRADE_OUTPUT_TABLE_NAME. "(Student_ID, Student_Name, Course_Code, Final_Grade) VALUES(?,?,?,?)");
            $sql_insert ->bind_param("sssd", $finalStudentID, $finalStudentName, $finalCourseCode, $finalGrade); //d = float
            $sql_insert->execute();

            }
        }
}


