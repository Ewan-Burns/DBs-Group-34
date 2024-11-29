<?php include_once("header.php")?>
<?php require("utilities.php")?>

<?php
// Include the database connection
require_once 'database_connect.php';

// Get item_id from the URL
$item_id = $_GET['item_id'];

// Default placeholder values in case the query fails
$title = "Your query failed to retrieve the item's title.";
$description = "Your query failed to retrieve the item's description.";
$current_price = "Your query failed to retrieve the item's current price.";
$num_bids = 0;
$end_time = new DateTime('2020-01-01T00:00:00');
$make = "N/A";
$bodyType = "N/A";
$colour = "N/A";
$year = "N/A";
$mileage = "N/A";
$image_src = '';


// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
  echo "You need to log in to view this page.";
  exit;
}

$user_id = $_SESSION['userID'];

// Check if the user is an admin
$is_admin = false;
$admin_check_query = "SELECT * FROM Admins WHERE userID = ?";
$stmt = $conn->prepare($admin_check_query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  $is_admin = true;
}
$stmt->close();


// Query the database for the item’s details
if (isset($item_id)) {

  // Fetch items along with the highest current bid, ordered by endDate, with pagination
  $query = "SELECT 
                Items.auctionTitle, 
                Items.description, 
                Items.startingPrice, 
                Items.reservePrice,
                Items.endDate, 
                Items.image,
                Items.userID,
                COALESCE(MAX(Bids.amount), Items.startingPrice) AS highestBid,
                CarTypes.make, 
                CarTypes.bodyType, 
                CarTypes.colour, 
                CarTypes.year,
                CarTypes.mileage
            FROM 
                Items
            LEFT JOIN 
                Bids ON Items.itemID = Bids.itemID
            LEFT JOIN
                CarTypes ON Items.carTypeID = CarTypes.carTypeID
            WHERE 
                Items.itemID = $item_id
            GROUP BY 
                Items.itemID, 
                Items.auctionTitle, 
                Items.description, 
                Items.startingPrice, 
                Items.endDate";

  // Execute the query
  $result = $conn->query($query);

    
  // Check if the item was found
  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $title = $row['auctionTitle'];
      $description = $row['description'];
      $current_price = $row['highestBid'] ?? $row['startingPrice'];
      $reservePrice = $row['reservePrice'];
      $end_time = new DateTime($row['endDate']);
      $image = $row['image'];
      $item_user_id = $row['userID'];
      $make = $row['make'];
      $bodyType = $row['bodyType'];
      $colour = $row['colour'];
      $year = $row['year'];
      $mileage = $row['mileage'];
  }
  
  // Free result and close the statement
  $result->free();



  // Check if user is already watching the item
  $watching_query = "SELECT * FROM Watchlist WHERE userID = ? AND itemID = ?";
  $stmt = $conn->prepare($watching_query);
  $stmt->bind_param("ii", $user_id, $item_id);
  $stmt->execute();
  $stmt->store_result();
  // If the query returns a row, it means the user is watching the item
  $watching = $stmt->num_rows > 0;



  // Get the highest bid placed by the user on this item
  $bid_query = "SELECT MAX(amount) AS highest_bid FROM Bids WHERE userID = ? AND itemID = ?";
  $stmt_bid = $conn->prepare($bid_query);
  $stmt_bid->bind_param("ii", $user_id, $item_id);
  $stmt_bid->execute();
  $stmt_bid->bind_result($bid_placed);
  $stmt_bid->fetch(); // Fetch the maximum bid amount
  $stmt_bid->free_result();



  // Get the userID of the user with the highest bid on this item
  $highest_bid_query = "SELECT userID FROM Bids WHERE itemID = ? ORDER BY amount DESC LIMIT 1";
  $stmt_highest_bid = $conn->prepare($highest_bid_query);
  $stmt_highest_bid->bind_param("i", $item_id);
  $stmt_highest_bid->execute();
  $stmt_highest_bid->bind_result($highest_bid_user_id);
  $stmt_highest_bid->fetch(); // Fetch the userID of the highest bid
  $stmt_highest_bid->free_result();



  // Check if there already exists a rating for this item
  $rating_query = "SELECT rating FROM Ratings WHERE itemID = ?";
  $stmt_rating = $conn->prepare($rating_query);
  $stmt_rating->bind_param("i", $item_id);
  $stmt_rating->execute();
  $stmt_rating->bind_result($rating_exists); 
  $stmt_rating->fetch();
  $stmt_rating->free_result();


  $stmt_bid->close();
  $stmt->close();



  // Showcase seller's average rating
  $query = "SELECT COALESCE(AVG(rating), 'N/A') AS average_rating FROM Ratings 
  WHERE userID = ?";
  $stmt_rat = $conn->prepare($query);
  $stmt_rat->bind_param("i", $item_user_id);  // The seller's itemID (userID who created the auction)
  $stmt_rat->execute();
  $result = $stmt_rat->get_result();
  $row = $result->fetch_assoc();

  $average_rating = is_numeric($row['average_rating']) ? number_format($row['average_rating'], 2) . '/5' : 'N/A';
      
  $conn->close();
}

