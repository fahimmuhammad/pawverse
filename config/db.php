<?php
// config/db.php
$host = "localhost";
$user = "root";  // or your MySQL username
$pass = "";      // your MySQL password (if any)
$dbname = "pawverse_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
  die("Database connection failed: " . $conn->connect_error);
}
?>
