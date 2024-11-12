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
    $sql = "INSERT INTO Bids (itemID, amount, userID) 
    VALUES ('$item_id', '$bid', '1')";  // hard coded userID to be 1 -- need to change this

    // Execute query and check for success
    if ($conn->query($sql) === TRUE) {
        /*
        echo "
        <div class='alert alert-success text-center p-4'>
            <h4 class='display-6' style='color: #28a745;'>Bid Placed Successfully!</h4>
            <hr class='my-4'>
            <p>Thank you for participating in the auction. Good luck!</p>
        </div>";

        // Form with button to return to the listing page
        echo "
        <form action='listing.php' method='GET' class='text-center mt-3'>
            <input type='hidden' name='item_id' value='" . htmlspecialchars($item_id) . "'>
            <button type='submit' class='btn btn-primary btn-lg'>Back to Listing</button>
        </form>";
        */
        header("Location: place_bid_result.php?success=true&item_id=" . urlencode($item_id));

        exit();
    } else {
        echo "<p>Error inserting auction: " . $conn->error . "</p>";
    }
} else {
    echo "Item ID or bid amount is missing.";
}

// Close the statement and connection
$conn->close();

?>