<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Logout the user
logoutAdmin();

// Redirect to login page
header('Location: login.php');
exit();
?>