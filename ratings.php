<?php
// Check if item was purchased and POST request contains bidID
include_once("database_connect.php");

$_POST['itemID'] = 1;  // For testing purposes, you can set the bidID here
$itemID = 1; // $_POST['itemID'];
$rating = $_POST['rating'] ?? null;
$userID = 1; // For testing purposes, you can set the userID here

// Input rating - Handling form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rating'])) {
    $rating = $_POST['rating']; // The rating (1-5)

    // Confirm rating is between 1 and 5
    if ($rating < 1 || $rating > 5) {
        echo "Rating must be between 1 and 5.";
        exit;
    }

    // Insert the rating into the Ratings table
    $query = "INSERT INTO Ratings (userID, rating, itemID) 
              VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $userID, $rating, $itemID);
    $stmt->execute();

    echo "Thank you for your rating!";
    $stmt->close();  // Close the statement
}

?>