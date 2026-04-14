<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Response.php';

class AuthController extends Controller
{
    public function login()
    {
        $userModel = new User();
        $data = $this->getJsonInput();

        if (!isset($data['username']) || !isset($data['password'])) {
            $this->json([
                'status' => 'error',
                'message' => 'Username dan password wajib diisi'
            ], 400);
            return;
        }

        $user = $userModel->findByUsername($data['username']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            $this->json(['status' => 'error', 'message' => 'Login gagal'], 401);
            return;
        }

        $this->json([
            'status' => 'success',
            'data' => [
                'id' => $user['id'],
                'nama' => $user['nama'],
                'role' => $user['role']
            ]
        ]);
    }
}
