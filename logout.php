<?php
session_start();

// Destroy all session variables
$_SESSION = [];
session_unset();
session_destroy();

// Prevent going back with browser back button
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirect to login
header("Location: index.php");
exit();
?>

