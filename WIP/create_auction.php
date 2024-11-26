<?php include_once("header.php")?>

<div class="container">

<?php
// Include the database connection file
require_once 'database_connect.php';

// Fetch the makes from the database
$sqlMakes = "SELECT makeID FROM Make"; 
$resultMakes = $conn->query($sqlMakes);

// Fetch the body types from the database
$sqlBodyTypes = "SELECT bodyID FROM BodyType"; 
$resultBodyTypes = $conn->query($sqlBodyTypes);

// Fetch the colour from the database
$sqlColours = "SELECT colourID FROM Colour"; 
$resultColours = $conn->query($sqlColours);
?>

<!-- Create auction form -->
<div style="max-width: 800px; margin: 10px auto">
  <h2 class="my-3">Create new auction</h2>
  <div class="card">
    <div class="card-body">
      <form method="post" action="create_auction_result.php" enctype="multipart/form-data">
        <!-- Title field -->
        <div class="form-group row">
          <label for="auctionTitle" class="col-sm-2 col-form-label text-right">Auction Title</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="auctionTitle" name="auctionTitle" placeholder="e.g. Black Mercedes SLS">
            <small id="titleHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> A short description of your car, which will display in listings.</small>
          </div>
        </div>

        <!-- Description field -->
        <div class="form-group row">
          <label for="auctionDescription" class="col-sm-2 col-form-label text-right">Description</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="auctionDescription" name="auctionDescription" rows="4"></textarea>
            <small id="descriptionHelp" class="form-text text-muted">Full details of the listing to help bidders decide if it's what they're looking for.</small>
          </div>
        </div>

        <!-- Image upload field -->
        <div class="form-group row">
          <label for="auctionImage" class="col-sm-2 col-form-label text-right">Upload Image</label>
          <div class="col-sm-10">
            <input type="file" class="form-control" id="auctionImage" name="auctionImage" accept="image/*" onchange="toggleRemoveButton()">
            <small id="imageHelp" class="text-danger"> *Required.<span> Upload an image of the car.</small>
                
            <!-- Remove button (appears only when an image is uploaded) -->
            <div id="removeButtonContainer" style="display: none; margin-top: 10px;">
              <button type="button" id="removeImageButton" class="btn btn-danger btn-sm" onclick="removeImage()">Remove Image</button>
            </div>
          </div>
        </div>

        <script>
        // Function to toggle the display of the remove button based on file input
        function toggleRemoveButton() {
          const fileInput = document.getElementById('auctionImage');
          const removeButtonContainer = document.getElementById('removeButtonContainer');
            
          // Check if a file is selected
          if (fileInput.files && fileInput.files.length > 0) {
            removeButtonContainer.style.display = 'block'; // Show the remove button
          } else {
            removeButtonContainer.style.display = 'none'; // Hide the remove button
          }
        }

          // Function to remove the selected image
          function removeImage() {
            const fileInput = document.getElementById('auctionImage');
            const removeButtonContainer = document.getElementById('removeButtonContainer');
            
            // Clear the file input value
            fileInput.value = '';
            removeButtonContainer.style.display = 'none';
          }
        </script>

        <!-- Make field -->
        <div class="form-group row">
          <label for="auctionMake" class="col-sm-2 col-form-label text-right">Make</label>
          <div class="col-sm-10">
            <select class="form-control" id="auctionMake" name="auctionMake">
              <option selected>Choose...</option>
              <?php
              // Check if the query for makes returned results
              if ($resultMakes->num_rows > 0) {
                while ($row = $resultMakes->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($row['makeID']) . '">' . htmlspecialchars($row['makeID']) . '</option>';
                }
              } else {
                echo '<option disabled>No makes available</option>';
              }
              ?>
            </select>
            <small id="makeHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Select a make for your car.</small>
          </div>
        </div>
        
        <!-- Body type field -->
        <div class="form-group row">
          <label for="auctionBodyType" class="col-sm-2 col-form-label text-right">Body Type</label>
          <div class="col-sm-10">
            <select class="form-control" id="auctionBodyType" name="auctionBodyType">
              <option selected>Choose...</option>
              <?php
              // Check if the query for body types returned results
              if ($resultBodyTypes->num_rows > 0) {
                while ($row = $resultBodyTypes->fetch_assoc()) {
                  echo '<option value="' . htmlspecialchars($row['bodyID']) . '">' . htmlspecialchars($row['bodyID']) . '</option>';
                }
              } else {
                echo '<option disabled>No body types available</option>';
              }
              ?>
            </select>
            <small id="bodyHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Select a body type for your car.</small>
          </div>
        </div>

        <!-- Year field -->
        <div class="form-group row">
          <label for="auctionYear" class="col-sm-2 col-form-label text-right">Year</label>
          <div class="col-sm-10">
            <input type="int" class="form-control" id="auctionYear" name="auctionYear" placeholder="e.g. 1967">
            <small id="yearHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> The production year of the car you're selling, which will display in listings.</small>
          </div>
        </div>

        <!-- Colour field -->
        <div class="form-group row">
          <label for="auctionColour" class="col-sm-2 col-form-label text-right">Colour</label>
          <div class="col-sm-10">
            <select class="form-control" id="auctionColour" name="auctionColour">
              <option selected>Choose...</option>
              <?php
              // Check if the query for makes returned results
              if ($resultMakes->num_rows > 0) {
                while ($row = $resultColours->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($row['colourID']) . '">' . htmlspecialchars($row['colourID']) . '</option>';
                }
              } else {
                echo '<option disabled>No makes available</option>';
              }
              ?>
            </select>
            <small id="colourHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Select a colour for your car.</small>
          </div>
        </div>

        <!-- Mileage field -->
        <div class="form-group row">
          <label for="auctionMileage" class="col-sm-2 col-form-label text-right">Mileage</label>
          <div class="col-sm-10">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">KM</span>
              </div>
              <input type="number" class="form-control" id="auctionMileage" name="auctionMileage">
            </div>
            <small id="mileageHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Mileage on the car</small>
          </div>
        </div>

        <!-- Starting price field -->
        <div class="form-group row">
          <label for="auctionStartPrice" class="col-sm-2 col-form-label text-right">Starting price</label>
          <div class="col-sm-10">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">£</span>
              </div>
              <input type="number" class="form-control" id="auctionStartPrice" name="auctionStartPrice">
            </div>
            <small id="startBidHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Initial bid amount.</small>
          </div>
        </div>

        <!-- Reserve price field -->
        <div class="form-group row">
          <label for="auctionReservePrice" class="col-sm-2 col-form-label text-right">Reserve price</label>
          <div class="col-sm-10">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">£</span>
              </div>
              <input type="number" class="form-control" id="auctionReservePrice" name="auctionReservePrice">
            </div>
            <small id="reservePriceHelp" class="form-text text-muted">Optional. Auctions that end below this price will not go through. This value is not displayed in the auction listing.</small>
          </div>
        </div>

        <!-- End date field -->
        <div class="form-group row">
          <label for="auctionEndDate" class="col-sm-2 col-form-label text-right">End date</label>
          <div class="col-sm-10">
            <input type="datetime-local" class="form-control" id="auctionEndDate" name="auctionEndDate">
            <small id="endDateHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Day and time for the auction to end.</small>
          </div>
        </div>
        
        <button type="submit" class="btn btn-primary form-control">Create Auction</button>
      </form>
    </div>
  </div>
</div>

</div>

<?php include_once("footer.php")?>

<?php
// Close the database connection
$conn->close();
?>