// Calculate time to auction end
$now = new DateTime();
if ($now < $end_time) {
    $time_to_end = date_diff($now, $end_time);
    $time_remaining = ' (in ' . display_time_remaining($time_to_end) . ')';
} else {
    $time_remaining = "Auction ended";
}


// TODO: If the user has a session, use it to make a query to the database
//       to determine if the user is already watching this item.
//       For now, this is hardcoded.
$has_session = true;
?>



<!-- HTML content for the listing page -->

<div class="container-fluid">

<div class="row"> <!-- Row #1 with auction title + watch button -->
  <div class="col-sm-8"> <!-- Left col -->
    <h2 class="my-3"><?php echo($title); ?></h2>
  </div>
  <div class="col-sm-4 align-self-center"> <!-- Right col -->
<?php
  if ($now < $end_time):
?>
  <?php if ($item_user_id != $user_id && empty($bid_placed)): ?>
    <div id="watch_nowatch" <?php if ($has_session && $watching) echo('style="display: none"');?> >
      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addToWatchlist(this)" data-item-id="<?php echo $item_id; ?>" 
      data-user-id="<?php echo $user_id; ?>">+ Add to watchlist</button>
    </div>
    <div id="watch_watching" <?php if (!$has_session || !$watching) echo('style="display: none"');?> >
      <button type="button" class="btn btn-success btn-sm" disabled>Watching</button>
      <button type="button" class="btn btn-danger btn-sm" onclick="removeFromWatchlist(this)" data-item-id="<?php echo $item_id; ?>" 
      data-user-id="<?php echo $user_id; ?>">Remove watch</button>
    </div>
  <?php elseif ($item_user_id == $user_id || $is_admin): ?>
    <?php if ($current_price > $reservePrice && !$is_admin): ?>
      <p class="lead" style="font-size: 15px; color: darkred;">The reserve price has been met,<br> it is no longer possible to delete the item.</p>
    <?php else: ?>
      <!-- Delete Button that triggers a modal for confirmation-->
      <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmDeleteModal">
        Delete item
      </button>

      <!-- Delete Confirmation Modal -->
      <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              Are you sure you want to delete this item?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              <!-- Delete Form inside the Modal -->
              <form method="POST" action="delete_item.php" class="d-inline">
                <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item_id); ?>">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                <button type="submit" class="btn btn-danger">Delete item</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    <?php endif ?>
  <?php elseif ($bid_placed < $current_price): ?>
    <div class="my-3">
      Your last bid: £<?php echo(number_format($bid_placed, 2)) ?></p>
    </div>
  <?php endif ?>
<?php endif /* Print nothing otherwise */ ?>


<?php if ($now > $end_time && $highest_bid_user_id == $user_id && empty($rating_exists)): ?>
    <!-- User won the auction -->
    <!-- "Rate Seller" Button (show only if auction ended and user won) -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#rateSellerModal">
        Rate Seller
    </button>

    <!-- Modal Structure -->
     
    <div class="modal fade" id="rateSellerModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="rateSellerModalLabel">Rate the Seller</h5>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form method="POST" action="ratings.php">
                        <input type="hidden" id= "item_id" name="item_id" value="<?php echo $item_id; ?>">
                        <div class="form-group">
                            <label for="rating" class="form-label">Rate the Seller:</label>
                            <select name="rating" id="rating" class="form-select" required>
                                <option value="" disabled selected>Select a rating</option>
                                <option value="1">1 - Poor</option>
                                <option value="2">2 - Fair</option>
                                <option value="3">3 - Good</option>
                                <option value="4">4 - Very Good</option>
                                <option value="5">5 - Excellent</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Rating</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
  </div>
