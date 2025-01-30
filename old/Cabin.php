<?php
session_start(); // Ensure session starts before any output

// Include the database connection
include 'db_config.php';

// Retrieve id from URL (for update scenario)
$data = null; // Initialize $data to avoid undefined warnings
if (isset($_GET["id"])) {
    $cabinid = $_GET["id"];
    $result = $conn->query("SELECT * FROM cabin WHERE cabinID = $cabinid");

    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
    }
}

// Handle form submission (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Allowed image types and size limit
    $allowedImageTypes = ['jpg', 'jpeg', 'png', 'gif'];
    $maxFileSize = 2 * 1024 * 1024; // 2MB file size limit
    $defaultImage = "default.jpg"; // Default image path

    // File upload handling
    $imageURL = $defaultImage; // Default image
    if (isset($_FILES["Cabin_Image"]) && $_FILES["Cabin_Image"]["error"] === UPLOAD_ERR_OK) {
        $targetDir = "images/";
        $targetFile = $targetDir . basename($_FILES["Cabin_Image"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if (in_array($imageFileType, $allowedImageTypes) && $_FILES["Cabin_Image"]["size"] <= $maxFileSize) {
            if (move_uploaded_file($_FILES["Cabin_Image"]["tmp_name"], $targetFile)) {
                $imageURL = basename($_FILES["Cabin_Image"]["name"]);
            }
        }
    }

    // Retrieve and validate form inputs
    $CabinName = $_POST['CabinName'];
    $description = $_POST['Description'];
    $pricePerNight = $_POST['Price_Per_Night'];
    $pricePerWeek = $_POST['Price_Per_Week'];

    // Insert or update logic
    if (isset($_GET["id"])) {
        $cabinid = $_GET["id"];
        $sql = "UPDATE cabin SET CabinName = ?, Description = ?, Price_Per_Night = ?, Price_Per_Week = ?, Cabin_Image = ? WHERE cabinID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdssi", $CabinName, $description, $pricePerNight, $pricePerWeek, $imageURL, $cabinid);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Cabin updated successfully!";
            header("Location: Admin_Page.php");
            exit(); // Ensure script stops after header
        }
    } else {
        $sql = "INSERT INTO cabin (CabinName, Description, Price_Per_Night, Price_Per_Week, Cabin_Image) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdss", $CabinName, $description, $pricePerNight, $pricePerWeek, $imageURL);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Cabin added successfully!";
            header("Location: Admin_Page.php");
            exit();
        }
    }
}
?>

<!---------------------------------------- HTML CODE  ----------------------------------------------->
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SunnySpot Accommodation</title>
    <link href="https://fonts.googleapis.com/css?family=Quando&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet" type="text/css">
    <script src="script.js"></script>
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

    <body>
        <?php
        if (isset($_GET["id"])) {
            ?>
            <h1>Update Cabin</h1>
            <?php
        } else {
            ?>
            <h1>Add new Cabin</h1>
            <?php

        }
        ?>

        <div class="form-container">
            <!-- When uploading files, the <form> tag must include the enctype="multipart/form-data" attribute. -->
            <form method="POST" enctype="multipart/form-data" onsubmit="return checkPricesBeforeSubmit()">
                <label for="CabinName">Cabin Name:</label>
                <input type="text" id="CabinName" name="CabinName" value="<?php if (isset($_GET['id']))
                    echo $data['CabinName'] ?>" required>

                    <label for="Description">Description:</label>
                    <input type="textarea" id="Description" name="Description" value="<?php if (isset($_GET['id']))
                    echo $data['Description'] ?>" required></inputtextarea>

                    <!-- Price per Night -->
                    <label for="Price_Per_Night">Price/Night:</label>
                    <div class="input-with-prefix">
                        <span class="currency-prefix">AU$</span>
                        <input type="number" id="Price_Per_Night" name="Price_Per_Night" min="1" oninput="validatePrices()"
                            value="<?php if (isset($_GET['id']))
                    echo $data['Price_Per_Night'] ?>" required>
                    </div>

                    <!-- Price per Week -->
                    <label for="Price_Per_Week">Price/Week:</label>
                    <div class="input-with-prefix">
                        <span class="currency-prefix">AU$</span>
                        <input type="number" id="Price_Per_Week" name="Price_Per_Week" min="1" oninput="validatePrices()"
                            value="<?php if (isset($_GET['id'])) echo $data['Price_Per_Week'] ?>" required>
                    </div>

                    <!-- Div for error messages -->
                    <div id="price-error" style="color: red; font-weight: bold; margin-bottom: 1em;"></div>

                    <!-- choose file validation --->
                <label for="Cabin_Image">Cabin Image:</label>
                <input type="file" id="Cabin_Image" name="Cabin_Image" accept="image/*" onchange="validateFileType()"
                    class="ChooseFile">

                <!-- Div to show error messages -->
                    <div id="file-error" style="color: red; font-weight: bold;"></div>

                <?php if (isset($data['Cabin_Image']) && !empty($data['Cabin_Image'])): ?>
                    <img src="images/<?php echo $data['Cabin_Image']; ?>" alt="Cabin Image"
                        style="width: 200px; margin-top: 10px;">
                <?php endif; ?>
                <!-- choose file end --->

                <input type="submit" value="SAVE" class="GreenButton">
            </form>
        </div>

        <footer>
            <div class="footer-links">
                <a href="Admin_Page.php">BACK TO ADMIN PAGE</a>
            </div>
        </footer>
    </body>

</html>
<!--------------------------------------------------------------------------------------------->