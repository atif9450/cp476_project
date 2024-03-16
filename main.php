ob_start(); 



$connectionMySQL = connectToMySQL(); 
cleanDatabases($connectionMySQL); 
createDatabase($connectionMySQL);


print("Closing the connection to mysql\n");
$connectionMySQL->close();


$connectionMyDatabase = connectToMyDatabase(); 
createTables($connectionMyDatabase); 
dataBaseReader($fileName1,$fileName2,$connectionMyDatabase);  
outputDatabase($connectionMyDatabase); 


print("Closing the connection to " . DATABASE_NAME . "\n");
$connectionMyDatabase->close();



ob_clean(); 



?>


<html>
    <body>

    <h1>Database project</h1>


    <form action = "cp476_project_GUI.php" method = "post">
    Enter a query: <input type ="text" name ="query"><br>
    <input type = "submit" name = "show databases">
    </form>
    
    <a href="cp476_project_logout.php">Logout</a>

    <?php 

    //connect to our database
    $con=mysqli_connect(SERVER_NAME, USER_NAME, PASSWORD, DATABASE_NAME); 

    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    //first table
    echo  "<h3>" . NAME_TABLE_NAME . "</h3>";
    $sql= "SELECT * FROM ". NAME_TABLE_NAME;
    showTable($con, $sql);

    //second table
    echo  "<h3>" . COURSE_TABLE_NAME . "</h3>";
    $sql= "SELECT * FROM ". COURSE_TABLE_NAME;
    showTable($con, $sql);

    //third table
    echo  "<h3>" . FINAL_GRADE_OUTPUT_TABLE_NAME . "</h3>";
    $sql= "SELECT * FROM ". FINAL_GRADE_OUTPUT_TABLE_NAME;
    showTable($con, $sql);


    mysqli_close($con);
    ?>
    
    </body>
</html>
