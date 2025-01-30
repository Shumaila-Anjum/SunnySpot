<?php
// Error reporting (hide warnings for deployment)
error_reporting(0);
ini_set('display_errors', 0);

// Start the session
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['StaffID'])) {
    header("Location: Staff_login.php");
    exit();
}

// Include the database connection
include 'db_config.php';

// Fetch data from the `cabin` table
$result = $conn->query("SELECT * FROM cabin");
$data = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SunnySpot Accommodation</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <script src="script.js"></script>
    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this cabin?")) {
                window.location.href = "DeleteCabin.php?id=" + id;
            }
        }
    </script>
</head>
<body>
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="successdiv">
            <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <header>
        <div class="admin-header">
            <a href="index.php"><img src="images/accommodation.png" alt="Accommodation"></a>
            <h1>SunnySpot Accommodation</h1>
        </div>
    </header>

    <h3>ADMIN access for Add, Update and Delete Cabin details</h3>

    <div class="new-cabin-container">
        <div class="text-button-wrapper">
            <p class="new-cabin-text">Click on NEW button to add new Cabin information</p>
            <a href="Cabin.php" class="add-new-btn">NEW CABIN</a>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Cabin ID</th>
                <th>Cabin Name</th>
                <th>Description</th>
                <th>Price Per Night</th>
                <th>Price Per Week</th>
                <th>Cabin Image</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($data) > 0): ?>
                <?php foreach ($data as $Cabin): ?>
                    <tr>
                        <td><?php echo $Cabin['CabinID']; ?></td>
                        <td><?php echo $Cabin['CabinName']; ?></td>
                        <td><?php echo $Cabin['Description']; ?></td>
                        <td><?php echo "$" . $Cabin['Price_Per_Night']; ?></td>
                        <td><?php echo "$" . $Cabin['Price_Per_Week']; ?></td>
                        <td><img src="images/<?php echo $Cabin['Cabin_Image']; ?>" alt="Cabin Image" width="100"></td>
                        <td>
                            <a href="Cabin.php?id=<?php echo $Cabin['CabinID']; ?>" class="greenlink">Update</a>
                            <a onclick="return confirmDelete(<?php echo $Cabin['CabinID']; ?>)" class="redButton">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No cabins available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <footer>
        <div class="footer-links">
            <a href="index.php">HOME PAGE</a>
            <a href="Staff_logout.php">LOGOUT</a>
        </div>
    </footer>
</body>
</html>
