<?php
// Start the session
session_start();

// Set a session variable to show the logout message
$_SESSION['logout_message'] = "You are now logged out successfully.";

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Flush the session data and create a new session ID
session_regenerate_id(true);

// Redirect to login page
header("Location: login.php");
exit;
?>