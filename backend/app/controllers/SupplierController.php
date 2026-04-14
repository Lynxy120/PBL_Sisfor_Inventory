<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Supplier.php';

class SupplierController extends Controller
{
    public function index()
    {
        $model = new Supplier();
        $this->json(['status' => 'success', 'data' => $model->getAll()]);
    }

    public function show($id)
    {
        $model = new Supplier();
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

        if (empty($input['nama_suppliers'])) {
            $this->json(['status' => 'error', 'message' => 'Nama supplier wajib'], 400);
            return;
        }

        $model = new Supplier();
        $model->create($input);

        $this->json(['status' => 'success', 'message' => 'Supplier dibuat']);
    }

    public function update($id)
    {
        $this->auth(['super_admin', 'admin_gudang']);
        $input = $this->getJsonInput();

        $model = new Supplier();
        if (!$model->find($id)) {
            $this->json(['status' => 'error', 'message' => 'Not found'], 404);
            return;
        }

        $model->update($id, $input);
        $this->json(['status' => 'success', 'message' => 'Supplier diupdate']);
    }

    public function destroy($id)
    {
        $this->auth(['super_admin', 'admin_gudang']);
        $model = new Supplier();
        $model->delete($id);

        $this->json(['status' => 'success', 'message' => 'Supplier dihapus']);
    }
}
