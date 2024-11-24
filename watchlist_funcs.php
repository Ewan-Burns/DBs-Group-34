 <?php

require_once 'database_connect.php';

if (!isset($_POST['functionname']) || !isset($_POST['item'])) {
  return;
}

// Extract arguments from the POST variables:
$item = $_POST['item'];
$user = $_POST['user'];

// Extract itemID and userID from the arguments
if (isset($item) && isset($user)) {
  $item_id = $item; // itemID
  $user_id = $user; // userID
}

if ($_POST['functionname'] == "add_to_watchlist") {
  // Prepare SQL statement to prevent SQL injection
  $sql = "INSERT INTO Watchlist (userID, itemID) VALUES (?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $user_id, $item_id);

  if ($stmt->execute()) {
      $res = "success";  // If the query executes successfully
  } else {
      $res = "failure";  // If the query fails
      error_log("SQL Error: " . $stmt->error);  // Log the error for debugging
  }

  $stmt->close();
  $conn->close();
}

else if ($_POST['functionname'] == "remove_from_watchlist") {
  // TODO: Update database and return success/failure.

  // Prepare SQL statement to prevent SQL injection
  $sql = "DELETE FROM Watchlist WHERE userID = ? AND itemID = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $user_id, $item_id);

  if ($stmt->execute()) {
      $res = "success";  // If the query executes successfully
  } else {
      $res = "failure";  // If the query fails
      error_log("SQL Error: " . $stmt->error);  // Log the error for debugging
  }

  $stmt->close();
  $conn->close();

  $res = "success";
}

// Note: Echoing from this PHP function will return the value as a string.
// If multiple echo's in this file exist, they will concatenate together,
// so be careful. You can also return JSON objects (in string form) using
// echo json_encode($res).
echo $res;

?>