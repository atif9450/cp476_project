<?php

session_start();
$table = $_SESSION["table"];

$keys;
if ($table == "names") {
    $keys = "STUDENT_ID";
} else {
    $keys = "STUDENT_ID,COURSE_CODE";
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
        <input type="submit" value="Submit">
    </form>
</html>