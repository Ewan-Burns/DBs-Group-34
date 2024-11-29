<?php

// Include the database connection file
session_start();
require_once 'database_connect.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the registration form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract form data
    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $day = $_POST['day'] ?? '';
    $month = $_POST['month'] ?? '';
    $year = $_POST['year'] ?? '';
    $dateOfBirth = "$year-$month-$day";
    $country = $_POST['country'] ?? '';
    $street = $_POST['street'] ?? '';
    $houseNumber = $_POST['houseNumber'] ?? '';
    $postcode = $_POST['postcode'] ?? '';
    $city = $_POST['city'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $passwordConfirmation = $_POST['passwordConfirmation'] ?? '';

    // Validate form data
    $errors = [];
    if (empty($firstName)) $errors[] = 'First name is required';
    if (empty($lastName)) $errors[] = 'Last name is required';
    if (empty($day) || empty($month) || empty($year)) $errors[] = 'Date of birth is required';
    if (empty($country)) $errors[] = 'Country is required';
    if (empty($street)) $errors[] = 'Street is required';
    if (empty($houseNumber)) $errors[] = 'House number is required';
    if (empty($postcode)) $errors[] = 'Postcode is required';
    if (empty($city)) $errors[] = 'City is required';
    if (empty($email)) $errors[] = 'Email is required';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format';
    if (empty($password)) $errors[] = 'Password is required';
    if ($password !== $passwordConfirmation) $errors[] = 'Passwords do not match';

    if (empty($errors)) {
        // Hash the password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the Users table
        $sql = "INSERT INTO Users (email, passwordHash, firstName, lastName, dateOfBirth, country, street, houseNumber, postcode, city) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param('ssssssssss', $email, $passwordHash, $firstName, $lastName, $dateOfBirth, $country, $street, $houseNumber, $postcode, $city);
        if ($stmt->execute()) {
            // Notify user of success
            $_SESSION['message'] = 'Account successfully created, please login.';
            header('Location: register.php');
            exit;
        } else {
            // Notify user of failure
            $_SESSION['message'] = 'Registration failed: ' . htmlspecialchars($stmt->error);
            header('Location: register.php');
            exit;
        }
    } else {
        // Notify user of validation errors
        $_SESSION['errors'] = $errors;
        header('Location: register.php');
        exit;
    }
}
?>