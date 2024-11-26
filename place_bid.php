<?php
session_start();
// TODO: Extract $_POST variables, check they're OK, and attempt to make a bid.
// Notify user of success/failure and redirect/give navigation options.


// Retrieve item ID and bid amount from POST request
$item_id = $_POST['item_id'] ?? null;
$bid = $_POST['bid'] ?? null;
//$user_id = 1;  // hard coded userID to be 1 -- need to change this

// Include the database connection file
require_once 'database_connect.php';

// Check if the user is logged in
if (isset($_SESSION['userID'])) {
    $user_id = $_SESSION['userID'];

    // Check if the item ID and bid amount are set
    if ($item_id && $bid) {
        // Insert query
        $sql = "INSERT INTO Bids (itemID, amount, userID) 
        VALUES ('$item_id', '$bid', '$user_id')";

        // Execute query and check for success
        if ($conn->query($sql) === TRUE) {
            
            // Delete the item from your watchlist if it's there
            $sql_wl = "DELETE FROM Watchlist WHERE itemID = '$item_id' AND userID = '$user_id'";
            $conn->query($sql_wl);

            header("Location: place_bid_result.php?success=true&item_id=" . urlencode($item_id));

            exit();
        } else {
            echo "<p>Error inserting auction: " . $conn->error . "</p>";
        }
    } else {
        echo "Item ID or bid amount is missing.";
    }
} else {
    echo "You must be logged in to place a bid.";
}

// Close the statement and connection
$conn->close();

?>