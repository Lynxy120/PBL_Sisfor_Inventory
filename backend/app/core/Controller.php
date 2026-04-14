<?php
require_once __DIR__ . '/Response.php';
require_once __DIR__ . '/../helpers/Auth.php';

class Controller
{
    protected function json($data, int $code = 200)
    {
        Response::json($data, $code);
    }

    protected function getJsonInput()
    {
        return json_decode(file_get_contents("php://input"), true);
    }

    protected function auth(array $roles = [])
    {
        $user = Auth::user();
        if (!empty($roles)) {
            Auth::role($roles);
        }
        return $user;
    }
}
