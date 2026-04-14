<?php
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/CategoryController.php';
require_once __DIR__ . '/../app/controllers/ItemController.php';
require_once __DIR__ . '/../app/controllers/StockController.php';
require_once __DIR__ . '/../app/controllers/SupplierController.php';
require_once __DIR__ . '/../app/controllers/UserController.php';
require_once __DIR__ . '/../app/controllers/SaleController.php';
require_once __DIR__ . '/../app/controllers/StockHistoryController.php';
require_once __DIR__ . '/../app/controllers/ReportController.php';

$categoryController = new CategoryController();
$itemController = new ItemController();
$stockController = new StockController();

// health check
$router->get('/', function () {
    echo json_encode([
        'status' => 'ok',
        'message' => 'API Inventory UMKM hidup'
    ]);
});

// testing endpoint
$router->get('/ping', function () {
    echo json_encode([
        'message' => 'pong'
    ]);
});

// Serve uploaded images (simple file serve)
$router->get('/uploads/{filename}', function ($filename) {
    $filePath = __DIR__ . '/../storage/uploads/' . $filename;
    if (!file_exists($filePath)) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'File not found']);
        return;
    }

    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $mime = 'application/octet-stream';
    if (in_array($ext, ['jpg', 'jpeg'])) $mime = 'image/jpeg';
    if ($ext === 'png') $mime = 'image/png';

    header('Content-Type: ' . $mime);
    header('Cache-Control: public, max-age=86400');
    readfile($filePath);
});

// auth routes
$router->post('/login', 'AuthController@login');

// user routes
$router->get('/users', 'UserController@index');
$router->get('/users/{id}', 'UserController@show');
$router->post('/users', 'UserController@store');
$router->put('/users/{id}', 'UserController@update');
$router->delete('/users/{id}', 'UserController@delete');

// category routes
$router->get('/categories', 'CategoryController@index');
$router->get('/categories/{id}', 'CategoryController@show');
$router->post('/categories', 'CategoryController@store');
$router->put('/categories/{id}', 'CategoryController@update');
$router->delete('/categories/{id}', 'CategoryController@destroy');

// supplier routes
$router->get('/suppliers', 'SupplierController@index');
$router->get('/suppliers/{id}', 'SupplierController@show');
$router->post('/suppliers', 'SupplierController@store');
$router->put('/suppliers/{id}', 'SupplierController@update');
$router->delete('/suppliers/{id}', 'SupplierController@destroy');

// item routes
$router->get('/items', 'ItemController@index');
$router->get('/items/{id}', 'ItemController@show');
$router->post('/items', 'ItemController@store');
$router->put('/items/{id}', 'ItemController@update');
$router->delete('/items/{id}', 'ItemController@destroy');
$router->post('/items/{id}/photo', 'ItemController@uploadPhoto');

// stock routes
$router->post('/stock-in', 'stockController@stockIn');
$router->post('/stock-out', 'stockController@stockOut');
$router->post('/stock-adjust', 'stockController@stockAdjustment');

// stock history routes
$router->get('/stock-histories', 'StockHistoryController@index');
$router->get('/stock-histories/{id}', 'StockHistoryController@show');
$router->get('/items/{id}/stock-history', 'StockHistoryController@getByItem');
$router->post('/stock-histories/date-range', 'StockHistoryController@getByDateRange');

// sales routes
$router->get('/sales', 'SaleController@index');
$router->get('/sales/{id}', 'SaleController@show');
$router->post('/sales', 'SaleController@store');

// report routes
// report routes
$router->post('/reports/sales', 'ReportController@salesReport');
$router->get('/reports/stock', 'ReportController@stockReport');
$router->post('/reports/low-stock', 'ReportController@lowStockReport');
$router->post('/reports/top-selling', 'ReportController@topSellingItems');

// ============================================
// POS ANDROID ROUTES (Optimized)
// ============================================
require_once __DIR__ . '/../app/controllers/PosController.php';

$router->post('/pos/login', 'PosController@login');
$router->get('/pos/catalog', 'PosController@catalog');
$router->post('/pos/checkout', 'PosController@checkout');
