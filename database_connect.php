<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Insert User and Display All Users</title>
</head>
<body>
   <h2>Enter New User Email</h2>
   <form action="" method="POST">
       <label for="email">Email:</label>
       <input type="email" id="email" name="email" required>
       <br><br>
       <input type="submit" value="Add User">
   </form>




   <?php
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
       // Get the email from the form input
       $email = $conn->real_escape_string($_POST['email']);

       // Insert query
       $sql = "INSERT INTO Users (email) VALUES ('$email')";

       // Execute query and check for success
       if ($conn->query($sql) === TRUE) {
           echo "<p>New user created successfully</p>";
       } else {
           echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
       }

       // Fetch and display all users
       $sql = "SELECT id, email FROM users";
       $result = $conn->query($sql);
       if ($result->num_rows > 0) {
           echo "<h2>All Users</h2>";
           echo "<table border='1'>";
           echo "<tr><th>ID</th><th>Email</th></tr>";
           while ($row = $result->fetch_assoc()) {
               echo "<tr><td>" . $row["id"] . "</td><td>" . $row["email"] . "</td></tr>";
           }
           echo "</table>";
       } else {
           echo "<p>No users found.</p>";
       }
   }

   // Close the database connection
   $conn->close();
   ?>

   <br>
   <a href="?">Add Another User</a>
</body>
</html>