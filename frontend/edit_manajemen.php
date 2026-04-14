<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Manajemen User</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container-fluid px-4">
        <div class="row justify-content-center mt-4 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Tambah Manajemen User</h5>
                    </div>
                    <div class="card-body">

                        <form id="formStock">

                            <!-- Nama Supplier -->
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" id="nama_barang" class="form-control" placeholder="Masukkan nama barang" required>
                            </div>

                            <!-- Kontak Supplier -->
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" id="kontak_supplier" class="form-control" placeholder="Masukkan kontak supplier" required>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" id="password" class="form-control" placeholder="Masukkan password" required>
                            </div>

                            <!-- Role -->
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <select id="role" class="form-select" required>
                                    <option value="">-- Pilih Role --</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Super Admin">Super Admin</option>
                                    <option value="Kasir">Kasir</option>
                                </select>
                            </div>

                            <!-- Tombol -->
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary" onclick="window.location.href='manajemenuser.php'; return false;">
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