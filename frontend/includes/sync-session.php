<?php
// sync-session.php - Helper to sync localStorage to PHP session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();

    // Handle logout
    if (!empty($_POST['logout'])) {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();

        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Logged out']);
        exit();
    }

    // Handle sync login
    if (!empty($_POST['user_id']) && !empty($_POST['user_name'])) {
        $_SESSION['user_id'] = $_POST['user_id'];
        $_SESSION['user_name'] = $_POST['user_name'];
        $_SESSION['user_role'] = $_POST['user_role'] ?? '';

        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Session synced']);
        exit();
    }

    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Missing user data']);
    exit();
}

// If GET request, just return current session data
header('Content-Type: application/json');
session_start();
echo json_encode([
    'user_id' => $_SESSION['user_id'] ?? null,
    'user_name' => $_SESSION['user_name'] ?? null,
    'user_role' => $_SESSION['user_role'] ?? null
]);
