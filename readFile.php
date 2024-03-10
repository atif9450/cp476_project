<?php
$servername = "localhost";
$username = "INSERT_USERNAME";
$password = "INSERT_PASSWORD";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully\n\n";

#create a test database
$query = "CREATE DATABASE test";
$result = $conn->query($query);

if ($result == 1) {
    echo "Successful\n";
} else{
    echo "Unsuccessful\n";
}

#drop database
$query = "DROP DATABASE test";
$result = $conn->query($query);

// Close connection
$conn->close();
?>