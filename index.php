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

  <section>
    <?php

    // Include the database connection
    include 'db_config.php';

    // Fetch data from Cabin table
    $sql = "SELECT CabinName, Description, Price_Per_Night, Price_Per_Week, Cabin_Image FROM cabin";
    $result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error); // Output the error
}
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cabinName = htmlspecialchars($row['CabinName']);
            $description = htmlspecialchars($row['Description']);
            $pricePerNight = number_format($row['Price_Per_Night'], 2);
            $pricePerWeek = number_format($row['Price_Per_Week'], 2);
            $cabinImage = htmlspecialchars($row['Cabin_Image']);
            $cabinImagePath = !empty($cabinImage) ? "images/$cabinImage" : "images/default.jpg";
            $len = mb_strlen($cabinName);
            if($len > 25){
              $cabinName = substr($cabinName,0,22);
              $cabinName = $cabinName . "...";
            }
    ?>
    <article>
      <h2 class="lineHeight"><?php echo $cabinName; ?></h2>
      <img src="<?php echo $cabinImagePath; ?>" alt="<?php echo $cabinName; ?>" width="350" height="250">
      <p><span>Details: </span><?php echo $description; ?></p>
      <p><span>Price per night: </span>A$<?php echo $pricePerNight; ?></p>
      <p><span>Price per week: </span>A$<?php echo $pricePerWeek; ?></p>
    </article>
    <?php
        }
    } else {
        echo "<p>No cabins available at the moment.</p>";
    }
    $conn->close();
    ?>
  </section>
  
  <footer>
    <div class="footer-links">
        <a href="Admin_Page.php">ADMIN PAGE</a>
    </div>
</footer>
</body>
</html>
