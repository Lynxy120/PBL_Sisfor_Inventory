<?php

require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../models/StockHistory.php';
require_once __DIR__ . '/../core/Controller.php';

class StockController extends Controller
{
    public function stockIn()
    {
        $this->auth(['super_admin', 'admin_gudang']);
        $input = $this->getJsonInput();

        $required = ['item_id', 'jumlah'];
        foreach ($required as $field) {
            if (!isset($input[$field]) || $input[$field] <= 0) {
                $this->json([
                    'status' => 'error',
                    'message' => "$field tidak valid"
                ], 422);
                return;
            }
        }

        $itemModel = new Item();
        $item = $itemModel->find($input['item_id']);

        if (!$item) {
            $this->json([
                'status' => 'error',
                'message' => 'Item tidak ditemukan'
            ], 404);
            return;
        }

        $stokBaru = $item['stok'] + $input['jumlah'];

        // update stok item
        $itemModel->updateStock($input['item_id'], $stokBaru);

        // simpan riwayat
        $history = new StockHistory();
        $history->create([
            'item_id'     => $input['item_id'],
            'supplier_id' => $input['supplier_id'] ?? null,
            'tipe'        => 'in',
            'jumlah'      => $input['jumlah'],
            'sumber'      => 'supplier',
            'ref_id'      => null,
            'keterangan'  => $input['keterangan'] ?? 'Stok masuk',
            'tanggal'     => date('Y-m-d H:i:s')
        ]);

        $this->json([
            'status' => 'success',
            'message' => 'Stok berhasil ditambahkan'
        ]);
    }

    public function stockOut()
    {
        $this->auth(['super_admin', 'admin_gudang', 'sales']);
        $input = $this->getJsonInput();

        $required = ['item_id', 'jumlah'];
        foreach ($required as $field) {
            if (!isset($input[$field]) || $input[$field] <= 0) {
                $this->json([
                    'status' => 'error',
                    'message' => "$field tidak valid"
                ], 422);
                return;
            }
        }

        $itemModel = new Item();
        $item = $itemModel->find($input['item_id']);

        if (!$item) {
            $this->json([
                'status' => 'error',
                'message' => 'Item tidak ditemukan'
            ], 404);
            return;
        }

        if ($item['stok'] < $input['jumlah']) {
            $this->json([
                'status' => 'error',
                'message' => 'Stok tidak mencukupi'
            ], 422);
            return;
        }

        $stokBaru = $item['stok'] - $input['jumlah'];

        $itemModel->updateStock($input['item_id'], $stokBaru);

        $history = new StockHistory();
        $history->create([
            'item_id'     => $input['item_id'],
            'supplier_id' => null,
            'tipe'        => 'out',
            'jumlah'      => $input['jumlah'],
            'sumber'      => 'sales',
            'ref_id'      => null,
            'keterangan'  => $input['keterangan'] ?? 'Stok keluar',
            'tanggal'     => date('Y-m-d H:i:s')
        ]);

        $this->json([
            'status' => 'success',
            'message' => 'Stok berhasil dikurangi'
        ]);
    }

    public function stockAdjustment()
    {
        $this->auth(['super_admin']);
        $input = $this->getJsonInput();

        if (!isset($input['item_id']) || !isset($input['stok_baru'])) {
            $this->json([
                'status' => 'error',
                'message' => 'item_id dan stok_baru wajib diisi'
            ], 422);
            return;
        }

        $itemModel = new Item();
        $item = $itemModel->find($input['item_id']);

        if (!$item) {
            Response::json([
                'status' => 'error',
                'message' => 'Item tidak ditemukan'
            ], 404);
            return;
        }

        $selisih = $input['stok_baru'] - $item['stok'];

        $itemModel->updateStock($input['item_id'], $input['stok_baru']);

        $history = new StockHistory();
        $history->create([
            'item_id'     => $input['item_id'],
            'supplier_id' => null,
            'tipe'        => 'adjustment',
            'jumlah'      => abs($selisih),
            'sumber'      => 'supplier',
            'ref_id'      => null,
            'keterangan'  => $input['keterangan'] ?? 'Penyesuaian stok (sebelumnya: ' . $item['stok'] . ')',
            'tanggal'     => date('Y-m-d H:i:s')
        ]);

        $this->json([
            'status' => 'success',
            'message' => 'Stok berhasil disesuaikan',
            'stok_lama' => $item['stok'],
            'stok_baru' => $input['stok_baru']
        ]);
    }
}
