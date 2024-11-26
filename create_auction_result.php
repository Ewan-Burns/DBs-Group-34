<?php include_once("header.php")?>

<div class="container my-5">

<?php

$user_id = 1; // For testing purposes, you can set the user ID here

// This function takes the form data and adds the new auction to the database.

/* TODO #1: Connect to MySQL database (perhaps by requiring a file that
            already does this). */

// Include the database connection file
require_once 'database_connect.php';


/* TODO #2: Extract form data into variables. Because the form was a 'post'
            form, its data can be accessed via $POST['auctionTitle'], 
            $POST['auctionDetails'], etc. Perform checking on the data to
            make sure it can be inserted into the database. If there is an
            issue, give some semi-helpful feedback to user. */


// Extract form variables with default empty values
$title = $_POST['auctionTitle'] ?? '';
$description = $_POST['auctionDescription'] ?? '';
$make = $_POST['auctionMake'] ?? '';
$bodyType = $_POST['auctionBodyType'] ?? '';
$colour = $_POST['auctionColour'] ?? '';
$year = $_POST['auctionYear'] ?? '';
$mileage = $_POST['auctionMileage'] ?? '';
$startPrice = $_POST['auctionStartPrice'] ?? '';
$reservePrice = $_POST['auctionReservePrice'] ?? '';
$endDate = $_POST['auctionEndDate'] ?? '';

// Initialize an array to store any validation errors
$errors = [];

//Perform validation checks

// Check if the title is provided and has a minimum length
if (empty($title) || strlen($title) < 2) {
    $errors[] = "The auction title is required and should be at least 2 characters. Go back to the form and make the change.";
}

//Check if the description is provided 
if (empty($description)) {
    $errors[] = "The auction description is required. Go back to the form and make the change.";
}

//Check if an image file was uploaded 
// For image handling, we'll use $_FILES
$image = null;  // Initialize the image variable
// Check if an image was uploaded and there were no errors
if (isset($_FILES['auctionImage']) && $_FILES['auctionImage']['error'] === 0) {
    // The file was uploaded successfully, so read the image's content
    $image = file_get_contents($_FILES['auctionImage']['tmp_name']);
} else {

}

//Check if Make, Body Type, and Colour are selected 
if (empty($make) || $make == "Choose...") {
    $errors[] = "Please select the make of the car. Go back to the form and make the change.";
}
if (empty($bodyType) || $bodyType == "Choose...") {
    $errors[] = "Please select the body type of the car. Go back to the form and make the change.";
}
if (empty($colour) || $colour == "Choose...") {
    $errors[] = "Please select the colour of the car. Go back to the form and make the change.";
}

// Check if the year is valid year (not in the future, and after 1886 when the first car was made)
if (empty($year) || !is_numeric($year)) {
    $errors[] = "The year is required and must be a valid number. Go back to the form and make the change.";
} else {
    // Check if the year is reasonable (after 1886)
    if ($year < 1886) {
        $errors[] = "The year must be after 1886 (the year the first car was built). Go back to the form and make the change.";
    }

    // Check if the year is not a future year
    if ($year > date("Y")) {
        $errors[] = "The year cannot be in the future. Go back to the form and make the change.";
    }
}
// Check mileage is a positive number
if (empty($mileage) || !is_numeric($mileage) || $mileage < 0) {
    $errors[] = "The mileage is required and must be a positive number. Go back to the form and make the change.";
}

// Check if the starting price is a positive number
if (empty($startPrice) || !is_numeric($startPrice) || $startPrice <= 0) {
    $errors[] = "The starting price is required and must be a positive number. Go back to the form and make the change.";
}

// Reserve price must be a greater than the starting price
if (empty($reservePrice) || !is_numeric($reservePrice) || $reservePrice <= $startPrice) {
    $errors[] = "The reserve price is required and must be a greater than the starting price. Go back to the form and make the change.";
}

// Check if the end date is provided and is a valid future date
if (empty($endDate)) {
    $errors[] = "The auction end date is required. Go back to the form and make the change.";
} else {
    $endDateTimestamp = strtotime($endDate);
    if ($endDateTimestamp === false || $endDateTimestamp <= time()) {
        $errors[] = "The end date must be a valid date and time in the future. Go back to the form and make the change.";
    }
}



// If there are any errors, display them and stop further processing
if (!empty($errors)) {
    echo "<h2>Oops! Please go back to the form and correct your input(s).</h2><ul>";
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul>";
} else {

    /* TODO #3: If everything looks good, make the appropriate call to insert
            data into the database. */

    // Insert CarType infromation into CarType database table 
    $carTypeExists = false;
    $carTypeID = null;

    $sqlCheckCarType = "SELECT 
        CarTypeID 
        FROM CarTypes 
        WHERE make='$make' AND bodyType='$bodyType' AND colour='$colour' AND year='$year'";
    $resultCheckCarType = $conn->query($sqlCheckCarType);

    //Create CarTypeID or copy pre-existing CarTypeID
    if ($resultCheckCarType->num_rows > 0) {
        // Car type exists, get the CarTypeID
        $carTypeID = $resultCheckCarType->fetch_assoc()['CarTypeID'];
    } else {
        // Insert new car type
        $sqlInsertCarType = "INSERT 
            INTO CarTypes (Make, BodyType, Colour, Year) 
            VALUES ('$make', '$bodyType', '$colour', '$year')";
        if ($conn->query($sqlInsertCarType) === TRUE) {
            $carTypeID = $conn->insert_id; // Get the last inserted CarTypeID
        } else {
            echo "<p>Error inserting car type: " . $conn->error . "</p>";
            exit();
        }
    }


    // Insert query
    $sqlInsertItem = $conn->prepare(
        "INSERT INTO Items (
            userID,
            auctionTitle, 
            description, 
            startingPrice, 
            reservePrice, 
            endDate, 
            CarTypeID, 
            image
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?
        )"
    );
    // Bind the parameters: s = string, d = double, i = integer, b = blob
    // The `b` type is specific for binary data
    $sqlInsertItem->bind_param("ssddssis", $user_id, $title, $description, $startPrice, $reservePrice, $endDate, $carTypeID, $image);                     

    // Execute query and check for success
    if ($sqlInsertItem->execute()) {
        echo "<p>Success: Auction created successfully.</p>";
    } else {
        echo "<p>Error inserting auction: " . $sqlInsertItem->error . "</p>";
    }


    // Close the connection
    $sqlInsertItem->close();
    $conn->close();
}


// If all is successful, let user know.
//echo('<div class="text-center">Auction successfully created! <a href="FIXME">View your new listing.</a></div>');
?>

</div>


<?php include_once("footer.php")?>