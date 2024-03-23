<?php

session_start();

?>

<html>
    <style>
        body {
            text-align: center;
        }
    </style>
    <form method="post" action="reroute.php">
        <label for="select_table">Select table:</label>
        <select name="select_table" id="select_table">
            <option value="names">Names</option>
            <option value="grades">Test Grades</option>
            <option value="finals">Final Grades</option>
        </select>
        <br><br>
        <label for="select_function">Select function:</label>
        <select name="select_function" id="select_function">
            <option value="search">Search</option>
            <option value="update">Update</option>
            <option value="delete">Delete</option>
        </select>
        <br><br>
        <input type="submit" value="Continue">
    </form>
</html>