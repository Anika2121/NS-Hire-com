<?php
session_start();

// Destroy the session and redirect to the login page
session_unset(); // Remove session variables
session_destroy(); // Destroy the session

// Redirect to the login page
header("Location: ../index.html");
exit;
?>
