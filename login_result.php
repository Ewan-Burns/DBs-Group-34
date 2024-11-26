<?php
session_start();

// Include the database connection file
require_once 'database_connect.php';

// Extract $_POST variables
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Validate form data
$errors = [];
if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
}

if (empty($password)) {
    $errors[] = 'Password is required';
}

if (empty($errors)) {
    // Attempt to log in
    $sql = "SELECT userID, passwordHash FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['passwordHash'])) {
            // Password is correct, log the user in
            $_SESSION['userID'] = $row['userID'];
            $_SESSION['email'] = $email;
            $_SESSION['logged_in'] = true;
            header('Location: index.php'); // Redirect to the homepage or another page
            exit;
        } else {
            $errors[] = 'Incorrect password';
        }
    } else {
        $errors[] = 'No user found with that email address';
    }

    $stmt->close();
}

// If there are errors, store them in the session and redirect back to the login page
if (!empty($errors)) {
    $_SESSION['login_errors'] = $errors;
    $_SESSION['login_email'] = $email; // Store the email to pre-fill the form
    header('Location: login.php'); // Redirect to the login page
    exit;
}

$conn->close();
?>