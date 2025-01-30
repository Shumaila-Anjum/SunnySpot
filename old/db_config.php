<?php
// Include the database connection
// Local host Database Configuration
$server = "localhost";
$database = "sunnyspot";
$dbuser = "root";
$dbpwd = "";

// Deployment Database connection
// $server = "localhost";
// $database = "sanjum_SunnySpot";
// $dbuser = "sanjum_admin";
// $dbpwd = "admin@123!!!";

// Establish a connection
$conn = new mysqli($server, $dbuser, $dbpwd, $database);

// Check if the connection works
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>