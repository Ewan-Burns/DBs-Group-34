<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Insert New User</title>
</head>
<body>
   <h2>Enter New User Details</h2>
   <form action="" method="POST">
       <label for="email">Email:</label>
       <input type="email" id="email" name="email" required><br><br>

       <label for="passwordHash">Password Hash:</label>
       <input type="text" id="passwordHash" name="passwordHash" required><br><br>

       <label for="firstName">First Name:</label>
       <input type="text" id="firstName" name="firstName" required><br><br>

       <label for="lastName">Last Name:</label>
       <input type="text" id="lastName" name="lastName" required><br><br>

       <label for="dateOfBirth">Date of Birth:</label>
       <input type="date" id="dateOfBirth" name="dateOfBirth" required><br><br>

       <label for="country">Country:</label>
       <input type="text" id="country" name="country" required><br><br>

       <label for="street">Street:</label>
       <input type="text" id="street" name="street" required><br><br>

       <label for="houseNumber">House Number:</label>
       <input type="number" id="houseNumber" name="houseNumber" required><br><br>

       <label for="postcode">Postcode:</label>
       <input type="text" id="postcode" name="postcode" required><br><br>

       <label for="city">City:</label>
       <input type="text" id="city" name="city" required><br><br>

       <input type="submit" value="Add User">
   </form>

   <?php
   ini_set('display_errors', 1);
   ini_set('display_startup_errors', 1);
   error_reporting(E_ALL);

   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "CourseWork";

   // Create connection
   $conn = new mysqli($servername, $username, $password, $dbname);

   // Check connection
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }

   // Check if form data has been submitted
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
       // Get the data from the form input
       $email = $conn->real_escape_string($_POST['email']);
       $passwordHash = $conn->real_escape_string($_POST['passwordHash']);
       $firstName = $conn->real_escape_string($_POST['firstName']);
       $lastName = $conn->real_escape_string($_POST['lastName']);
       $dateOfBirth = $conn->real_escape_string($_POST['dateOfBirth']);
       $country = $conn->real_escape_string($_POST['country']);
       $street = $conn->real_escape_string($_POST['street']);
       $houseNumber = $conn->real_escape_string($_POST['houseNumber']);
       $postcode = $conn->real_escape_string($_POST['postcode']);
       $city = $conn->real_escape_string($_POST['city']);

       // Insert query
       $sql = "INSERT INTO Users (email, passwordHash, firstName, lastName, dateOfBirth, country, street, houseNumber, postcode, city)
               VALUES ('$email', '$passwordHash', '$firstName', '$lastName', '$dateOfBirth', '$country', '$street', '$houseNumber', '$postcode', '$city')";

       // Execute query and check for success
       if ($conn->query($sql) === TRUE) {
           echo "<p>Success: New user created.</p>";
       } else {
           echo "<p>Error inserting user: " . $conn->error . "</p>";
       }
   }

   // Close the database connection
   $conn->close();
   ?>
</body>
</html>
