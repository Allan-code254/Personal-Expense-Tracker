<?php
// db.php – Connects to MySQL database

$host = "localhost";      // Typically "localhost"
$user = "root";           // Default for local dev
$pass = "";               // Empty if no password
$db   = "budget_app";     // Database name

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
