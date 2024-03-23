<?php

session_start();
$table = $_SESSION["table"];

$keys;
if ($table == "names") {
    $keys = "STUDENT_ID";
} else {
    $keys = "STUDENT_ID,COURSE_CODE";
}

$values;
if ($table == "names") {
    $values = "STUDENT_NAME";
} else if ($table == "grades") {
    $values = "TEST_1,TEST_2,TEST_3,FINAL_EXAM";
} else {
    $values = "STUDENT_NAME,FINAL_GRADE";
}

?>

<html>
    <style>
        body {
            text-align: center;
        }
    </style>
    <form method="post" action="process.php">
        <label for="key_input">Keys (<?php echo $keys ?>): </label>
        <input type="text" name="key_input" id="key_input">
        <br><br>
        <label for="value_input">Values (<?php echo $values ?>): </label>
        <input type="text" name="value_input" id="value_input">
        <br><br>
        <input type="submit" value="Submit">
    </form>
</html>