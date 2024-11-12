<?php include_once("header.php")?>
<?php require("utilities.php")?>

<?php
//Start the session 
session_start();
?>

<div class="container">
  <h2 class="my-3">My listings</h2>

  <?php
  // This page is for showing a user the auction listings they've made.
  // It will be pretty similar to browse.php, except there is no search bar.
  // This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.
  // TODO: Check user's credentials (cookie/session).
  // TODO: Perform a query to pull up their auctions.
  // TODO: Loop through results and print them out as list items.
  
  // Check if the user is logged in 
  if (isset($_SESSION['userID'])) {
  //User is logged in, so can display the auction listings they made 
    $userID = $_SESSION['userID'];
    // SQL query to pull the auctions that were listed by the user 
    $sql = "SELECT itemID, auctionTitle, image, description, startingPrice, reservePrice, endDate, status FROM Items WHERE userID = $userID";
    // Execute query 
    $result = $conn->query($sql);
    // Displaying the listings
    //Check if the query worked and if there are any listings done by this user
    if ($result && $result->num_rows > 0) {
      // Style in HTML
      echo ">ul class=list-group>";
      // Loop through the results 
      while ($row = $result->fetch_assoc()) {
        $end_time = new DateTime($row['endDate']);
        $image = $row ['image'];
        $title = $row ['auctionTitle'];
        $description = $row ['description'];
        $starting_price =  $row ['startingPrice'];
        $reserve_price = $row ['reservePrice'];
        $status = $row ['status'];
        $end_date = $row ['endDate'];

        print_listing_li($image, $title, $description, $starting_price, $reserve_price, $end_date, $status);
      }
      echo "</ul>";
    // outcome if there are no listings under this userID
    } else {
      echo "<p> No auction listings found. List your first car for sale now!<p>";
    }
    $result->free();
  } else {
    echo "<h2>Please log in to view your auction listings.<h2>";
  }
  $conn->close();
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
      <a class="page-link" href="mylistings.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
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