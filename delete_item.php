<?php
// Include necessary files and start the session
include_once("header.php");
require_once("utilities.php");
require_once("database_connect.php");


// Get the item_id and user_id from the POST request
$item_id = isset($_POST['item_id']) ? $_POST['item_id'] : null;
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;

// Check if the item_id and user_id are valid
if ($item_id && $user_id) {
    
    // Ensure that the user owns the item (item's userID matches session user)
    $check_ownership_query = "SELECT userID FROM Items WHERE itemID = ?";
    $stmt = $conn->prepare($check_ownership_query);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($owner_id);
    $stmt->fetch();

    
    if ($stmt->num_rows > 0) {
        // The user is the owner, so we can proceed with deletion
        $delete_query = "DELETE FROM Items WHERE itemID = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $item_id);
        
        if ($delete_stmt->execute()) {
            // Deletion successful, redirect back to the listings page
            echo "<h2>Item deleted successfully!</h2>";
            header("Location: browse.php"); // Redirect to the user's listings page
            exit();
        } else {
            echo "<h2>Failed to delete item. Please try again later.</h2>";
        }
    } else {
        echo "<h2>You do not have permission to delete this item.</h2>";
    }
    $stmt->close();
} else {
    echo "<h2>Invalid request.</h2>";
}

// Close the database connection
$conn->close();
?>

<?php include_once("footer.php") ?>
