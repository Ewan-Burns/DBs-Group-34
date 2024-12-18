<?php
  // For now, index.php just redirects to browse.php, but you can change this
  // if you like.
  
  //header("Location: browse.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags and title -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vintage Car Auctions</title> <!-- Updated title -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        /* Style for the image at the top */
        .header-image {
            width: 100%;
            height: auto;
            z-index: -1;
            margin-bottom: 20px; /* Reduces space between the image and the content below */
        }

        /* Center the content below the image */
        .container {
            text-align: center;
        }

        /* Style for buttons */
        .btn-custom {
            margin: 10px;
            padding: 15px 30px;
            font-size: 1.2rem;
            background-color: #6c757d; /* Custom grey color */
            color: white;
            border: none;
        }
        
        .btn-custom:hover {
            background-color: #5a6268; /* Darker shade on hover */
        }

        /* Style for the register link text */
        .register-link {
            margin-top: 20px;
            font-size: 1rem;
            color: #666;
        }

        .register-link a {
            color: #007bff;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Image at the top -->
<div class="text-center">
    <img src="images/mustang.jpg" alt="Mustang" class="header-image">
</div>

<!-- Main content container -->
<div class="container">
    <!-- Main title -->
    <h1>Vintage Car Auctions</h1> 
    
    <!-- Buttons for navigation -->
    <div>
        <a href="browse.php" class="btn btn-custom">Sell your vintage car or find your next dream ride!</a>
    </div>
    
    <!-- Register link at the bottom -->
    <div class="register-link">
        Not a user yet? <a href="register.php">Register here!</a>
    </div>
</div>

<!-- Bootstrap JS, jQuery, and Popper.js for Bootstrap components (if needed) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
<?php include_once("footer.php")?>
