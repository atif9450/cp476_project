<?php
include $_SERVER['DOCUMENT_ROOT']."/db_funcs.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    verify_user();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="signup_page.css">
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="signin.php">Sign In</a></p>
    </div>
</body>
</html>

<style>
    .error-message {
        color: red;
        position: fixed;
        bottom: 500;
        left: 0;
        width: 100%;
        text-align: center;
        padding: 10px;
        box-sizing: border-box;
        z-index: 9999; /* Ensure it's on top of other content */
    }
</style>
