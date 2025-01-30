
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
 <!---------------------------------------- PHP CODE  ----------------------------------------------->
<?php

session_start();

    $id = $_GET['id'];  
    // Include the database connection
    include 'db_config.php';

    $stmt = $conn ->prepare("DELETE FROM cabin WHERE CabinID =?");
    $stmt->bind_param("i",$id);
  

    if ($stmt->execute()) {
        // Success: Show a success popup and redirect
        echo '<script>
                showSuccessPopup("deleted the cabin");
                window.location.href = "Admin_Page.php";
              </script>';
              $_SESSION['success_message'] = "Cabin has been deleted successfully!"; //show success message
    } else {
        // Failure: Show a failure popup and redirect
        echo '<script>
                showFailPopup("delete the cabin");
                window.location.href = "Admin_Page.php";
              </script>';
    }

    $stmt->close();
 
$conn->close();
?>