<?php include_once("header.php")?>

<div class="container my-5">

<?php

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

// Check if the form was submitted

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debugging: Print the POST array
    echo "<pre>";
    var_dump($_POST);
    echo "</pre>";
    
    // Your existing code for variable extraction...
}


// Extract form variables with default empty values
$title = $_POST['auctionTitle'] ?? 'test';
$details = $_POST['auctionDetails'] ?? '';
$category = $_POST['auctionCategory'] ?? '';
$startPrice = $_POST['auctionStartPrice'] ?? '';
$reservePrice = $_POST['auctionReservePrice'] ?? '';
$endDate = $_POST['auctionEndDate'] ?? '';

// Echo all the form variables for debugging
echo "<h3>Form Data Received:</h3>";
echo "<p><strong>Auction Title:</strong> " . htmlspecialchars($title) . "</p>";
echo "<p><strong>Details:</strong> " . htmlspecialchars($details) . "</p>";
echo "<p><strong>Category:</strong> " . htmlspecialchars($category) . "</p>";
echo "<p><strong>Starting Price:</strong> " . htmlspecialchars($startPrice) . "</p>";
echo "<p><strong>Reserve Price:</strong> " . htmlspecialchars($reservePrice) . "</p>";
echo "<p><strong>End Date:</strong> " . htmlspecialchars($endDate) . "</p>";


// Initialize an array to store any validation errors
$errors = [];

//Perform validation checks

// Check if the title is provided and has a minimum length
if (empty($title) || strlen($title) < 3) {
    $errors[] = "The auction title is required and should be at least 3 characters.";
}

// Check if the category is selected (assuming "Choose..." is the default unselected value)
if ($category == "Choose...") {
    $errors[] = "Please select a category for the auction.";
}

// Check if the starting price is a positive number
if (empty($startPrice) || !is_numeric($startPrice) || $startPrice <= 0) {
    $errors[] = "The starting price is required and must be a positive number.";
}

// Reserve price is optional but must be a positive number if provided
if (!empty($reservePrice) && (!is_numeric($reservePrice) || $reservePrice <= 0)) {
    $errors[] = "The reserve price must be a positive number if specified.";
}

// Check if the end date is provided and is a valid future date
if (empty($endDate)) {
    $errors[] = "The auction end date is required.";
} else {
    $endDateTimestamp = strtotime($endDate);
    if ($endDateTimestamp === false || $endDateTimestamp <= time()) {
        $errors[] = "The end date must be a valid date and time in the future.";
    }
}



// If there are any errors, display them and stop further processing
if (!empty($errors)) {
    echo "<h2>Form Validation Errors</h2><ul>";
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul>";
} else {

    /* TODO #3: If everything looks good, make the appropriate call to insert
            data into the database. */

    // Proceed with processing the form data (e.g., inserting into the database)
    echo "All fields are valid. Proceed with auction creation.";

   // Insert query
    $sql = "INSERT INTO Items (auctionTitle, description, startingPrice, reservePrice, endDate)
    VALUES ('$title', '$details', '$startPrice', '$reservePrice', '$endDate')";

    // Execute query and check for success
    if ($conn->query($sql) === TRUE) {
        echo "<p>Success: Auction created successfully.</p>";
    } else {
        echo "<p>Error inserting auction: " . $conn->error . "</p>";
    }

    // Close the statement and connection
    $conn->close();
}


// If all is successful, let user know.
echo('<div class="text-center">Auction successfully created! <a href="FIXME">View your new listing.</a></div>');
?>

</div>


<?php include_once("footer.php")?>