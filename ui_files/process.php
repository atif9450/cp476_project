<?php

// include "usd_functions.php";
session_start();

$table = $_SESSION["table"];
$function = $_SESSION["function"];

$keys = $_POST["key_input"];
$keys_arr = explode(",", $keys);

$values;
$values_arr;
if ($function == "update") {
    $values = $_POST["value_input"];
    $values_arr = explode(",", $values);
}

$output;
// discuss function arguments for usd
if ($function == "search") {
    // $output = search student function
} else if ($function == "delete") {
    // $output = delete student function
} else {
    // $output = update student function
}

?>

<html>
    <style>
        body {
            text-align: center;
        }
    </style>
    <body>Results:</body>
    <br><br><br>
    <a href="reset.php"><button>Start Another Query</button></a>
</html>