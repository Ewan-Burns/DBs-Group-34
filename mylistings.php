<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container-fluid">
  
<h2 class="my-3">My listings</h2>

<div class="row justify-content-center align-items-center">

  <?php
    // This page is for showing a user the auction listings they've made.
    // It will be pretty similar to browse.php, except there is no search bar.
    // This can be started after browse.php is working with a database.
    // Feel free to extract out useful functions from browse.php and put them in
    // the shared "utilities.php" where they can be shared by multiple files.
    // TODO: Check user's credentials (cookie/session).
    // TODO: Perform a query to pull up their auctions.
    // TODO: Loop through results and print them out as list items.
    
    // Include the database connection file
    require_once 'database_connect.php';

    // Hardcode a userID for testing purposes
    $_SESSION['userID'] = 1; // Replace 1 with the desired user ID

    // Check if the user is logged in 
    if (isset($_SESSION['userID'])) {
    //User is logged in, so can display the auction listings they made 
      $user_id = $_SESSION['userID'];

      //------------Preparing pagination:--------------

      // Number of items per page
      $items_per_page = 12;

      //Count the total number of items to calculate the number of pages
      $count_query = "SELECT 
                        COUNT(*) AS total 
                      FROM 
                        Items
                      WHERE
                        Items.userID = $user_id";
      $count_result = $conn->query($count_query);
      $total_items = $count_result->fetch_assoc()['total'];

      // Calculate the total number of pages
      $max_page = ceil($total_items / $items_per_page);

      // Get the current page from the URL, default to 1 if not set
      $curr_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

      // Calculate the offset based on the page number
      $offset = ($curr_page - 1) * $items_per_page;

      //----------------------------------------------

      // SQL query to pull the auctions that were listed by the user 
      $query = "SELECT 
                  Items.itemID,
                  Items.auctionTitle,
                  Items.image,
                  Items.description,
                  Items.endDate,
                  COALESCE(bids_summary.max_bid, Items.startingPrice) AS max_bid,
                  COALESCE(bids_count.total_bids, 0) AS total_bids
                FROM 
                  Items
                LEFT JOIN 
                  (SELECT itemID, MAX(amount) AS max_bid 
                  FROM Bids 
                  GROUP BY itemID) AS bids_summary 
                ON Items.itemID = bids_summary.itemID
                LEFT JOIN 
                  (SELECT itemID, COUNT(*) AS total_bids 
                  FROM Bids 
                  GROUP BY itemID) AS bids_count 
                ON Items.itemID = bids_count.itemID
                WHERE 
                  Items.userID = $user_id
                ORDER BY 
                  Items.endDate ASC
                LIMIT $items_per_page OFFSET $offset";

      // Execute query 
      $result = $conn->query($query);
      
      // Displaying the listings
      //Check if the query worked and if there are any listings done by this user
      if ($result && $result->num_rows > 0) {
        // Loop through the results 
        while ($row = $result->fetch_assoc()) {
          print_listing_li(
            $row['itemID'],
            $row['auctionTitle'],
            $row['image'],
            $row['description'],
            $row['max_bid'],
            $row['total_bids'],
            new DateTime($row['endDate']),
            $user_id
        );
        }
      // outcome if there are no listings under this userID
      } else {
        echo "<p> No auction listings found. List your first car for sale now!<p>";
      }
      $result->free();
    } else {
      echo "<h2>Please log in to view your auction listings.<h2>";
    }
  ?>

</div>


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
      <a class="page-link" href="mylistings.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
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
      <a class="page-link" href="mylistings.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
    </li>');
  }
  
  if ($curr_page != $max_page) {
    echo('
    <li class="page-item">
      <a class="page-link" href="mylsitings.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
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