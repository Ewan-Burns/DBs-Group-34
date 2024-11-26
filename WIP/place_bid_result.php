<?php 
include_once("header.php"); // Include your header (navbar, etc.)
require_once 'database_connect.php'; // Make sure to include your database connection

// Get the item_id from the GET request (or post if you prefer)
$item_id = $_GET['item_id'] ?? null; // Get the item_id from URL parameter

// If the item_id is not set, display an error message
if (!$item_id) {
    echo "<div class='alert alert-danger'>Item not found.</div>";
    exit;
}

// Fetch item details and highest bid
$query = "
    SELECT 
      Items.itemID,
      Items.auctionTitle,
      Items.image,
      Items.description,
      Items.startingPrice,
      Items.endDate,
      MAX(Bids.amount) AS highestBid
    FROM 
      Items
    LEFT JOIN 
      Bids ON Items.itemID = Bids.itemID
    WHERE 
      Items.itemID = ?
    GROUP BY 
      Items.itemID, Items.auctionTitle, Items.image, Items.description, Items.startingPrice, Items.endDate
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $item_id); // Bind item_id to query
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $item = $result->fetch_assoc();
    $auctionTitle = $item['auctionTitle'];
    $image = $item['image'];
    $description = $item['description'];
    $highestBid = $item['highestBid'] ?? $item['startingPrice'];
    $endDate = $item['endDate'];
} else {
    echo "<div class='alert alert-danger'>No such item found.</div>";
    exit;
}

?>

<div class="container">
    <h2 class="my-4">Bid Confirmation</h2>

    <!-- Success message -->
    <div class="alert alert-success">
        <h4 class="alert-heading">Your bid has been placed successfully!</h4>
        <p>You have successfully placed a bid on the following item:</p>
    </div>

    <!-- Display item details -->
    <div class="row">
        <div class="col-md-4">
            <?php if ($image): ?>
                <img src="data:image/jpeg;base64,<?= base64_encode($image); ?>" alt="<?= htmlspecialchars($auctionTitle); ?>" class="img-fluid img-thumbnail">
            <?php else: ?>
                <p><strong>No image available</strong></p>
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <h3><?= htmlspecialchars($auctionTitle); ?></h3>
            <p><strong>Description:</strong> <?= htmlspecialchars($description); ?></p>
            <p><strong>Starting Price:</strong> £<?= number_format($item['startingPrice'], 2); ?></p>
            <p><strong>Current Highest Bid:</strong> £<?= number_format($highestBid, 2); ?></p>
            <p><strong>Auction End Date:</strong> <?= date('F j, Y, g:i a', strtotime($endDate)); ?></p>
        </div>
    </div>

    <!-- Link back to the listing -->
    <div class="mt-4">
        <a href="browse.php?item_id=<?= $item_id; ?>" class="btn btn-primary">Back to Browse</a>
    </div>

</div>

<?php include_once("footer.php"); // Include your footer ?>
