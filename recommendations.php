<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php require_once("database_connect.php");?> 

<div class="container-fluid">

<h2 class="my-3">Recommendations for you</h2>

<?php
  // Check if session is active
  if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session if it's not already active
  }

  // Check if session is active
  if (isset($_SESSION['userID'])) {
    $user_id = $_SESSION['userID'];
  } else {
    $user_id = null;
  }
  
  // Section 1: Recommendations based on the user's bids
  if ($user_id) {
    // Get the items the user has bid on
    $user_bids_query = "SELECT DISTINCT 
                            itemID 
                        FROM 
                            Bids 
                        WHERE 
                            userID = $user_id";
    
    $user_bids_result = mysqli_query($conn, $user_bids_query);
    
    if ($user_bids_result && mysqli_num_rows($user_bids_result) > 0) {
        // Collect the user's bids
        $user_bids = [];
        while ($row = mysqli_fetch_assoc($user_bids_result)) {
            $user_bids[] = $row['itemID'];
        }
        
        // Create comma-separated list of user bids
        $user_bids_str = implode(',', $user_bids);
        
        // Find users who have bid on the same items
        $other_users_query = "SELECT DISTINCT 
                                b.userID
                                FROM 
                                    Bids b
                                WHERE 
                                    b.itemID IN ($user_bids_str)
                                    AND b.userID != $user_id";  // Exclude the current user
        
        $other_users_result = mysqli_query($conn, $other_users_query);
        
        if ($other_users_result && mysqli_num_rows($other_users_result) > 0) {
            // Collect users who have bid on the same items
            $other_users = [];
            while ($row = mysqli_fetch_assoc($other_users_result)) {
                $other_users[] = $row['userID'];
            }
            
            // Create comma-separated list of user IDs
            $other_users_str = implode(',', $other_users);
            
            // Find other items these users have bid on
            $other_user_bids_query = "SELECT DISTINCT b.itemID
                FROM Bids b
                WHERE b.userID IN ($other_users_str)
                AND b.itemID NOT IN ($user_bids_str)";  // Exclude items the current user has already bid on
            
            $other_user_bids_result = mysqli_query($conn, $other_user_bids_query);
            
            if ($other_user_bids_result && mysqli_num_rows($other_user_bids_result) > 0) {
                // Collect recommended items
                $recommended_items = [];
                while ($row = mysqli_fetch_assoc($other_user_bids_result)) {
                    $recommended_items[] = $row['itemID'];
                }
                
                // Create comma-separated list of recommended items
                $recommended_items_str = implode(',', $recommended_items);
                
                // Get details for recommended items (ensure they are active)
                $recommendations_query = "SELECT 
                        i.itemID,
                        i.auctionTitle,
                        i.image,
                        i.description,
                        i.startingPrice,
                        i.endDate,
                        MAX(b.amount) as highestBid,
                        COUNT(b.amount) AS bidCount
                    FROM Items i
                    LEFT JOIN Bids b ON i.itemID = b.itemID
                    WHERE i.itemID IN ($recommended_items_str)
                    AND i.endDate > NOW()  -- Ensure the auction has not ended
                    GROUP BY 
                        i.itemID, 
                        i.auctionTitle, 
                        i.image, 
                        i.description, 
                        i.startingPrice
                    ORDER BY RAND()
                    LIMIT 5";
                
                $recommendations_result = mysqli_query($conn, $recommendations_query);
                
                echo '<h3>Based on your bids:</h3>';
                if ($recommendations_result && mysqli_num_rows($recommendations_result) > 0) {
                    while ($row = mysqli_fetch_assoc($recommendations_result)) {
                        $title = $row['auctionTitle'];
                        $description = $row['description'];
                        $current_price = $row['highestBid'] ?? $row['startingPrice'];
                        $item_id = $row['itemID'];
                        $image = $row['image'];
                        $num_bids = $row['bidCount'];
                        $end_date = $row['endDate'];
                        
                        print_listing_li($item_id, $title, $image, $description, 
                                       $current_price, $num_bids, new DateTime($end_date), $user_id);
                    }
                    echo '</ul>';
                } else {
                    echo '<div class="alert alert-info">
                            No recommendations available based on your bids.
                          </div>';
                }
            } else {
                echo '<div class="alert alert-info">
                        No recommendations based on other users\' bids.
                      </div>';
            }
        } else {
            echo '<div class="alert alert-info">
                    No recommendations based on your bids.
                  </div>';
        }
    } else {
        echo '<div class="alert alert-info">
                Items will be recommended to your here when you made your first bid!
              </div>';
    }

    // Section 2: Recommendations based on the user's watchlist
    $watchlist_query = "SELECT itemID 
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
        $carType_query = "SELECT DISTINCT carTypeID 
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
            
            // Get recommendations based on watchlist
            $recommendations_query = "SELECT 
                    i.itemID,
                    i.auctionTitle,
                    i.image,
                    i.description,
                    i.startingPrice,
                    i.endDate,
                    MAX(b.amount) as highestBid,
                    COUNT(b.amount) AS bidCount
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
            
            echo '<h3>Based on your watchlist:</h3>';
            if ($recommendations_result && mysqli_num_rows($recommendations_result) > 0) {
                while ($row = mysqli_fetch_assoc($recommendations_result)) {
                    $title = $row['auctionTitle'];
                    $description = $row['description'];
                    $current_price = $row['highestBid'] ?? $row['startingPrice'];
                    $item_id = $row['itemID'];
                    $image = $row['image'];
                    $num_bids = $row['bidCount'];
                    $end_date = $row['endDate'];

                    print_listing_li($item_id, $title, $image, $description,
                                     $current_price, $num_bids, new DateTime($end_date), $user_id);
                }
                echo '</ul>';
            } else {
                echo '<div class="alert alert-info">
                        No recommendations available based on your watchlist.
                      </div>';
            }
        } else {
            echo '<div class="alert alert-info">
                    No recommendations based on your watchlist items.
                  </div>';
        }
    } else {
        echo '<div class="alert alert-info">
                Your watchlist is empty. Add items to your watchlist to get recommendations based on your watchlist!
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