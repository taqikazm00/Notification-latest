<?php
session_start();

// Destroy all session data
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to the login page or index.php
header('Location: ../index.php');
exit();
?>