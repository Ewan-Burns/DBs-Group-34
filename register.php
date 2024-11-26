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

  <title>Register - Vintage Cars</title>
</head>

<body>
  <?php include_once("header.php"); ?>

  <div class="container my-5">
    <h2>Register</h2>
    <?php
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['message']) . '</div>';
        unset($_SESSION['message']); // Clear the message after displaying
    }
    if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
        echo '<div class="alert alert-danger"><ul>';
        foreach ($_SESSION['errors'] as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul></div>';
        unset($_SESSION['errors']); // Clear errors after displaying
    }
    ?>
    <form action="process_registration.php" method="post">
      <div class="form-group row">
        <label for="firstName" class="col-sm-2 col-form-label text-right">First Name</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" required>
          <small id="firstNameHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
      </div>
      <div class="form-group row">
        <label for="lastName" class="col-sm-2 col-form-label text-right">Last Name</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name" required>
          <small id="lastNameHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
      </div>
      <div class="form-group row">
        <label for="dateOfBirth" class="col-sm-2 col-form-label text-right">Date of Birth</label>
        <div class="col-sm-10">
          <div class="form-row">
            <div class="col">
              <input type="number" class="form-control" id="day" name="day" placeholder="Day" required>
            </div>
            <div class="col">
              <select class="form-control" id="month" name="month" required>
                <option value="">Month</option>
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
              </select>
            </div>
            <div class="col">
              <input type="number" class="form-control" id="year" name="year" placeholder="Year" required>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label for="country" class="col-sm-2 col-form-label text-right">Country</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="country" name="country" placeholder="Country" required>
          <small id="countryHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
      </div>
      <div class="form-group row">
        <label for="street" class="col-sm-2 col-form-label text-right">Street</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="street" name="street" placeholder="Street" required>
          <small id="streetHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
      </div>
      <div class="form-group row">
        <label for="houseNumber" class="col-sm-2 col-form-label text-right">House Number</label>
        <div class="col-sm-10">
          <input type="number" class="form-control" id="houseNumber" name="houseNumber" placeholder="House Number" required>
          <small id="houseNumberHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
      </div>
      <div class="form-group row">
        <label for="postcode" class="col-sm-2 col-form-label text-right">Postcode</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="postcode" name="postcode" placeholder="Postcode" required>
          <small id="postcodeHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
      </div>
      <div class="form-group row">
        <label for="city" class="col-sm-2 col-form-label text-right">City</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="city" name="city" placeholder="City" required>
          <small id="cityHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
      </div>
      <div class="form-group row">
        <label for="email" class="col-sm-2 col-form-label text-right">Email</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="email" name="email" placeholder="Email" required>
          <small id="emailHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
      </div>
      <div class="form-group row">
        <label for="password" class="col-sm-2 col-form-label text-right">Password</label>
        <div class="col-sm-10">
          <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
          <small id="passwordHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
      </div>
      <div class="form-group row">
        <label for="passwordConfirmation" class="col-sm-2 col-form-label text-right">Repeat password</label>
        <div class="col-sm-10">
          <input type="password" class="form-control" id="passwordConfirmation" name="passwordConfirmation" placeholder="Enter password again" required>
          <small id="passwordConfirmationHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
      </div>
      <div class="form-group row">
        <button type="submit" class="btn btn-primary form-control">Register</button>
      </div>
    </form>
  </div>

  <?php include_once("footer.php")?>

  <!-- Include Bootstrap JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>