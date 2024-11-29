<?php
// Check if item was purchased and POST request contains bidID
include_once("database_connect.php");

$item_id = $_POST['item_id'] ?? null;
$rating = $_POST['rating'] ?? null;

// Check which user owns the item
$check_ownership_query = "SELECT userID FROM Items WHERE itemID = ?";
$stmt = $conn->prepare($check_ownership_query);
$stmt->bind_param("i", $item_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($owner_id);
$stmt->fetch();


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
    $stmt->bind_param("iii", $owner_id, $rating, $item_id);
    $stmt->execute();
    $stmt->close();  // Close the statement


    // Show modal with thank you message
    echo '
        <html>
        <head>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </head>
        <body>
            <!-- Modal HTML -->
            <div class="modal fade" id="thankYouModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <p>Thank you for your rating!</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="redirectToBrowse()">Done</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- JavaScript to trigger the modal -->
            <script type="text/javascript">
                $(document).ready(function() {
                    $("#thankYouModal").modal("show");
                });

                function redirectToBrowse() {
                    window.location.href = "browse.php";
                }
            </script>
        </body>
        </html>
        ';
}

?>