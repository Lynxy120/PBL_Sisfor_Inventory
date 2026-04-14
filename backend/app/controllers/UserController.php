<?php
require_once __DIR__ . '/../core/Controller.php';

class UserController extends Controller
{
    private User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function index()
    {
        $this->auth(['super_admin']);

        $this->json([
            'status' => 'success',
            'data' => $this->user->all()
        ]);
    }

    public function show($id)
    {
        $this->auth(['super_admin']);

        $data = $this->user->find($id);
        if (!$data) {
            $this->json(['status' => 'error', 'message' => 'User not found'], 404);
            return;
        }

        $this->json(['status' => 'success', 'data' => $data]);
    }

    public function store()
    {
        Auth::user();
        Auth::role(['super_admin']);

        $data = $_POST;

        if (!$this->user->store($data)) {
            Response::json(['status' => 'error', 'message' => 'Gagal tambah user'], 500);
            return;
        }

        Response::json(['status' => 'success', 'message' => 'User berhasil ditambahkan']);
    }

    public function update($id)
    {
        Auth::user();
        Auth::role(['super_admin']);

        parse_str(file_get_contents("php://input"), $data);

        if (!$this->user->update($id, $data)) {
            Response::json(['status' => 'error', 'message' => 'Gagal update user'], 500);
            return;
        }

        Response::json(['status' => 'success', 'message' => 'User berhasil diupdate']);
    }

    public function delete($id)
    {
        Auth::user();
        Auth::role(['super_admin']);

        if (!$this->user->delete($id)) {
            Response::json(['status' => 'error', 'message' => 'Gagal hapus user'], 500);
            return;
        }

        Response::json(['status' => 'success', 'message' => 'User berhasil dihapus']);
    }
}
