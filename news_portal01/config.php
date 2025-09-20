<?php
// config.php
// Database connection settings

$host = "localhost";      // Database host
$user = "root";           // Database username (default in XAMPP/WAMP)
$pass = "";               // Database password (empty by default in XAMPP)
$db   = "news_portal1";    // Database name (we will create this later)

// Create connection
$conn = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Start session for login system
session_start();
?>
