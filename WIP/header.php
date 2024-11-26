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
    <a class="navbar-brand" href="index.php">Vintage Cars <!--CHANGEME!--></a>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <?php
          // Displays either login or logout on the right, depending on user's session status.
          if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            echo '<a class="nav-link" href="logout.php">Logout</a>';
          } else {
            echo '<button type="button" class="btn nav-link" data-toggle="modal" data-target="#loginModal">Login</button>';
          }
        ?>
      </li>
    </ul>
  </div>
  
  <!-- Bottom Row: Navigation Links -->
  <div class="d-flex w-100 justify-content-center">
    <ul class="navbar-nav">
      <li class="nav-item mx-1">
        <a class="nav-link" href="browse.php">Browse</a>
      </li>
      <?php
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
          echo('
          <li class="nav-item mx-1">
            <a class="nav-link" href="mybids.php">My Bids</a>
          </li>
          <li class="nav-item mx-1">
            <a class="nav-link" href="recommendations.php">Recommended</a>
          </li>
          <li class="nav-item mx-1">
            <a class="nav-link" href="mylistings.php">My Listings</a>
          </li>
          <li class="nav-item ml-3">
            <a class="nav-link btn border-light" href="create_auction.php">+ Create auction</a>
          </li>');
        }
      ?>
    </ul>
  </div>
</nav>

<!-- Header image (Mustang) -->
<div class="text-center" style="padding-top: 70px;">
  <img src="images/mustang.jpg" alt="Mustang" class="img-fluid" style="width: 100%; height: auto; z-index: -1">
</div>

<!-- Login modal -->
<div class="modal fade" id="loginModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Login</h4>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form method="POST" action="login.php">
          <div class="form-group">
            <label for="email">Email</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Email" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
          </div>
          <button type="submit" class="btn btn-primary form-control">Sign in</button>
        </form>
        <div class="text-center">or <a href="register.php">create an account</a></div>
      </div>

    </div>
  </div>
</div> <!-- End login modal -->

<!-- Bootstrap and jQuery JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>