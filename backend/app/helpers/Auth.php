<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/Env.php';

class Auth
{
    private static $user = null;

    public static function user()
    {
        if (self::$user !== null) return self::$user;

        $headers = getallheaders();
        $userId = $headers['X-User-Id'] ?? null;
        $userRole = $headers['X-User-Role'] ?? null;

        if (!$userId || !$userRole) {
            Response::json(['status' => 'error', 'message' => 'Unauthorized'], 401);
            exit;
        }

        $userModel = new User();
        self::$user = $userModel->find($userId);

        if (!self::$user || self::$user['role'] !== $userRole) {
            Response::json(['status' => 'error', 'message' => 'Invalid user or role'], 401);
            exit;
        }

        return self::$user;
    }

    public static function role(array $roles)
    {
        $user = self::user();
        if (!in_array($user['role'], $roles)) {
            Response::json(['status' => 'error', 'message' => 'Forbidden: Access Denied'], 403);
            exit;
        }
    }
}
