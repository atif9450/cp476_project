<?php

include $_SERVER['DOCUMENT_ROOT']."/db_funcs.php";
user_login_table();

// User registration
if(isset($_POST['register'])){
    user_registration();
}

// User login
if(isset($_POST['login'])){
    user_login();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" type="text/css" href="signup_page.css">
</head>
<body>
    <div class="container">
        <h2>Sign In</h2>
        <form action="main.php" method="post">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br>
            <button type="submit">Sign In</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
</body>
</html>


