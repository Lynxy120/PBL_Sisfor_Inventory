<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../models/Sale.php';
require_once __DIR__ . '/../models/SaleItem.php';
require_once __DIR__ . '/../models/StockHistory.php';

class PosController extends Controller
{
    // 1. Login Khusus Kasir
    public function login()
    {
        $input = $this->getJsonInput();
        if (!isset($input['username']) || !isset($input['password'])) {
            $this->json(['status' => 'error', 'message' => 'Username & Password required'], 400);
            return;
        }

        $userModel = new User();
        $user = $userModel->findByUsername($input['username']);

        if (!$user || !password_verify($input['password'], $user['password'])) {
            $this->json(['status' => 'error', 'message' => 'Invalid credentials'], 401);
            return;
        }

        // Response ringkas untuk mobile (tanpa token, menggunakan header auth)
        $this->json([
            'status' => 'success',
            'data' => [
                'id' => $user['id'],
                'nama' => $user['nama'],
                'role' => $user['role']
            ]
        ]);
    }

    // 2. Catalog Ringan (Hide Harga Beli)
    public function catalog()
    {
        $this->auth(['sales', 'super_admin']); // Hanya kasir & super admin

        $db = Database::connect();
        // Ambil hanya yg stok > 0 dan kolom yg diperlukan
        $sql = "SELECT id, nama_barang, harga_jual, stok, foto, kategori_id 
                FROM items 
                WHERE stok > 0 
                ORDER BY nama_barang ASC";

        $items = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $this->json([
            'status' => 'success',
            'data' => $items
        ]);
    }

    // 3. Checkout Atomic Transaction
    public function checkout()
    {
        $user = $this->auth(['sales', 'super_admin']);
        $input = $this->getJsonInput();

        if (empty($input['items'])) {
            $this->json(['status' => 'error', 'message' => 'Cart is empty'], 400);
            return;
        }

        $itemsRequest = $input['items'];
        $kasir_id = $user['id'];
        $total = 0;
        $processedItems = [];

        $itemModel = new Item();
        $saleModel = new Sale();
        $saleItemModel = new SaleItem();
        $stockModel = new StockHistory();

        // Mulai Transaksi Database
        try {
            Database::beginTransaction();

            // A. Validasi & Hitung Total
            foreach ($itemsRequest as $req) {
                $item = $itemModel->find($req['item_id']);

                if (!$item) {
                    throw new Exception("Item ID {$req['item_id']} not found");
                }

                if ($item['stok'] < $req['jumlah']) {
                    throw new Exception("Stok tidak cukup untuk {$item['nama_barang']} (Sisa: {$item['stok']})");
                }

                $subtotal = $item['harga_jual'] * $req['jumlah'];
                $total += $subtotal;

                $processedItems[] = [
                    'item' => $item,
                    'jumlah' => $req['jumlah'],
                    'harga' => $item['harga_jual']
                ];
            }

            // B. Insert Sale Header
            $sale_id = $saleModel->create([
                'kasir_id' => $kasir_id,
                'total' => $total,
                'tanggal' => date('Y-m-d H:i:s')
            ]);

            if (!$sale_id) {
                throw new Exception("Failed to create sale record");
            }

            // C. Insert Items & Update Stock
            foreach ($processedItems as $pItem) {
                // 1. Insert ke sales_items
                $saleModel->addItem([
                    'sale_id' => $sale_id,
                    'item_id' => $pItem['item']['id'],
                    'jumlah' => $pItem['jumlah'],
                    'harga' => $pItem['harga']
                ]);

                // 2. Kurangi Stok
                $itemModel->decreaseStock($pItem['item']['id'], $pItem['jumlah']);

                // 3. Catat History
                $stockModel->create([
                    'item_id' => $pItem['item']['id'],
                    'supplier_id' => null,
                    'tipe' => 'out',
                    'jumlah' => $pItem['jumlah'],
                    'sumber' => 'sales',
                    'ref_id' => $sale_id,
                    'keterangan' => 'POS Transaction',
                    'tanggal' => date('Y-m-d H:i:s')
                ]);
            }

            // Jika semua lancar, Commit!
            Database::commit();

            $this->json([
                'status' => 'success',
                'message' => 'Transaction successful',
                'sale_id' => $sale_id,
                'total' => $total
            ]);
        } catch (Exception $e) {
            // Jika ada error, Rollback semua perubahan
            Database::rollBack();

            $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
