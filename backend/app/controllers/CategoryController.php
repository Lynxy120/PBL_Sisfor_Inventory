<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Category.php';

class CategoryController extends Controller
{
    public function index()
    {
        $model = new Category();
        $this->json(['status' => 'success', 'data' => $model->getAll()]);
    }

    public function show($id)
    {
        $model = new Category();
        $data = $model->find($id);

        if (!$data) {
            $this->json(['status' => 'error', 'message' => 'Not found'], 404);
            return;
        }

        $this->json(['status' => 'success', 'data' => $data]);
    }

    public function store()
    {
        $this->auth(['super_admin', 'admin_gudang']);
        $input = $this->getJsonInput();

        if (empty($input['nama_kategori'])) {
            $this->json(['status' => 'error', 'message' => 'Nama wajib'], 400);
            return;
        }

        $model = new Category();
        $model->create($input['nama_kategori']);

        $this->json(['status' => 'success', 'message' => 'Kategori dibuat']);
    }

    public function update($id)
    {
        $this->auth(['super_admin', 'admin_gudang']);
        $input = $this->getJsonInput();

        $model = new Category();
        if (!$model->find($id)) {
            $this->json(['status' => 'error', 'message' => 'Not found'], 404);
            return;
        }

        $model->update($id, $input['nama_kategori']);
        $this->json(['status' => 'success', 'message' => 'Kategori diupdate']);
    }

    public function destroy($id)
    {
        $this->auth(['super_admin', 'admin_gudang']);
        $model = new Category();
        $model->delete($id);

        $this->json(['status' => 'success', 'message' => 'Kategori dihapus']);
    }
}
