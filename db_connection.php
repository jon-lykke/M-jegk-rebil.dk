<?php
// Database configuration
$servername = "mysql64.unoeuro.com";
$username = "jonlykke_com";
$password = "JdJbpLT4eQSG";
$database = "jonlykke_com_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>