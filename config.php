<?php
// Database connection for M/S Pandey Store (Utensils)
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "ms_pandey_store";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Set timezone for Assam/India
date_default_timezone_set('Asia/Kolkata');

// Support for Indian Rupee symbol and Assamese/Hindi names
$conn->set_charset("utf8");
?>