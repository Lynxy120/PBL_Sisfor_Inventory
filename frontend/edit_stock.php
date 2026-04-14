<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Stok</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container-fluid px-4">
        <div class="row justify-content-center mt-4 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Edit Stok Barang</h5>
                    </div>
                    <div class="card-body">

                        <form id="formStock">

                            <!-- Nama Barang -->
                            <div class="mb-3">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" id="nama_barang" class="form-control" placeholder="Masukkan nama barang" required>
                            </div>

                            <!-- Jumlah -->
                            <div class="mb-3">
                                <label class="form-label">Jumlah Stok</label>
                                <input type="number" id="jumlah" class="form-control" placeholder="Masukkan jumlah stok" required>
                            </div>

                            <!-- Kategori -->
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select id="kategori" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="Elektronik">Elektronik</option>
                                    <option value="ATK">ATK</option>
                                    <option value="Makanan">Makanan</option>
                                </select>
                            </div>

                            <!-- Harga Beli -->
                            <div class="mb-3">
                                <label class="form-label">Harga Beli</label>
                                <input type="number" id="harga_beli" class="form-control" placeholder="Masukkan harga beli" required>
                            </div>
                            <!-- Harga Jual -->
                            <div class="mb-3">
                                <label class="form-label">Harga Jual</label>
                                <input type="number" id="harga_jual" class="form-control" placeholder="Masukkan harga jual" required>
                            </div>

                            <!--Foto-->
                            <div class="mb-3">
                                <label class="form-label">Foto Produk</label>
                                <input type="file" id="foto_produk" class="form-control" accept="image/*" required>
                            </div>

                            <!--Keterangan-->
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea id="keterangan" class="form-control" rows="3" placeholder="Masukkan keterangan tambahan"></textarea>
                            </div>

                            <!-- Tanggal -->
                            <div class="mb-3">
                                <label class="form-label">Tanggal Masuk</label>
                                <input type="date" id="tanggal" class="form-control" required>
                            </div>

                            <!-- Tombol -->
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary" onclick="window.location.href='table_stock.php'; return false;">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    Simpan Data
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="app.js"></script>
</body>

</html>