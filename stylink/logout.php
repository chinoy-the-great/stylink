<?php
session_start(); // Start or resume the session

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page (or any other appropriate page)
header("Location: index.php"); // Replace with your login page
exit;
?>