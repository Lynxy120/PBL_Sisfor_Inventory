<?php
// Debug helper - shows current user state
// Access via: http://localhost/frontend/debug-user.php

session_start();

$debugInfo = [
    'timestamp' => date('Y-m-d H:i:s'),
    'session' => [
        'user_id' => $_SESSION['user_id'] ?? null,
        'user_name' => $_SESSION['user_name'] ?? null,
        'user_role' => $_SESSION['user_role'] ?? null,
    ],
    'notes' => [
        'message' => 'This page shows current user session state. Check console in browser (F12) to see localStorage state.'
    ]
];

header('Content-Type: application/json');
echo json_encode($debugInfo, JSON_PRETTY_PRINT);
