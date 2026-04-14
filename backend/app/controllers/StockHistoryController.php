<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/StockHistory.php';

class StockHistoryController extends Controller
{
    public function index()
    {
        $this->auth(['super_admin', 'admin_gudang']);
        $model = new StockHistory();
        $this->json([
            'status' => 'success',
            'data' => $model->getAll()
        ]);
    }

    public function show($id)
    {
        $this->auth(['super_admin', 'admin_gudang']);
        $db = Database::connect();

        $stmt = $db->prepare("SELECT * FROM stock_histories WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            $this->json([
                'status' => 'error',
                'message' => 'Stock history not found'
            ], 404);
            return;
        }

        $this->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function getByItem($item_id)
    {
        $this->auth(['super_admin', 'admin_gudang']);
        $model = new StockHistory();
        $data = $model->getByItem($item_id);

        $this->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function getByDateRange()
    {
        $this->auth(['super_admin', 'admin_gudang']);
        $input = $this->getJsonInput();

        if (!isset($input['start']) || !isset($input['end'])) {
            $this->json([
                'status' => 'error',
                'message' => 'start dan end date wajib diisi'
            ], 400);
            return;
        }

        $model = new StockHistory();
        $data = $model->getByDateRange($input['start'], $input['end']);

        $this->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
