<?php
// Check if item was purchased and POST request contains bidID
include_once("database_connect.php");

$_POST['itemID'] = 1;  // For testing purposes, you can set the bidID here

if (isset($_POST['itemID'])) {
    $userID = 1; // $_SESSION['userID'];  // The logged-in user ID
    $itemID = 2; //$_POST['itemID']; 

    // Check if the user has already rated this seller for the transaction
    $check_rating_query = "SELECT * FROM Ratings WHERE userID = ? AND itemID = ?";
    $check_stmt = $conn->prepare($check_rating_query);
    $check_stmt->bind_param("ii", $userID, $itemID);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    ?>
    <!-- Modal Header -->
    <div class="modal-header">
        <h4 class="modal-title">Rate the Seller</h4>
    </div>
    
    <!-- Modal body -->
    <div class="modal-body">
        <?php if ($check_result->num_rows == 0): ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="rating">Rate the Seller: </label>
                    <select name="rating" id="rating" class="form-control" required>
                        <option value="" disabled selected>Select a rating</option>
                        <option value="1">1 - Poor</option>
                        <option value="2">2 - Fair</option>
                        <option value="3">3 - Good</option>
                        <option value="4">4 - Very Good</option>
                        <option value="5">5 - Excellent</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary form-control">Submit Rating</button>
            </form>
        <?php else: ?>
            <div class="text-center">You have already rated this seller for this transaction.</div>
        <?php endif; ?>
    </div>
    <?php
} else {
    echo "Invalid request.";
}

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