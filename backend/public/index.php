<?php

// CORS Configuration
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-User-Id, X-User-Role');
header('Access-Control-Max-Age: 3600');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../app/core/Env.php';
Env::load(__DIR__ . '/../.env');

require_once __DIR__ . '/../app/core/Router.php';

$router = new Router();

// load routes
require_once __DIR__ . '/../routes/api.php';

// run router
$router->run();
