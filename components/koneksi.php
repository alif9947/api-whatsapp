<?php
// Database connection credentials
$servername = "localhost"; // or 127.0.0.1
$username = "root"; // default MySQL username
$password = ""; // default MySQL password if not set
$dbname = "1_register";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


