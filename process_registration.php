<?php
session_start();
require_once 'database_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Extract and sanitize form inputs
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $day = $_POST['day'];
    $month = $_POST['month'];
    $yearOfBirth = $_POST['yearOfBirth'];
    $houseNumber = $_POST['houseNumber'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $postcode = $_POST['postcode'];
    $country = $_POST['country'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordConfirmation = $_POST['passwordConfirmation'];

    // Validate inputs
    $errors = [];
    if (empty($firstName)) $errors[] = 'First name is required';
    if (empty($lastName)) $errors[] = 'Last name is required';
    if (empty($day) || empty($month) || empty($yearOfBirth)) $errors[] = 'Date of birth is required';
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
        $dateOfBirth = "$yearOfBirth-$month-$day";
        $sql = "INSERT INTO Users (email, passwordHash, firstName, lastName, dateOfBirth, country, street, houseNumber, postcode, city) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param('ssssssssss', $email, $passwordHash, $firstName, $lastName, $dateOfBirth, $country, $street, $houseNumber, $postcode, $city);
        if ($stmt->execute()) {
            // Registration successful, set success message and redirect to login page
            $_SESSION['success_message'] = 'Account creation successful. Please login.';
            header("Location: login.php");
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