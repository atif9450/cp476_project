<?php

session_start();

$table = $_POST["select_table"];
$function = $_POST["select_function"];

$_SESSION["table"] = $table;
$_SESSION["function"] = $function;

$nextFile;
if ($function=="search" | $function=="delete") {
    $nextFile = "sd_query.php";
} else {
    $nextFile = "u_query.php";
}

header("Location: $nextFile");
exit;

?>