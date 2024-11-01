<?php
$DB_HOST = 'localhost';      // Replace with your host
$DB_NAME = 'my_db';  // Replace with your database name
$DB_USER = 'root';  // Replace with your username
$DB_PASSWORD = ''; // Replace with your password

// Database connection
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
