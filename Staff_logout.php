<?php
session_start(); // Resume the session

if (isset($_SESSION['StaffID'])) {
    $StaffID = $_SESSION['StaffID'];

    // Include the database connection
    include 'db_config.php';

    // Update the logout time in the database
    $update_sql = "UPDATE staff_login SET LogoutDateTime = NOW() WHERE StaffID = ? ORDER BY LoginDateTime DESC LIMIT 1";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("i", $StaffID);
    $stmt->execute();

    // Destroy the session
    session_unset();  // Unset all session variables
    session_destroy(); // Destroy the session

    // Redirect to login page
    header("Location: Staff_login.php");
    exit();
} else {
    // If no session, redirect to login page
    header("Location: Staff_login.php");
    exit();
}
?>
