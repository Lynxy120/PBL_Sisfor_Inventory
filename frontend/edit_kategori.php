<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Kategori</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container-fluid px-4">
        <div class="row justify-content-center mt-4 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Edit Kategori</h5>
                    </div>
                    <div class="card-body">

                        <form id="formStock">

                            <!-- Nama Kategori -->
                            <div class="mb-3">
                                <label class="form-label">Nama Kategori</label>
                                <input type="text" id="nama_barang" class="form-control" placeholder="Masukkan nama kategori" required>
                            </div>
                            <!-- Tombol -->
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary" onclick="window.location.href='kategori.php'; return false;">
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