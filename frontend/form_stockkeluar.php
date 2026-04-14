<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Stok Keluar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container-fluid px-4">
        <div class="row justify-content-center mt-4 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Barang Keluar/Penjualan</h5>
                    </div>
                    <div class="card-body">

                        <form id="formStockKeluar">

                            <!-- Item Barang -->
                            <div class="mb-3">
                                <label class="form-label">Pilih Barang</label>
                                <select id="item_id" class="form-select" required>
                                    <option value="">-- Pilih Barang --</option>
                                </select>
                                <small class="text-muted">Daftar barang akan dimuat otomatis</small>
                            </div>

                            <!-- Jumlah -->
                            <div class="mb-3">
                                <label class="form-label">Jumlah Keluar</label>
                                <input type="number" id="jumlah" class="form-control" placeholder="Masukkan jumlah barang keluar" min="1" required>
                            </div>

                            <!-- Keterangan -->
                            <div class="mb-3">
                                <label class="form-label">Keterangan/Alasan</label>
                                <textarea id="keterangan" class="form-control" rows="3" placeholder="Masukkan alasan barang keluar (misal: Penjualan, Barang rusak, Retur, dll)"></textarea>
                            </div>

                            <!-- Tombol -->
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" onclick="window.location.href='kelolastock.php'; return false;">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    Simpan Stok Keluar
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
        // Load items dropdown
        async function loadItems() {
            try {
                const response = await fetch(API_BASE_URL + '/items', {
                    headers: getAuthHeaders()
                });
                const result = await response.json();
                const select = document.getElementById('item_id');

                if (result.data && Array.isArray(result.data)) {
                    result.data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = `${item.nama_barang} (Stok: ${item.stok})`;
                        select.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading items:', error);
                showError('Gagal memuat daftar barang');
            }
        }

        // Form submission
        document.getElementById('formStockKeluar').addEventListener('submit', async (e) => {
            e.preventDefault();

            if (!validateForm()) {
                showError('Mohon isi semua field yang diperlukan');
                return;
            }

            const formData = {
                item_id: parseInt(document.getElementById('item_id').value),
                jumlah: parseInt(document.getElementById('jumlah').value),
                keterangan: document.getElementById('keterangan').value || ''
            };

            try {
                const response = await fetch(API_BASE_URL + '/stock-out', {
                    method: 'POST',
                    headers: getAuthHeaders(),
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.status === 'success') {
                    showSuccess('Stok keluar berhasil dicatat!');
                    setTimeout(() => {
                        window.location.href = 'kelolastock.php';
                    }, 1500);
                } else {
                    showError(result.message || 'Gagal menyimpan stok keluar');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Terjadi kesalahan saat menyimpan data');
            }
        });

        // Validate form
        function validateForm() {
            return document.getElementById('item_id').value &&
                document.getElementById('jumlah').value > 0;
        }

        // Load data on page load
        document.addEventListener('DOMContentLoaded', () => {
            loadItems();
        });
    </script>
</body>

</html>