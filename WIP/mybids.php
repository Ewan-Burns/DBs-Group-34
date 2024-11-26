<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container">

<h2 class="my-3">My bids</h2>

<?php
  // This page is for showing a user the auctions they've bid on.
  // It will be pretty similar to browse.php, except there is no search bar.
  // This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.
  
  require_once 'database_connect.php';
  
  // TODO: Check user's credentials (cookie/session).

  //session_start(); // Start the session

  // Hardcode a userID for testing purposes
  $_SESSION['userID'] = 1; // Replace 1 with the desired user ID


  if (isset($_SESSION['userID'])) {
    // User recognised.
    // Display content, create/update sessionvariables.
    $user_id = $_SESSION['userID'];


    // TODO: Perform a query to pull up the auctions they've bidded on.

    // Fetch items from the database
    $query = "SELECT 
            Items.itemID,
            Items.auctionTitle,
            Items.image,
            Items.description,
            Items.endDate,
            bids_summary.max_bid
          FROM 
            Items
          JOIN 
            (SELECT itemID, MAX(amount) AS max_bid 
             FROM Bids 
             WHERE userID = $user_id 
             GROUP BY itemID) AS bids_summary 
          ON Items.itemID = bids_summary.itemID";


    $result = $conn->query($query);


    // TODO: Loop through results and print them out as list items.

    // Check if the query was successful
    if ($result && $result->num_rows > 0) {
        // Loop through each item and call the function to display it
        while ($row = $result->fetch_assoc()) {
            print_listing_li(
                $row['itemID'],
                $row['auctionTitle'],
                $row['image'],
                $row['description'],
                $row['max_bid'],
                1,
                new DateTime($row['endDate'])
            );
        }
    } else {
        echo "You have not bid on any items yet.";
    }
  }
  
?>

<?php include_once("footer.php")?>