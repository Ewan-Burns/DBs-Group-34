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
function print_listing_li($item_id, $title, $image, $desc, $price, $num_bids, $end_time, $user_id)
{
    require 'database_connect.php';

    // Truncate descriptions at a word limit
    $word_limit = 15; // Maximum number of words to display
    $words = explode(' ', $desc);
    if (count($words) > $word_limit) {
        $desc_shortened = implode(' ', array_slice($words, 0, $word_limit)) . '...';
    } else {
        $desc_shortened = $desc;
    }

    // Determine singular/plural bid language
    $bid = ($num_bids == 1) ? ' bid' : ' bids';

    // Calculate time remaining until auction ends
    $now = new DateTime();
    if ($now > $end_time) {
        $time_remaining = 'This auction has ended';
    } else {
        $time_to_end = date_diff($now, $end_time); // Get time difference
        $time_remaining = display_time_remaining($time_to_end) . ' remaining';
    }

    // Handle image encoding or use a placeholder
    $image_src = isset($image) && !empty($image)
        ? 'data:image/jpeg;base64,' . base64_encode($image) // Encode image as Base64
        : 'images/imageplaceholder.jpg'; // Default placeholder image

    // Check if the user is watching the item
    $watching_query = "SELECT * FROM Watchlist WHERE userID = ? AND itemID = ?";
    $stmt_watching = $conn->prepare($watching_query);
    $stmt_watching->bind_param("ii", $user_id, $item_id);
    $stmt_watching->execute();
    $watching = $stmt_watching->fetch(); // Fetch result
    $stmt_watching->close();

    // Get the highest bid placed by the user on this item
    $bid_query = "SELECT MAX(amount) AS highest_bid FROM Bids WHERE userID = ? AND itemID = ?";
    $stmt_bid = $conn->prepare($bid_query);
    $stmt_bid->bind_param("ii", $user_id, $item_id);
    $stmt_bid->execute();
    $stmt_bid->bind_result($bid_placed);
    $stmt_bid->fetch(); // Fetch the maximum bid amount
    $stmt_bid->close();

    // Close the database connection
    $conn->close();

    // HTML for the listing
    echo '
    <div class="item-card" style="border: 1px solid #ccc; border-radius: 8px; padding: 15px; margin: 15px; width: 300px; display: inline-block; vertical-align: top; background-color: #f9f9f9; box-shadow: 0 2px 4px rgba(0,0,0,0.1); height: 500px;">
        <img src="' . $image_src . '" alt="' . htmlspecialchars($title) . '" style="width: 100%; height: 200px; object-fit: cover; border-radius: 4px;">
        <h5 style="margin: 5px 0; font-size: 1.5em; height: 2.5em; overflow: hidden;"><a href="listing.php?item_id=' . $item_id . '" style="text-decoration: none; color: #333;">' . htmlspecialchars($title) . '</a></h5>
        <p style="font-size: 0.9em; color: #555; margin: 5px 0; height: 5em; overflow: hidden;">' . htmlspecialchars($desc_shortened) . '</p>
        <div style="font-size: 0.9em; margin: 5px 0; text-align: center;">
            <strong>£' . number_format($price, 2) . '</strong><br/>
            ' . $num_bids . $bid . '<br/>
            ' . $time_remaining . '
        </div>
        <div style="text-align: center; margin: 5px 0;">
            ' . ($watching 
                ? '<span style="display: inline-block; font-size: 0.8em; color: #008000; background-color: #e6ffe6; padding: 3px 6px; border-radius: 4px;">On Watchlist</span>' 
                : '') . '
            ' . ($now > $end_time 
                ? (!empty($bid_placed) && $bid_placed >= $price 
                    ? '<span style="display: inline-block; font-size: 0.8em; color: #008000; background-color: #e6ffe6; padding: 3px 6px; border-radius: 4px;">You have won the auction</span>' 
                    : (!empty($bid_placed) 
                        ? '<span style="display: inline-block; font-size: 0.8em; color: #ff0000; background-color: #ffe6e6; padding: 3px 6px; border-radius: 4px;">You have lost the auction</span>'
                        : '')
                  )
                : (!empty($bid_placed) 
                    ? ($bid_placed >= $price 
                        ? '<span style="display: inline-block; font-size: 0.8em; color: #008000; background-color: #e6ffe6; padding: 3px 6px; border-radius: 4px;">You have the highest bid</span>'
                        : '<span style="display: inline-block; font-size: 0.8em; color: #ff0000; background-color: #ffe6e6; padding: 3px 6px; border-radius: 4px;">You have been outbid</span>
                           <br/><span style="font-size: 0.9em; color: #333;">Your last bid: £' . number_format($bid_placed, 2) . '</span>'
                      )
                    : '')
              ) . '
        </div>
    </div>';

}


?>