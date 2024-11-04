<?php include_once("header.php")?>

<div class="container">

<!-- Create auction form -->
<div style="max-width: 800px; margin: 10px auto">
  <h2 class="my-3">Create new auction</h2>
  <div class="card">
    <div class="card-body">
      <form method="post" action="create_auction_result.php">
        <div class="form-group row">
          <label for="auctionTitle" class="col-sm-2 col-form-label text-right">Title of auction</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="auctionTitle" name="auctionTitle" placeholder="e.g. Black Mercedes SLS">
            <small id="titleHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> A short description of your car, which will display in listings.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionDescription" class="col-sm-2 col-form-label text-right">Description</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="auctionDescription" name="auctionDescription" rows="4"></textarea>
            <small id="descriptionHelp" class="form-text text-muted">Full details of the listing to help bidders decide if it's what they're looking for.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionMake" class="col-sm-2 col-form-label text-right">Make</label>
          <div class="col-sm-10">
            <select class="form-control" id="auctionMake" name="auctionMake">
              <option selected>Choose...</option>
              <option value="mercedes">Mercedes</option>
              <option value="bmw">BMW</option>
              <option value="audi">Audi</option>
            </select>
            <small id="makeHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Select a make for your car.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionBody" class="col-sm-2 col-form-label text-right">Body type</label>
          <div class="col-sm-10">
            <select class="form-control" id="auctionBody" name="auctionBody">
              <option selected>Choose...</option>
              <option value="coupe">Coupe</option>
              <option value="convertible">Convertible</option>
              <option value="sports">Sportscar</option>
              <option value="supercar">Supercar</option>
            </select>
            <small id="makeHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Select a body type for your car.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionYear" class="col-sm-2 col-form-label text-right">Year</label>
          <div class="col-sm-10">
            <input type="int" class="form-control" id="auctionYear" name="auctionYear" placeholder="e.g. 1967">
            <small id="yearHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> The production year of the car you're selling, which will display in listings.</small>
          </div>
        </div>
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
        <div class="form-group row">
          <label for="auctionEndDate" class="col-sm-2 col-form-label text-right">End date</label>
          <div class="col-sm-10">
            <input type="datetime-local" class="form-control" id="auctionEndDate" name="auctionEndDate">
            <small id="endDateHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Day for the auction to end.</small>
          </div>
        </div>
        <button type="submit" class="btn btn-primary form-control">Create Auction</button>
      </form>
    </div>
  </div>
</div>

</div>

<?php include_once("footer.php")?>
