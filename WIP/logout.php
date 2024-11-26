<?php
session_start(); // Start the session

// Unset all session variables related to the user's login state
unset($_SESSION['logged_in']);
unset($_SESSION['username']);
unset($_SESSION['userID']);

// Delete the session cookie
setcookie(session_name(), "", time() - 3600, "/");

// Destroy the session
session_destroy();

// Redirect to the index page
header("Location: index.php");
exit();
?>