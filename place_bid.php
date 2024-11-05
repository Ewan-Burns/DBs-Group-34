<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to make a bid.
// Notify user of success/failure and redirect/give navigation options.


// Retrieve item ID and bid amount from POST request
$item_id = $_POST['item_id'] ?? null;
$bid = $_POST['bid'] ?? null;

// Include the database connection file
require_once 'database_connect.php';

// Check if the item ID and bid amount are set
if ($item_id && $bid) {
    // Insert query
    $sql = "INSERT INTO Bids (itemID, amount)
    VALUES ('$item_id', '$bid')";

    // Execute query and check for success
    if ($conn->query($sql) === TRUE) {
        echo "<p>Your bid has been submitted for the item $item_id.</p>";
    } else {
        echo "<p>Error inserting auction: " . $conn->error . "</p>";
    }
} else {
    echo "Item ID or bid amount is missing.";
}

// Close the statement and connection
$conn->close();

?>