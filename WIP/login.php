<?php
session_start();
require_once 'database_connect.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract $_POST variables
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        // Query the database for the user
        $sql = "SELECT * FROM Users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['passwordHash'])) {
                // Set session variables
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $user['firstName'];
                $_SESSION['userID'] = $user['userID'];

                // Redirect to browse page
                header("Location: browse.php");
                exit();
            } else {
                echo('<div class="text-center">Invalid password. Please try again.</div>');
            }
        } else {
            echo('<div class="text-center">No user found with that email. Please try again.</div>');
        }
    } else {
        echo('<div class="text-center">Please enter both email and password.</div>');
        // Redirect back to login page after 5 seconds
        header("refresh:5;url=login.php");
    }
} else {
    // Display the login form
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="path/to/your/css/file.css">
    </head>
    <body>
        <div class="container">
            <h2 class="my-3">Login</h2>
            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary form-control">Sign in</button>
            </form>
        </div>
    </body>
    </html>
    <?php
}
?>