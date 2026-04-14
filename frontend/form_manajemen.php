<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User - Grosiran Ayah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="bg-light">
    <div class="container-fluid">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">
                            <i class="fas fa-user-plus me-2"></i>Tambah User Baru
                        </h5>
                        <a href="manajemenuser.php" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                    <div class="card-body p-4">
                        <form id="formUser">
                            <!-- Alert Container -->
                            <div id="alertContainer"></div>

                            <!-- Nama Lengkap -->
                            <div class="mb-3">
                                <label for="nama" class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" placeholder="Masukkan nama lengkap" required>
                                <small class="text-muted">Nama lengkap user yang akan ditampilkan di sistem</small>
                            </div>

                            <!-- Username -->
                            <div class="mb-3">
                                <label for="username" class="form-label fw-semibold">Username</label>
                                <input type="text" class="form-control" id="username" placeholder="Masukkan username (untuk login)" required>
                                <small class="text-muted">Username unik untuk login, gunakan huruf dan angka</small>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <input type="password" class="form-control" id="password" placeholder="Masukkan password (minimal 8 karakter)" required>
                                <small class="text-muted">Password aman dengan minimal 8 karakter</small>
                            </div>

                            <!-- Role -->
                            <div class="mb-4">
                                <label for="role" class="form-label fw-semibold">Role/Hak Akses</label>
                                <select id="role" class="form-select" required>
                                    <option value="">-- Pilih Role --</option>
                                    <option value="admin_gudang">📦 Admin Gudang (Manage Stok)</option>
                                    <option value="sales">💼 Sales/Kasir (Transaksi)</option>
                                </select>
                                <small class="text-muted">Tentukan role/hak akses user di sistem</small>
                            </div>

                            <!-- Tombol -->
                            <div class="d-grid gap-2 d-sm-flex justify-content-sm-end">
                                <a href="manajemenuser.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Simpan User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/api.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('formUser').addEventListener('submit', async function(e) {
                e.preventDefault();
                await saveUser();
            });
        });

        async function saveUser() {
            const nama = document.getElementById('nama').value.trim();
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            const role = document.getElementById('role').value;
            const alertContainer = document.getElementById('alertContainer');

            // Validation
            if (!nama) {
                showAlert('Nama lengkap harus diisi', 'danger');
                return;
            }
            if (!username) {
                showAlert('Username harus diisi', 'danger');
                return;
            }
            if (username.length < 3) {
                showAlert('Username minimal 3 karakter', 'danger');
                return;
            }
            if (!password) {
                showAlert('Password harus diisi', 'danger');
                return;
            }
            if (password.length < 8) {
                showAlert('Password minimal 8 karakter', 'danger');
                return;
            }
            if (!role) {
                showAlert('Role harus dipilih', 'danger');
                return;
            }

            try {
                console.log('Creating user:', {
                    nama,
                    username,
                    role
                });
                console.log('Auth headers:', getAuthHeaders());

                const response = await createUser({
                    nama,
                    username,
                    password,
                    role
                });

                console.log('API Response:', response);

                if (response.status === 'success') {
                    showAlert('✅ User berhasil ditambahkan! Mengalihkan...', 'success');
                    setTimeout(() => {
                        window.location.href = 'manajemenuser.php';
                    }, 1500);
                } else {
                    showAlert('❌ Error: ' + (response.message || 'Gagal menambahkan user'), 'danger');
                }
            } catch (error) {
                console.error('Full error:', error);
                showAlert('❌ Gagal menambahkan user: ' + error.message, 'danger');
            }
        }
    </script>
</body>

</html>