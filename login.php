<?php
session_start();
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

  <title>Login - Vintage Cars</title>
</head>

<body>
  <?php include_once("header.php"); ?>

  <div class="container my-5">
    <h2>Login</h2>
    <form action="login_result.php" method="post">
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" class="form-control" value="<?php echo isset($_SESSION['login_email']) ? htmlspecialchars($_SESSION['login_email']) : ''; ?>" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" class="form-control" required>
      </div>
      <?php
      if (isset($_SESSION['login_errors']) && !empty($_SESSION['login_errors'])) {
        echo '<div class="alert alert-danger">';
        foreach ($_SESSION['login_errors'] as $error) {
          echo '<p>' . htmlspecialchars($error) . '</p>';
        }
        echo '</div>';
        unset($_SESSION['login_errors']); // Clear errors after displaying
        unset($_SESSION['login_email']); // Clear email after displaying
      }
      ?>
      <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <div class="text-center">or <a href="register.php">create an account</a></div>
  </div>

  <!-- Include Bootstrap JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>