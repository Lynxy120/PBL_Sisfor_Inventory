<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../models/Sale.php';
require_once __DIR__ . '/../models/StockHistory.php';
require_once __DIR__ . '/../models/SaleItem.php';
require_once __DIR__ . '/../helpers/Auth.php';

class SaleController extends Controller
{
    public function index()
    {
        $saleModel = new Sale();
        $data = $saleModel->getAll();

        $this->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function store()
    {
        $this->auth(['super_admin', 'sales']);
        $input = $this->getJsonInput();

        if (!isset($input['kasir_id']) || empty($input['items'])) {
            $this->json([
                'status' => 'error',
                'message' => 'Data tidak lengkap'
            ], 400);
            return;
        }

        $kasir_id = $input['kasir_id'];
        $items = $input['items'];

        $itemModel = new Item();
        $saleModel = new Sale();
        $stockModel = new StockHistory();
        $saleItemModel = new SaleItem();

        $total = 0;
        $detailItems = [];

        // 🔁 VALIDASI ITEM & HITUNG TOTAL
        foreach ($items as $row) {
            $item = $itemModel->find($row['item_id']);

            if (!$item) {
                $this->json([
                    'status' => 'error',
                    'message' => 'Item tidak ditemukan'
                ], 404);
                return;
            }

            if ($item['stok'] < $row['jumlah']) {
                $this->json([
                    'status' => 'error',
                    'message' => 'Stok tidak cukup untuk ' . $item['nama_barang']
                ], 400);
                return;
            }

            $subtotal = $item['harga_jual'] * $row['jumlah'];
            $total += $subtotal;

            $detailItems[] = [
                'item' => $item,
                'jumlah' => $row['jumlah'],
                'harga' => $item['harga_jual']
            ];
        }

        // 🧾 INSERT SALES
        Database::beginTransaction();
        try {
            $sale_id = $saleModel->create([
                'kasir_id' => $kasir_id,
                'total' => $total,
                'tanggal' => date('Y-m-d H:i:s')
            ]);

            // 🔁 INSERT DETAIL + UPDATE STOK + HISTORY
            foreach ($detailItems as $row) {

                $saleModel->addItem([
                    'sale_id' => $sale_id,
                    'item_id' => $row['item']['id'],
                    'jumlah' => $row['jumlah'],
                    'harga' => $row['harga']
                ]);

                $itemModel->decreaseStock(
                    $row['item']['id'],
                    $row['jumlah']
                );

                $stockModel->create([
                    'item_id' => $row['item']['id'],
                    'supplier_id' => null,
                    'tipe' => 'out',
                    'jumlah' => $row['jumlah'],
                    'sumber' => 'sales',
                    'ref_id' => $sale_id,
                    'keterangan' => 'Penjualan',
                    'tanggal' => date('Y-m-d H:i:s')
                ]);
            }

            Database::commit();

            $this->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil',
                'sale_id' => $sale_id,
                'total' => $total
            ]);
        } catch (Exception $e) {
            Database::rollBack();
            $this->json([
                'status' => 'error',
                'message' => 'Transaksi gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $saleModel = new Sale();
        $sale = $saleModel->findWithItems($id);

        if (!$sale) {
            $this->json([
                'status' => 'error',
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
            return;
        }

        $this->json([
            'status' => 'success',
            'data' => $sale
        ]);
    }
}
