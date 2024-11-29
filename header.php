<?php
  // FIXME: At the moment, I've allowed these values to be set manually.
  // But eventually, with a database, these should be set automatically
  // ONLY after the user's login credentials have been verified via a 
  // database query.
  session_start();
  require_once 'database_connect.php';
?>


<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap and FontAwesome CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Custom CSS file -->
  <link rel="stylesheet" href="css/custom.css">

  <title>[My Auction Site] <!--CHANGEME!--></title>
</head>


<body>

<!-- Combined Navbar with two rows -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top flex-column">
  
  <!-- Top Row: Brand and Login/Logout -->
  <div class="d-flex w-100 justify-content-between">
    <a class="navbar-brand" href="#">Vintage Cars <!--CHANGEME!--></a>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <?php
          // Displays either login or logout on the right, depending on user's session status.
          if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            echo '<a class="nav-link " href="logout.php">Logout</a>';
          } else {
            echo '<a class="nav-link" href="login.php">Login</a>';
          }
        ?>
      </li>
    </ul>
  </div>
  
  <!-- Bottom Row: Navigation Links -->
  <div class="d-flex w-100 justify-content-center">
    <ul class="navbar-nav">
      <li class="nav-item mx-2">
        <a class="nav-link font-weight-bold" href="browse.php">Browse</a>
      </li>
      <?php
        echo('
        <li class="nav-item mx-2">
          <a class="nav-link font-weight-bold" href="mylistings.php">My Listings</a>
        </li>

        <li class="nav-item mx-2">
          <a class="nav-link font-weight-bold" href="mybids.php">My Bids</a>
        </li>

        <li class="nav-item mx-2">
          <a class="nav-link font-weight-bold" href="recommendations.php">Recommended</a>
        </li>

        <li class="nav-item mx-2">
          <a class="nav-link font-weight-bold" href="watchlist.php">Watchlist</a>
        </li>

        <li class="nav-item ml-5">
          <a class="nav-link btn border-light font-weight-bold" href="create_auction.php">+ Create auction</a>
        </li>
        ');
      ?>
    </ul>
  </div>
</nav>

<!-- Header image (Mustang) -->
<div class="text-center" style="padding-top: 70px;">
  <img src="images/mustang.jpg" alt="Mustang" class="img-fluid" style="width: 100%; height: auto; z-index: -1">
</div>
