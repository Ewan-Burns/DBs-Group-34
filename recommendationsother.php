<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php require_once("database_connect.php");?> 

<div class="container">

<h2 class="my-3">Recommendations for you</h2>

<?php
  // This page is for showing a buyer recommended items based on their bid 
  // history. It will be pretty similar to browse.php, except there is no 
  // search bar. This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.
  
  // TODO: Check user's credentials (cookie/session).
  // Check user's credentials (e.g., using session or cookie)
  // Check if the session has already been started before calling session_start()
  if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session if it's not already active
  }

  $_SESSION['user_id'] = 1; // For testing purposes, you can set the user ID here

  $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
  // TODO: Perform a query to pull up auctions they might be interested in.
  if ($user_id) {
    // First get the user's watchlist items
    $watchlist_query = "
        SELECT itemID 
        FROM Watchlist 
        WHERE userID = $user_id";
    
    $watchlist_result = mysqli_query($conn, $watchlist_query);
    
    if (mysqli_num_rows($watchlist_result) > 0) {
        // Collect watchlist items
        $watchlist_items = [];
        while ($row = mysqli_fetch_assoc($watchlist_result)) {
            $watchlist_items[] = $row['itemID'];
        }
        
        // Create comma-separated list of watchlist items
        $watchlist_items_str = implode(',', $watchlist_items);
        
        // Get carTypes from watchlist items
        $carType_query = "
            SELECT DISTINCT carTypeID 
            FROM Items 
            WHERE itemID IN ($watchlist_items_str)";
        
        $carType_result = mysqli_query($conn, $carType_query);
        
        if ($carType_result && mysqli_num_rows($carType_result) > 0) {
            // Collect carTypes
            $carTypes = [];
            while ($row = mysqli_fetch_assoc($carType_result)) {
                $carTypes[] = $row['carTypeID'];
            }
            
            // Create comma-separated list of carTypes
            $carTypes_str = implode(',', $carTypes);
            
            // Get recommendations
            $recommendations_query = "
                SELECT 
                    i.itemID,
                    i.auctionTitle,
                    i.image,
                    i.description,
                    i.startingPrice,
                    MAX(b.amount) as highestBid
                FROM Items i
                LEFT JOIN Bids b ON i.itemID = b.itemID
                WHERE i.carTypeID IN ($carTypes_str)
                AND i.itemID NOT IN ($watchlist_items_str)
                AND i.endDate > NOW()
                GROUP BY 
                    i.itemID, 
                    i.auctionTitle, 
                    i.image, 
                    i.description, 
                    i.startingPrice
                ORDER BY RAND()
                LIMIT 5";
            
            $recommendations_result = mysqli_query($conn, $recommendations_query);
            
            if ($recommendations_result && mysqli_num_rows($recommendations_result) > 0) {
                echo '<ul class="list-group">';
                while ($row = mysqli_fetch_assoc($recommendations_result)) {
                    $title = $row['auctionTitle'];
                    $description = $row['description'];
                    $current_price = $row['highestBid'] ?? $row['startingPrice'];
                    $item_id = $row['itemID'];
                    $image = $row['image'];
                    
                    print_listing_li($item_id, $title, $image, $description, 
                                   $current_price, $row['highestBid'], new DateTime());
                }
                echo '</ul>';
            } else {
                echo '<div class="alert alert-info">
                        No recommendations available at the moment.
                      </div>';
            }
        } else {
            echo '<div class="alert alert-info">
                    No recommendations based on your watchlist items.Let\'s find something based on other users bids!
                  </div>';

            

        }
    } else {
        echo '<div class="alert alert-info">
                Your watchlist is empty. Add items to your watchlist to get recommendations!
              </div>';
    }
} else {
    echo '<div class="alert alert-warning">
            Please <a href="login.php">log in</a> to see recommendations.
          </div>';
}




mysqli_close($conn);
?>

</div>

<?php include_once("footer.php")?>