<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Stok</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/stock.css">
</head>

<body class="bg-light">

    <div class="container-fluid py-4">
        <h4 class="fw-bold">Kelola Stok</h4>
        <p class="text-muted">Lakukan input stok masuk, stok keluar, atau penyesuaian stok.</p>

        <div class="row">
            <!-- ===== FORM ===== -->
            <div class="col-lg-5">
                <div class="card card-custom">
                    <div class="card-body">
                        <h6 class="mb-3 fw-semibold">Form Pergerakan Stok</h6>
                        <form id="formStock">
                            <!-- Barang -->
                            <div class="mb-3">
                                <label class="form-label">Cari Barang</label>
                                <select id="barang" class="form-select">
                                    <option value="">Pilih Barang...</option>
                                    <option value="Pulpen Hitam">Pulpen Hitam</option>
                                    <option value="Lampu LED 10W">Lampu LED 10W</option>
                                    <option value="Mie Instan Goreng">Mie Instan Goreng</option>
                                </select>
                            </div>

                            <!-- Tipe -->
                            <div class="mb-3">
                                <label class="form-label">Tipe Transaksi</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipe" value="Masuk" checked>
                                        <label class="form-check-label text-success">Stok Masuk</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipe" value="Keluar">
                                        <label class="form-check-label text-danger">Stok Keluar</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipe" value="Penyesuaian">
                                        <label class="form-check-label text-primary">Penyesuaian</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Supplier -->
                            <div class="mb-3">
                                <label class="form-label">Supplier (Opsional)</label>
                                <select id="supplier" class="form-select">
                                    <option value="">Pilih Supplier...</option>
                                    <option value="PT Sumber Jaya">PT Sumber Jaya</option>
                                    <option value="CV Makmur">CV Makmur</option>
                                </select>
                            </div>

                            <!-- Jumlah -->
                            <div class="mb-3">
                                <label class="form-label">Jumlah</label>
                                <input type="number" id="jumlah" class="form-control" placeholder="Masukkan jumlah">
                            </div>

                            <!-- Keterangan -->
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea id="keterangan" class="form-control" rows="3" placeholder="Alasan stok masuk/keluar..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                Simpan Transaksi Stok
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ===== RIWAYAT ===== -->
            <div class="col-lg-7">
                <div class="card card-custom">
                    <div class="card-body">
                        <h6 class="mb-3 fw-semibold">Update Stok Terakhir</h6>

                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Barang</th>
                                        <th>Tipe</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody id="riwayatTable">
                                    <tr>
                                        <td>10 Jan 2026, 11.00</td>
                                        <td>Pulpen Hitam</td>
                                        <td class="text-success fw-semibold">Masuk</td>
                                        <td>3</td>
                                    </tr>
                                    <tr>
                                        <td>9 Jan 2026, 18.12</td>
                                        <td>Lampu LED 10W</td>
                                        <td class="text-danger fw-semibold">Keluar</td>
                                        <td>2</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-outline-secondary btn-sm">
                                Lihat Semua Riwayat
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="../assets/js/stock.js"></script>
</body>

</html>