</div>


<div class="row"> <!-- Row #2 with auction description + bidding info -->
  <div class="col-sm-4"> <!-- Left col with item info -->

    <div class="itemDescription">
    <?php echo($description); ?>
    </div>

    <!--Show the image-->
    <div class="itemImage">
    <?php echo('<img src="data:image/jpeg;base64,' . base64_encode($image) . '" alt="' . htmlspecialchars($title) . '" class="img-thumbnail" style="max-width: 300px; max-height: 300px;">'); ?>
    </div>
  </div>

 
  <div class="col-sm-4">
        <p><strong>Make:</strong> <?php echo($make); ?></p>
        <p><strong>Colour:</strong> <?php echo($colour); ?></p>
        <p><strong>Body Type:</strong> <?php echo($bodyType); ?></p>
        <p><strong>Year:</strong> <?php echo($year); ?></p>
        <p><strong>Mileage:</strong> <?php echo($mileage); ?> miles</p>
  </div>


  <div class="col-sm-4"> <!-- Right col with bidding info -->

    <?php if ($now > $end_time): ?>
        This auction ended <?php echo(date_format($end_time, 'j M H:i')) ?>
        <!-- TODO: Print the result of the auction here? -->
    <?php else: ?>
        Auction ends <?php echo(date_format($end_time, 'j M H:i') . $time_remaining) ?></p>  
        <p class="lead">Current bid: £<?php echo(number_format($current_price, 2)) ?></p>

        <?php if ($item_user_id != $user_id): ?>
          <!-- Bidding form -->
          <!-- Bidding form -->
          <form method="POST" action="place_bid.php">
            <!-- Hidden input for itemID -->
            <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item_id); ?>">

            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">£</span>
              </div>
              <input 
                type="number" 
                class="form-control" 
                id="bid" 
                name="bid" 
                min="<?php echo htmlspecialchars($current_price); ?>" 
                required 
                oninput="this.setCustomValidity('')" 
                oninvalid="setCustomMessage(this)">
            </div>
            <button type="submit" class="btn btn-primary form-control">Place bid</button>
          </form>
        <?php endif; ?>
        <div class="my-3">
          The seller's rating is: <?php echo $average_rating; ?> </p>
        <div>
    <?php endif ?>
  
  </div> <!-- End of right col with bidding info -->

</div> <!-- End of row #2 -->

</div> <!-- End of container-fluid -->



<?php include_once("footer.php")?>



<script> 
// JavaScript functions: addToWatchlist and removeFromWatchlist.

function addToWatchlist(button) {
  console.log("These print statements are helpful for debugging btw");
  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.

  // Extract item_id and user_id from the button's data attributes
  var item_id = $(button).data('item-id');
  var user_id = $(button).data('user-id');

  console.log("Item ID: " + item_id);
  console.log("User ID: " + user_id);

  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {
          functionname: 'add_to_watchlist', 
          item: item_id,
          user: user_id
    },

    success: 
      function (obj, textstatus) {
        // Callback function for when call is successful and returns obj
        console.log("Success");
        var objT = obj.trim();
 
        if (objT == "success") {
          $("#watch_nowatch").hide();
          $("#watch_watching").show();
        }
        else {
          var mydiv = document.getElementById("watch_nowatch");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Add to watch failed. Try again later."));
        }
      },

    error:
      function (obj, textstatus) {
        console.log("Error");
      }
  }); // End of AJAX call

} // End of addToWatchlist func



function removeFromWatchlist(button) {
  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.

  // Extract item_id and user_id from the button's data attributes
  var item_id = $(button).data('item-id');
  var user_id = $(button).data('user-id');

  console.log("Item ID: " + item_id);
  console.log("User ID: " + user_id);


  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {
      functionname: 'remove_from_watchlist', 
      item: item_id, 
      user: user_id
    },

    success: 
      function (obj, textstatus) {
        // Callback function for when call is successful and returns obj
        console.log("Success");
        var objT = obj.trim();
 
        if (objT == "success") {
          $("#watch_watching").hide();
          $("#watch_nowatch").show();
        }
        else {
          var mydiv = document.getElementById("watch_watching");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Watch removal failed. Try again later."));
        }
      },

    error:
      function (obj, textstatus) {
        console.log("Error");
      }
  }); // End of AJAX call

} // End of addToWatchlist func
</script>