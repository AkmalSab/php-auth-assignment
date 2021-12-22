<?php
$host = "localhost";
$username = "root";
$password = "123456789";
$database = "northwind";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";
?>