<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container-fluid">

<h2 class="my-3">My watchlist</h2>

<div class="row justify-content-center align-items-center">

    <?php
    // This page is for showing a user the auctions they've bid on.
    // It will be pretty similar to browse.php, except there is no search bar.
    // This can be started after browse.php is working with a database.
    // Feel free to extract out useful functions from browse.php and put them in
    // the shared "utilities.php" where they can be shared by multiple files.
    
    require_once 'database_connect.php';
    
    // TODO: Check user's credentials (cookie/session).

    //session_start(); // Start the session


    if (isset($_SESSION['userID'])) {
        // User recognised.
        // Display content, create/update sessionvariables.
        $user_id = $_SESSION['userID'];

        //------------Preparing pagination:--------------

        // Number of items per page
        $items_per_page = 12;

        //Count the total number of items to calculate the number of pages
        // Count the total number of items to calculate the number of pages for the watchlist
        $count_query = "SELECT 
                            COUNT(DISTINCT Items.itemID) AS total
                        FROM 
                            Items
                        JOIN 
                            Watchlist 
                        ON 
                            Items.itemID = Watchlist.itemID
                        WHERE 
                            Watchlist.userID = $user_id";

        $count_result = $conn->query($count_query);
        $total_items = $count_result->fetch_assoc()['total'];

        // Calculate the total number of pages
        $max_page = ceil($total_items / $items_per_page);

        // Get the current page from the URL, default to 1 if not set
        $curr_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Calculate the offset based on the page number
        $offset = ($curr_page - 1) * $items_per_page;

        //----------------------------------------------
        // TODO: Perform a query to pull up the auctions they've bidded on.

        // Fetch items from the database
        // Fetch items from the database for the watchlist page
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
                JOIN 
                    Watchlist 
                ON 
                    Items.itemID = Watchlist.itemID
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
                    Watchlist.userID = $user_id
                    AND NOT EXISTS (
                        SELECT 1 
                        FROM Bids 
                        WHERE Bids.itemID = Items.itemID 
                        AND Bids.userID = $user_id
                    )
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
            }
        } else {
            echo "You have not put any items on the watchlist yet.";
        }
        $result->free();
    } else {
        echo "<h2>Please log in to view your watchtlist.<h2>";
    }
    ?>

</div>

<!-- Pagination for results bids -->
<nav aria-label="Search results pages" class="mt-5">
  <ul class="pagination justify-content-center">
  
<?php
  // Get the current page from the URL, default to 1 if not set
  $curr_page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

  // Copy any currently-set GET variables to the URL.
  $querystring = "";
  foreach ($_GET as $key => $value) {
    if ($key != "page") {
      $querystring .= "$key=$value&amp;";
    }
  }

  
  // Calculate range for pagination links
  $low_page = max(1, $curr_page - 2);
  $high_page = min($max_page, $curr_page + 2);

  // Previous page link
  if ($curr_page > 1) {
    echo('
    <li class="page-item">
      <a class="page-link" href="watchlist.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
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
    echo('<a class="page-link" href="watchlist.php?' . $querystring . 'page=' . $i . '">' . $i . '</a></li>');
  }

  // Next page link
  if ($curr_page < $max_page) {
    echo('
    <li class="page-item">
      <a class="page-link" href="watchlist.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
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