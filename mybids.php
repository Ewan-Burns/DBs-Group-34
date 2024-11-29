<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container-fluid">

<h2 class="my-3">My bids</h2>

<div class="row justify-content-center align-items-center">

  <?php
    // This page is for showing a user the auctions they've bid on.
    // It is very similar to browse.php, except there is no search bar.
    
    require_once 'database_connect.php';

    //------------Preparing pagination:--------------

    // Number of items per page
    $items_per_page = 12;

    // Get the current page from the URL, default to 1 if not set
    $curr_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    // Calculate the offset based on the page number
    $offset = ($curr_page - 1) * $items_per_page;

    $total_items = 0;

    //----------------------------------------------
    

    // TODO: Check user's credentials (cookie/session).

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
                    MAX(Bids.amount) AS max_bid,  -- Get the highest bid overall
                    COUNT(Bids.amount) AS total_bids,
                    COUNT(*) OVER() AS total_count  -- Calculate the total number of items
                FROM 
                    Items
                LEFT JOIN 
                    Bids ON Items.itemID = Bids.itemID
                WHERE 
                    Bids.userID = $user_id  -- Only show items the user has bid on
                GROUP BY 
                    Items.itemID, Items.auctionTitle, Items.image, Items.description, Items.endDate
                ORDER BY 
                    Items.endDate ASC
                LIMIT $items_per_page OFFSET $offset";

      // Execute the query
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
                  $row['total_bids'],
                  new DateTime($row['endDate']),
                  $user_id
              );
              $total_items = $row['total_count'];
          }
      } else {
          echo "<p>You have not bid on any items yet.<p>";
      }
      $result->free();
    } else {
      echo "<h2>Please log in to view your auction bids.<h2>";
    }

    // Calculate the total number of pages
    $max_page = ceil($total_items / $items_per_page);

    $conn->close();
  ?>

</div>



<!-- Pagination for results bids -->
<nav aria-label="Search results pages" class="mt-5">
  <ul class="pagination justify-content-center">
  
<?php

  // ------------Perform the pagination:---------------

  // Copy any currently-set GET variables to the URL.
  $querystring = "";
  foreach ($_GET as $key => $value) {
    if ($key != "page") {
      $querystring .= "$key=$value&amp;";
    }
  }
  
  // Calculate range for pagination links
  $high_page_boost = max(3 - $curr_page, 0);
  $low_page_boost = max(2 - ($max_page - $curr_page), 0);
  $low_page = max(1, $curr_page - 2 - $low_page_boost);
  $high_page = min($max_page, $curr_page + 2 + $high_page_boost);

  // Previous page link
  if ($curr_page != 1) {
    echo('
    <li class="page-item">
      <a class="page-link" href="mybids.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
        <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
        <span class="sr-only">Previous</span>
      </a>
    </li>');
  }

  // Numbered page links
  for ($i = $low_page; $i <= $high_page; $i++) {
    if ($i == $curr_page) {
      echo('<li class="page-item active">');
    } else {
      echo('<li class="page-item">');
    }
    echo('<a class="page-link" href="mybids.php?' . $querystring . 'page=' . $i . '">' . $i . '</a></li>');
  }

  // Next page link
  if ($curr_page != $max_page) {
    echo('
    <li class="page-item">
      <a class="page-link" href="mybids.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
        <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
        <span class="sr-only">Next</span>
      </a>
    </li>');
  }
  //-----------------------------------------
?>


  </ul>
</nav>

</div>

<?php include_once("footer.php")?>