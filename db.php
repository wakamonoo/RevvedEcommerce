<?php
$servername = "localhost";
$username = "root";
$password = "12345";
$database = "ecommerce"; // Change this to your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
?>
