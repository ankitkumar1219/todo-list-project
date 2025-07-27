<?php
$host = "localhost";
$username = "root";
$password = ""; // default password is blank in XAMPP
$database = "todo_app";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
