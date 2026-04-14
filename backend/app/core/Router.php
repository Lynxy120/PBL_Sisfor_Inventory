<?php

class Router
{
    private array $routes = [];

    public function get(string $path, $handler)
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, $handler)
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function put(string $path, $handler)
    {
        $this->routes['PUT'][$path] = $handler;
    }

    public function delete(string $path, $handler)
    {
        $this->routes['DELETE'][$path] = $handler;
    }

    public function run()
    {
        // Enable CORS for frontend integration
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-User-Id, X-User-Role');

        // Handle preflight requests
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }

        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Strip base path to support running in subdirectories
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if ($scriptDir !== '/' && str_starts_with($uri, $scriptDir)) {
            $uri = substr($uri, strlen($scriptDir));
        }
        if (empty($uri)) $uri = '/';

        foreach ($this->routes[$method] ?? [] as $route => $handler) {

            // ubah /items/{id}/photo → regex
            // allow parameter to match any non-slash segment (filenames, alphanum)
            $pattern = preg_replace('#\{[a-zA-Z_]+\}#', '([^/]+)', $route);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // buang full match

                return $this->callHandler($handler, $matches);
            }
        }

        echo json_encode([
            "status" => "error",
            "message" => "Route not found"
        ]);
    }

    private function callHandler($handler, $params = [])
    {
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
            return;
        }

        if (is_string($handler)) {
            [$controller, $method] = explode('@', $handler);
            require_once __DIR__ . '/../controllers/' . $controller . '.php';

            $instance = new $controller;
            call_user_func_array([$instance, $method], $params);
        }
    }
}
