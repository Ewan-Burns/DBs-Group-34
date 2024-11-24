<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php require_once("database_connect.php");?> 

<?php if (!isset($_GET['order_by'])) {
    // TODO: Define behavior if an order_by value has not been specified.
    $ordering = 'date';
  }
  else {
    $ordering = $_GET['order_by'];
  }
?>
<div class="container-fluid mt-3">
  <h2 class="my-3 text-left">Browse Listings</h2>

  <div id="searchSpecs" class="p-3 bg-light rounded">
    <!-- Search and filter form -->
    <form method="get" action="browse.php">
      <div class="row align-items-center">
        <!-- Keyword search -->
        <div class="col-md-5">
          <div class="form-group mb-0">
            <label for="keyword" class="sr-only">Search keyword:</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text bg-transparent text-muted">
                  <i class="fa fa-search"></i>
                </span>
              </div>
              <input type="text" class="form-control border-left-0" id="keyword" name="keyword" placeholder="Search for anything">
            </div>
          </div>
        </div>

        <!-- Sort filter -->
        <div class="col-md-5">
          <div class="form-group d-flex align-items-center mb-0">
            <label for="order_by" class="mr-3 mb-0">Sorting:</label>
            <select class="form-control flex-grow-1" id="order_by" name="order_by">
              <option value="date" <?php echo ($ordering == 'date') ? 'selected' : ''; ?>>Soonest expiry</option>
              <option value="pricelow" <?php echo ($ordering == 'pricelow') ? 'selected' : ''; ?>>Price (low to high)</option>
              <option value="pricehigh" <?php echo ($ordering == 'pricehigh') ? 'selected' : ''; ?>>Price (high to low)</option>
            </select>
          </div>
        </div>

        <!-- Submit button -->
        <div class="col-md-2 text-right">
          <button type="submit" class="btn btn-primary btn-block">Search</button>
        </div>
      </div>

      <!-- Filters row -->
      <div class="row mt-3">
        <!-- Make filter -->
        <div class="col-md-4">
          <div class="form-group mb-0">
            <select class="form-control" id="make" name="make">
              <option value="">All makes</option>
              <?php
              $makes_query = "SELECT * FROM Make";
              $makes_result = $conn->query($makes_query);
              while ($make_row = $makes_result->fetch_assoc()) {
                $makeID = htmlspecialchars($make_row['makeID']);
                $selected = (isset($_GET['make']) && $_GET['make'] == $makeID) ? 'selected' : '';
                echo "<option value=\"$makeID\" $selected>$makeID</option>";
              }
              ?>
            </select>
          </div>
        </div>

        <!-- Colour filter -->
        <div class="col-md-4">
          <div class="form-group mb-0">
            <select class="form-control" id="colour" name="colour">
              <option value="">All colours</option>
              <?php
              $colours_query = "SELECT * FROM Colour";
              $colours_result = $conn->query($colours_query);
              while ($colour_row = $colours_result->fetch_assoc()) {
                $colourID = htmlspecialchars($colour_row['colourID']);
                $selected = (isset($_GET['colour']) && $_GET['colour'] == $colourID) ? 'selected' : '';
                echo "<option value=\"$colourID\" $selected>$colourID</option>";
              }
              ?>
            </select>
          </div>
        </div>

        <!-- Body Type filter -->
        <div class="col-md-4">
          <div class="form-group mb-0">
            <select class="form-control" id="bodyType" name="bodyType">
              <option value="">All body types</option>
              <?php
              $body_types_query = "SELECT * FROM BodyType";
              $body_types_result = $conn->query($body_types_query);
              while ($body_row = $body_types_result->fetch_assoc()) {
                $bodyID = htmlspecialchars($body_row['bodyID']);
                $selected = (isset($_GET['bodyType']) && $_GET['bodyType'] == $bodyID) ? 'selected' : '';
                echo "<option value=\"$bodyID\" $selected>$bodyID</option>";
              }
              ?>
            </select>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<?php
 // Include the database connection
 require_once 'database_connect.php';
  // Retrieve these from the URL
  if (!isset($_GET['keyword'])) {
    // TODO: Define behavior if a keyword has not been specified. Then it shouldn't filter for anything
    $keyword = "";
  }
  else {
    $keyword = $_GET['keyword'];
  }

  if (!isset($_GET['cat'])) {
    // TODO: Define behavior if a category has not been specified.
    $category = "all";
  }
  else {
    $category = $_GET['cat'];
  }
  
  if (!isset($_GET['page'])) {
    $curr_page = 1;
  }
  else {
    $curr_page = $_GET['page'];
  }
  // Retrieve the user-selected filters
  $makeID = isset($_GET['make']) ? $_GET['make'] : "";
  $colourID = isset($_GET['colour']) ? $_GET['colour'] : "";
  $bodyTypeID = isset($_GET['bodyType']) ? $_GET['bodyType'] : "";
  /* TODO: Use above values to construct a query. Use this query to 
     retrieve data from the database. (If there is no form data entered,
     decide on appropriate default value/default query to make. */
  // Build the WHERE clause based on search criteria
  $where_clause = [];
  if (!empty($keyword)) {
    $keyword = mysqli_real_escape_string($conn, $keyword); // sanitize input to prevent SQL injection
    $where_clause[] = "(Items.auctionTitle LIKE '%$keyword%' OR Items.description LIKE '%$keyword%')";
  } 
  // Join with CarTypes and filter based on make, colour, and bodyType
  if (!empty($makeID)) {
    $where_clause[] = "CarTypes.make = '$makeID'";
  }

  if (!empty($colourID)) {
    $where_clause[] = "CarTypes.colour = '$colourID'";
  }

  if (!empty($bodyTypeID)) {
    $where_clause[] = "CarTypes.bodyType = '$bodyTypeID'";
  }

  // Exclude items created by the user
  if (!empty($user_id)) {
    $user_id = mysqli_real_escape_string($conn, $user_id); // sanitize input
    $where_clause[] = "Items.userID != '$user_id'";
  }

  // Combine the WHERE clauses (if any)
  $where_sql = '';
  if (!empty($where_clause)) {
    $where_sql = 'WHERE ' . implode(' AND ', $where_clause);
  }
  // Build the ORDER BY clause based on the selected option
  $order_sql = '';
  switch ($ordering) {
      case 'pricelow':
          $order_sql = 'ORDER BY COALESCE(MAX(Bids.amount), Items.startingPrice) ASC';  // Ascending order of price (low to high)
          break;
      case 'pricehigh':
          $order_sql = 'ORDER BY COALESCE(MAX(Bids.amount), Items.startingPrice) DESC';  // Descending order of price (high to low)
          break;
      case 'date':
      default:
          $order_sql = 'ORDER BY Items.endDate ASC';  // Default ordering by end date (soonest expiry)
          break;
  }
