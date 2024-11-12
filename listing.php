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
$end_time = new DateTime('2020-11-02T00:00:00');

// Query the database for the item’s details
if (isset($item_id)) {

  // Fetch items along with the highest current bid, ordered by endDate, with pagination
  $query = "
    SELECT 
        Items.auctionTitle, 
        Items.description, 
        Items.startingPrice, 
        Items.endDate, 
        Items.image,
        COALESCE(MAX(Bids.amount), Items.startingPrice) AS highestBid
    FROM 
        Items
    LEFT JOIN 
        Bids ON Items.itemID = Bids.itemID
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
        $end_time = new DateTime($row['endDate']);
        $image = $row['image'];
    }
    
    // Free result and close the statement
    $result->free();
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
$watching = false;
?>



<div class="container">

<div class="row"> <!-- Row #1 with auction title + watch button -->
  <div class="col-sm-8"> <!-- Left col -->
    <h2 class="my-3"><?php echo($title); ?></h2>
  </div>
  <div class="col-sm-4 align-self-center"> <!-- Right col -->
<?php
  /* The following watchlist functionality uses JavaScript, but could
     just as easily use PHP as in other places in the code */
  if ($now < $end_time):
?>
    <div id="watch_nowatch" <?php if ($has_session && $watching) echo('style="display: none"');?> >
      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addToWatchlist()">+ Add to watchlist</button>
    </div>
    <div id="watch_watching" <?php if (!$has_session || !$watching) echo('style="display: none"');?> >
      <button type="button" class="btn btn-success btn-sm" disabled>Watching</button>
      <button type="button" class="btn btn-danger btn-sm" onclick="removeFromWatchlist()">Remove watch</button>
    </div>
<?php endif /* Print nothing otherwise */ ?>
  </div>
</div>

<div class="row"> <!-- Row #2 with auction description + bidding info -->
  <div class="col-sm-8"> <!-- Left col with item info -->

    <div class="itemDescription">
    <?php echo($description); ?>
    </div>

    <!--Show the image-->
    <div class="itemImage">
    <?php echo('<img src="data:image/jpeg;base64,' . base64_encode($image) . '" alt="' . htmlspecialchars($title) . '" class="img-thumbnail" style="max-width: 300px; max-height: 300px;">'); ?>
    </div>

  </div>

  <div class="col-sm-4"> <!-- Right col with bidding info -->

    <p>
<?php if ($now > $end_time): ?>
     This auction ended <?php echo(date_format($end_time, 'j M H:i')) ?>
     <!-- TODO: Print the result of the auction here? -->
<?php else: ?>
     Auction ends <?php echo(date_format($end_time, 'j M H:i') . $time_remaining) ?></p>  
    <p class="lead">Current bid: £<?php echo(number_format($current_price, 2)) ?></p>

    <!-- Bidding form -->
    <!-- Bidding form -->
    <form method="POST" action="place_bid.php">
      <!-- Hidden input for itemID -->
      <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item_id); ?>">

      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">£</span>
        </div>
        <input type="number" class="form-control" id="bid" name="bid" required>
      </div>
      <button type="submit" class="btn btn-primary form-control">Place bid</button>
    </form>
    
<?php endif ?>

  
  </div> <!-- End of right col with bidding info -->

</div> <!-- End of row #2 -->



<?php include_once("footer.php")?>


<script> 
// JavaScript functions: addToWatchlist and removeFromWatchlist.

function addToWatchlist(button) {
  console.log("These print statements are helpful for debugging btw");

  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'add_to_watchlist', arguments: [<?php echo($item_id);?>]},

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
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'remove_from_watchlist', arguments: [<?php echo($item_id);?>]},

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