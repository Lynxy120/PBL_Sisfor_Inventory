<?php

require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Supplier.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../helpers/Upload.php';

class ItemController extends Controller
{
    public function index()
    {
        $item = new Item();
        $this->json([
            'status' => 'success',
            'data' => $item->getAll()
        ]);
    }

    public function show($id)
    {
        $model = new Item();
        $item = $model->find($id);

        if (!$item) {
            $this->json([
                'status' => 'error',
                'message' => 'Item tidak ditemukan'
            ], 404);
            return;
        }

        $this->json([
            'status' => 'success',
            'data' => $item
        ]);
    }

    public function store()
    {
        $this->auth(['super_admin', 'admin_gudang']);

        // 1. Ambil data teks
        $data = $_POST;

        // 2. Validasi wajib
        $required = ['kategori_id', 'nama_barang', 'harga_beli', 'harga_jual', 'stok'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                $this->json([
                    'status' => 'error',
                    'message' => "Field {$field} wajib diisi"
                ], 400);
                return;
            }
        }

        // 3. Validasi tipe data numerik
        if (!is_numeric($data['harga_beli']) || !is_numeric($data['harga_jual']) || !is_numeric($data['stok'])) {
            $this->json([
                'status' => 'error',
                'message' => 'Harga dan stok harus berupa angka'
            ], 400);
            return;
        }

        // 4. Validasi FK kategori_id
        $category = new Category();
        if (!$category->find($_POST['kategori_id'])) {
            $this->json([
                'status' => 'error',
                'message' => 'Kategori tidak valid'
            ], 400);
            return;
        }

        // 5. Proses upload foto (opsional)
        $fotoName = null;
        $uploadPath = null;

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
            $allowedExt = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowedExt)) {
                $this->json([
                    'status' => 'error',
                    'message' => 'Format foto harus jpg, jpeg, atau png'
                ], 400);
                return;
            }

            $fotoName = 'item_' . uniqid() . '.' . $ext;
            $uploadPath = __DIR__ . '/../../storage/uploads/' . $fotoName;

            if (!move_uploaded_file($_FILES['foto']['tmp_name'], $uploadPath)) {
                Response::json([
                    'status' => 'error',
                    'message' => 'Gagal upload foto'
                ], 500);
                return;
            }
        }

        // 6. Simpan ke database
        $itemModel = new Item();

        $created = $itemModel->create([
            'kategori_id' => $data['kategori_id'],
            'nama_barang' => $data['nama_barang'],
            'harga_beli'  => $data['harga_beli'],
            'harga_jual'  => $data['harga_jual'],
            'stok'        => $data['stok'],
            'foto'        => $fotoName
        ]);

        // 7. Handle hasil
        if ($created) {
            $this->json([
                'status' => 'success',
                'message' => 'Item berhasil ditambahkan'
            ]);
        } else {
            // rollback foto kalau DB gagal
            if ($fotoName && file_exists($uploadPath)) {
                unlink($uploadPath);
            }

            $this->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan item'
            ], 500);
        }
    }


    public function update($id)
    {
        $this->auth(['super_admin', 'admin_gudang']);
        $data = $_POST; // multipart support

        $itemModel = new Item();
        $existing = $itemModel->find($id);

        if (!$existing) {
            $this->json([
                'status' => 'error',
                'message' => 'Item tidak ditemukan'
            ], 404);
            return;
        }

        $fotoName = $existing['foto'];

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
            $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $fotoName = 'item_' . uniqid() . '.' . $ext;

            $uploadPath = __DIR__ . '/../../storage/uploads/' . $fotoName;

            if (!move_uploaded_file($_FILES['foto']['tmp_name'], $uploadPath)) {
                $this->json([
                    'status' => 'error',
                    'message' => 'Gagal upload foto'
                ], 500);
                return;
            }

            // hapus foto lama
            if ($existing['foto']) {
                $oldPath = __DIR__ . '/../../storage/uploads/' . $existing['foto'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
        }

        $updated = $itemModel->update($id, [
            'kategori_id' => $data['kategori_id'] ?? null,
            'nama_barang' => $data['nama_barang'] ?? null,
            'harga_beli'  => $data['harga_beli'] ?? null,
            'harga_jual'  => $data['harga_jual'] ?? null,
            'stok'        => $data['stok'] ?? null,
            'foto'        => $fotoName
        ]);

        if ($updated) {
            $this->json([
                'status' => 'success',
                'message' => 'Item berhasil diupdate'
            ]);
        } else {
            $this->json([
                'status' => 'error',
                'message' => 'Item gagal diupdate'
            ], 500);
        }
    }


    public function destroy($id)
    {
        $this->auth(['super_admin', 'admin_gudang']);
        $item = new Item();

        $deleted = $item->delete($id);

        if ($deleted) {
            $this->json([
                'status' => 'success',
                'message' => 'Item berhasil dihapus'
            ]);
        } else {
            $this->json([
                'status' => 'error',
                'message' => 'Item gagal dihapus atau data tidak ditemukan'
            ], 404);
        }
    }

    public function uploadPhoto($id)
    {
        $this->auth(['super_admin', 'admin_gudang']);
        if (!isset($_FILES['foto'])) {
            $this->json([
                'status' => 'error',
                'message' => 'File foto wajib diupload'
            ], 422);
        }

        $itemModel = new Item();
        $item = $itemModel->find($id);

        if (!$item) {
            $this->json([
                'status' => 'error',
                'message' => 'Item tidak ditemukan'
            ], 404);
        }

        $filename = Upload::image(
            $_FILES['foto'],
            __DIR__ . '/../../storage/uploads'
        );

        if (!$filename) {
            $this->json([
                'status' => 'error',
                'message' => 'Upload foto gagal'
            ], 422);
        }

        $itemModel->updatePhoto($id, $filename);

        $this->json([
            'status' => 'success',
            'message' => 'Foto berhasil diupload',
            'foto' => $filename
        ]);
    }
}
