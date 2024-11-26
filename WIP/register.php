<?php include_once("header.php")?>

<div class="container">
<h2 class="my-3">Register new account</h2>

<!-- Registration form -->
<form method="POST" action="process_registration.php">
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
          <select class="form-control" id="day" name="day" required>
            <option value="">Day</option>
            <?php for ($i = 1; $i <= 31; $i++) echo "<option value='$i'>$i</option>"; ?>
          </select>
        </div>
        <div class="col">
          <select class="form-control" id="month" name="month" required>
            <option value="">Month</option>
            <?php
            $months = [
              1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
              5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
              9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
            ];
            foreach ($months as $num => $name) echo "<option value='$num'>$name</option>";
            ?>
          </select>
        </div>
        <div class="col">
          <select class="form-control" id="year" name="year" required>
            <option value="">Year</option>
            <?php for ($i = date('Y'); $i >= 1900; $i--) echo "<option value='$i'>$i</option>"; ?>
          </select>
        </div>
      </div>
      <small id="dateOfBirthHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
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
      <input type="text" class="form-control" id="houseNumber" name="houseNumber" placeholder="House Number" required>
      <small id="houseNumberHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
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
    <label for="postcode" class="col-sm-2 col-form-label text-right">Postcode</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="postcode" name="postcode" placeholder="Postcode" required>
      <small id="postcodeHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
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