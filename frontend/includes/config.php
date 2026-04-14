<?php
// Common configuration and API base URL
define('API_BASE_URL', 'http://localhost:8001');

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get current page for active menu highlighting
$currentPage = basename($_SERVER['PHP_SELF']);

// Get user info from session (if logged in)
$userName = $_SESSION['user_name'] ?? 'Guest';
$userRole = $_SESSION['user_role'] ?? '';
$userId = $_SESSION['user_id'] ?? null;