?>

<div class="container-fluid mt-5">

<!-- TODO: If result set is empty, print an informative message. Otherwise... -->

<ul class="row">

    <!-- TODO: Use a while loop to print a list item for each auction listing
     retrieved from the query -->



<?php

  // Include the database connection
  require_once 'database_connect.php';


  //------------Preparing pagination:--------------
  $user_id = 1;
  // Number of items to display per page
  $items_per_page = 10;

  //Count the total number of items to calculate the number of pages
  $count_query = "SELECT COUNT(*) AS total 
    FROM Items 
    WHERE Items.userID != $user_id";
  $count_result = $conn->query($count_query);
  $total_items = $count_result->fetch_assoc()['total'];

  // Calculate the total number of pages
  $max_page = ceil($total_items / $items_per_page);

  // Get the current page from the URL, default to 1 if not set
  $curr_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

  // Calculate the offset based on the page number
  $offset = ($curr_page - 1) * $items_per_page;

  //--------------Querying the items:-------------
  // Fetch items along with the highest current bid, ordered by endDate, with pagination
  $query = "SELECT 
      Items.itemID,
      Items.auctionTitle,
      Items.image,
      Items.description,
      Items.startingPrice,
      Items.endDate,
      MAX(Bids.amount) AS highestBid,
      COUNT(Bids.amount) AS bidCount
    FROM 
      Items
    LEFT JOIN 
      Bids ON Items.itemID = Bids.itemID
    LEFT JOIN 
      CarTypes ON Items.carTypeID = CarTypes.carTypeID
    $where_sql
    GROUP BY 
      Items.itemID, 
      Items.auctionTitle, 
      Items.image, 
      Items.description, 
      Items.startingPrice, 
      Items.endDate
    $order_sql
    LIMIT $items_per_page OFFSET $offset";

  // Execute the query
  $result = $conn->query($query);

  $user_id = 1; // Hardcoded user ID for now

  // Display query results
  // Check if the query was successful
  if ($result && $result->num_rows > 0) {
        
    // Loop through each item and display it
    while ($row = $result->fetch_assoc()) {
      $title = $row['auctionTitle'];
      $description = $row['description'];
      $current_price = $row['highestBid'] ?? $row['startingPrice'];
      $num_bids = $row['bidCount'];
      $end_date = new DateTime($row['endDate']);
      $item_id = $row['itemID'];
      $image = $row['image'];

      // Use the existing function to display the item
      print_listing_li($item_id, $title, $image, $description, $current_price, $num_bids, $end_date, $user_id);
    }
  } else {
          echo "No items available.";
  }

  // Free result set and close the connection
  $result->free();
  $conn->close();
  //----------------------------------------------

?>

</ul>

<!-- Pagination for results listings -->
<nav aria-label="Search results pages" class="mt-5">
  <ul class="pagination justify-content-center">
  
<?php

  // Copy any currently-set GET variables to the URL.
  $querystring = "";
  foreach ($_GET as $key => $value) {
    if ($key != "page") {
      $querystring .= "$key=$value&amp;";
    }
  }
  
  $high_page_boost = max(3 - $curr_page, 0);
  $low_page_boost = max(2 - ($max_page - $curr_page), 0);
  $low_page = max(1, $curr_page - 2 - $low_page_boost);
  $high_page = min($max_page, $curr_page + 2 + $high_page_boost);
  
  if ($curr_page != 1) {
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
        <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
        <span class="sr-only">Previous</span>
      </a>
    </li>');
  }
    
  for ($i = $low_page; $i <= $high_page; $i++) {
    if ($i == $curr_page) {
      // Highlight the link
      echo('
    <li class="page-item active">');
    }
    else {
      // Non-highlighted link
      echo('
    <li class="page-item">');
    }
    
    // Do this in any case
    echo('
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
    </li>');
  }
  
  if ($curr_page != $max_page) {
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
        <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
        <span class="sr-only">Next</span>
      </a>
    </li>');
  }
?>

  </ul>
</nav>


</div>



<?php include_once("footer.php")?>