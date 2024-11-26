<?php

// display_time_remaining:
// Helper function to help figure out what time to display
function display_time_remaining($interval) {

    if ($interval->days == 0 && $interval->h == 0) {
      // Less than one hour remaining: print mins + seconds:
      $time_remaining = $interval->format('%im %Ss');
    }
    else if ($interval->days == 0) {
      // Less than one day remaining: print hrs + mins:
      $time_remaining = $interval->format('%hh %im');
    }
    else {
      // At least one day remaining: print days + hrs:
      $time_remaining = $interval->format('%ad %hh');
    }

  return $time_remaining;

}

// print_listing_li:
// This function prints an HTML <li> element containing an auction listing
function print_listing_li($item_id, $title, $image, $desc, $price, $num_bids, $end_time)
{
  // Truncate long descriptions
  if (strlen($desc) > 250) {
    $desc_shortened = substr($desc, 0, 250) . '...';
  }
  else {
    $desc_shortened = $desc;
  }
  
  // Fix language of bid vs. bids
  if ($num_bids == 1) {
    $bid = ' bid';
  }
  else {
    $bid = ' bids';
  }
  
  // Calculate time to auction end
  $now = new DateTime();
  if ($now > $end_time) {
    $time_remaining = 'This auction has ended';
  }
  else {
    // Get interval:
    $time_to_end = date_diff($now, $end_time);
    $time_remaining = display_time_remaining($time_to_end) . ' remaining';
  }
  
  // Encode the image data to Base64 if it’s not already a URL
  //$image_src = 'data:image/jpeg;base64,' . base64_encode($image);
  // Ensure the variable is not null or empty before calling base64_encode
  // Initialize the image source
  $image_src = ''; // Initialize it in case we don't have an image

  // Check if image data exists and encode it, otherwise use a placeholder
  if (isset($image_data) && !empty($image_data)) {
      // If there's image data, encode it
      $encoded_image = base64_encode($image_data);
      $image_src = 'data:image/jpeg;base64,' . $encoded_image; // Assuming it's a jpeg image
  } else {
      // If no image data, use a placeholder image
      $image_src = 'images/imageplaceholder.jpg'; // Replace with the path to your default image
  }
  
  // Print HTML
  echo('
    <li class="list-group-item d-flex justify-content-between align-items-center">
      <div class="p-2">
        <img src="' . $image_src . '" alt="' . htmlspecialchars($title) . '" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
      </div>
      <div class="p-2 flex-grow-1">
        <h5><a href="listing.php?item_id=' . $item_id . '">' . htmlspecialchars($title) . '</a></h5>
        <p>' . htmlspecialchars($desc_shortened) . '</p>
      </div>
      <div class="text-center text-nowrap">
        <span style="font-size: 1.5em">£' . number_format($price, 2) . '</span><br/>
        ' . $num_bids . $bid . '<br/>
        ' . $time_remaining . '
      </div>
    </li>'
  );
}

?>