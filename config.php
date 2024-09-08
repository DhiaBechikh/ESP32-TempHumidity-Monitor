<?php
$servername = "localhost";
$username = "your username";
$password = "your password";
$dbname = "your database name ";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
