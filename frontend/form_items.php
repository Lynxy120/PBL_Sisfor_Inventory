<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container-fluid px-4">
        <div class="row justify-content-center mt-4 mb-4">
            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Tambah Barang Baru</h5>
                    </div>
                    <div class="card-body">

                        <form id="formItems" enctype="multipart/form-data">

                            <!-- Kategori -->
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select id="kategori_id" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                </select>
                                <small class="text-muted">Daftar kategori akan dimuat otomatis</small>
                            </div>

                            <!-- Nama Barang -->
                            <div class="mb-3">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" id="nama_barang" class="form-control" placeholder="Masukkan nama barang" required>
                            </div>

                            <!-- Harga Beli -->
                            <div class="mb-3">
                                <label class="form-label">Harga Beli (Rp)</label>
                                <input type="number" id="harga_beli" class="form-control" placeholder="Masukkan harga beli" min="0" required>
                            </div>

                            <!-- Harga Jual -->
                            <div class="mb-3">
                                <label class="form-label">Harga Jual (Rp)</label>
                                <input type="number" id="harga_jual" class="form-control" placeholder="Masukkan harga jual" min="0" required>
                            </div>

                            <!-- Stok Awal -->
                            <div class="mb-3">
                                <label class="form-label">Stok Awal</label>
                                <input type="number" id="stok" class="form-control" placeholder="Masukkan stok awal barang" min="0" required>
                            </div>

                            <!-- Foto -->
                            <div class="mb-3">
                                <label class="form-label">Foto Produk</label>
                                <input type="file" id="foto" class="form-control" accept="image/*">
                                <small class="text-muted">Format: JPG, PNG, GIF. Ukuran maksimal: 2MB</small>
                            </div>

                            <!-- Tombol -->
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" onclick="window.location.href='table_stock.php'; return false;">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    Simpan Barang
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
    <script src="js/validation.js"></script>
    <script>
        // Load categories dropdown
        async function loadCategories() {
            try {
                const response = await fetch(API_BASE_URL + '/categories', {
                    headers: getAuthHeaders()
                });
                const result = await response.json();
                const select = document.getElementById('kategori_id');

                if (result.data && Array.isArray(result.data)) {
                    result.data.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.id;
                        option.textContent = category.nama_kategori;
                        select.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading categories:', error);
                showError('Gagal memuat daftar kategori');
            }
        }

        // Form submission
        document.getElementById('formItems').addEventListener('submit', async (e) => {
            e.preventDefault();

            if (!validateForm()) {
                showError('Mohon isi semua field yang diperlukan');
                return;
            }

            const formData = new FormData();
            formData.append('kategori_id', document.getElementById('kategori_id').value);
            formData.append('nama_barang', document.getElementById('nama_barang').value);
            formData.append('harga_beli', document.getElementById('harga_beli').value);
            formData.append('harga_jual', document.getElementById('harga_jual').value);
            formData.append('stok', document.getElementById('stok').value);

            const fotoFile = document.getElementById('foto').files[0];
            if (fotoFile) {
                // Validate file size (max 2MB)
                if (fotoFile.size > 2 * 1024 * 1024) {
                    showError('Ukuran file foto tidak boleh lebih dari 2MB');
                    return;
                }
                formData.append('foto', fotoFile);
            }

            try {
                const response = await fetch(API_BASE_URL + '/items', {
                    method: 'POST',
                    headers: {
                        'X-User-Id': localStorage.getItem('user_id'),
                        'X-User-Role': localStorage.getItem('user_role')
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    showSuccess('Barang berhasil ditambahkan!');
                    setTimeout(() => {
                        window.location.href = 'table_stock.php';
                    }, 1500);
                } else {
                    showError(result.message || 'Gagal menambahkan barang');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Terjadi kesalahan saat menyimpan data');
            }
        });

        // Validate form
        function validateForm() {
            return document.getElementById('kategori_id').value &&
                document.getElementById('nama_barang').value &&
                document.getElementById('harga_beli').value > 0 &&
                document.getElementById('harga_jual').value > 0 &&
                document.getElementById('stok').value >= 0;
        }

        // Load data on page load
        document.addEventListener('DOMContentLoaded', () => {
            loadCategories();
        });
    </script>
</body>

</html>