<?php
session_start(); // Start session at the very beginning
    error_reporting(0); // Suppress warnings if needed (optional)

    // Check if the user is already logged in
    if (isset($_SESSION['StaffID'])) {
        // If logged in, show a popup and redirect to Admin Page
        echo '<script>
              alert("You are already logged in!");
              window.location.href = "Admin_Page.php";
            </script>';
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] === "POST") {

    // Include the database connection
    include 'db_config.php';

    $username = $_POST['loginusername'];
    $password = $_POST['loginpassword'];

    // Prevent SQL Injection
    $sql = "SELECT * FROM staff WHERE UserName = ? AND Password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $StaffID = $row['StaffID'];

        // Save StaffID in session for tracking
        $_SESSION['StaffID'] = $StaffID;
        $_SESSION['username'] = $username;

        $insert_sql = "INSERT INTO staff_login (StaffID, LoginDateTime) VALUES (?, NOW())";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("i", $StaffID);
        $insert_stmt->execute();
        // Redirect to Admin Page
        header("Location: Admin_Page.php");

    } else {
        echo '<script>
                alert("Login failed! Incorrect username or password.");
              </script>';
    }

    $stmt->close();
    $conn->close();
}
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SunnySpot Accommodation</title>
    <link href="https://fonts.googleapis.com/css?family=Quando&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <header>
        <div class="admin-header">
            <a href="index.php"> <!-- Wrap the image in a link -->
                <img src="images/accommodation.png" alt="Accommodation">
            </a>
            <h1>SunnySpot Accommodation</h1>
        </div>
    </header>

    <div class="form-container">
        <form method="POST">
            <h1> Please enter your credentials </h1>

            <label for="loginusername">Username:</label>
            <input type="text" id="loginusername" name="loginusername" required>

            <label for="loginpassword">Password:</label>
            <input type="password" id="loginpassword" name="loginpassword" required>

            <input type="submit" value="LOGIN" class="GreenButton">
        </form>
    </div>
</body>
<footer>
    <div class="footer-links">
        <a href="index.php">HOME PAGE</a>
    </div>
</footer>

</html>


