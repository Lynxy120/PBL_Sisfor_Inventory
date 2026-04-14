<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kelola Stok - Grosiran Ayah</title>
    <?php include 'includes/head.php'; ?>
</head>

<body class="sb-nav-fixed">
    <?php include 'includes/header.php'; ?>

    <div id="layoutSidenav">
        <?php include 'includes/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <div class="mt-4 row">
                        <!-- ===== FORM ===== -->
                        <div class="col-lg-5">
                            <div class="card card-custom">
                                <div class="card-body">
                                    <h6 class="mb-3 fw-semibold">Form Pergerakan Stok</h6>
                                    <form id="formStock">

                                        <!-- BARANG -->
                                        <div class="mb-3">
                                            <label class="form-label">Pilih Barang</label>
                                            <select id="barang" class="form-select" required>
                                                <option value="">Pilih Barang...</option>
                                            </select>
                                            <div class="mt-2 small text-muted" id="currentStock">Stok saat ini: -</div>
                                        </div>

                                        <!-- TIPE TRANSAKSI -->
                                        <div class="mb-3">
                                            <label class="form-label">Tipe Transaksi</label>
                                            <div class="d-flex gap-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tipe" value="in" id="tipeMasuk" checked>
                                                    <label class="form-check-label text-success" for="tipeMasuk">Stok Masuk</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tipe" value="out" id="tipeKeluar">
                                                    <label class="form-check-label text-danger" for="tipeKeluar">Stok Keluar</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="tipe" value="adjust" id="tipePenyesuaian">
                                                    <label class="form-check-label text-primary" for="tipePenyesuaian">Penyesuaian</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- STOK MASUK -->
                                        <div id="form-masuk" class="stok-form">
                                            <div class="mb-3">
                                                <label class="form-label">Supplier (Opsional)</label>
                                                <select id="supplierSelect" class="form-select">
                                                    <option value="">Pilih Supplier...</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Jumlah Masuk</label>
                                                <input type="number" class="form-control" id="jumlahMasuk" min="1">
                                            </div>
                                        </div>

                                        <!-- STOK KELUAR -->
                                        <div id="form-keluar" class="stok-form" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label">Jumlah Keluar</label>
                                                <input type="number" class="form-control" id="jumlahKeluar" min="1">
                                            </div>
                                        </div>

                                        <!-- PENYESUAIAN -->
                                        <div id="form-penyesuaian" class="stok-form" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label">Stok Setelah Penyesuaian</label>
                                                <input type="number" class="form-control" id="stokBaru" min="0">
                                            </div>
                                        </div>

                                        <!-- KETERANGAN -->
                                        <div class="mb-3">
                                            <label class="form-label">Keterangan</label>
                                            <textarea id="keterangan" class="form-control" rows="3"
                                                placeholder="Alasan stok masuk / keluar / penyesuaian"></textarea>
                                        </div>

                                        <!-- SUBMIT -->
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
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody id="riwayatTable">
                                                <tr>
                                                    <td colspan="5" class="text-center">Memuat data...</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="text-center">
                                        <a href="riwayatstock.php" class="btn btn-outline-secondary btn-sm">
                                            Lihat Semua Riwayat
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>

    <?php include 'includes/scripts.php'; ?>

    <script>
        let itemsData = [];

        document.addEventListener('DOMContentLoaded', function() {
            loadItemsForSelect();
            loadSuppliers();
            loadRecentHistory();

            // Handle transaction type change
            document.querySelectorAll('input[name="tipe"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    document.querySelectorAll('.stok-form').forEach(form => form.style.display = 'none');

                    if (this.value === 'in') {
                        document.getElementById('form-masuk').style.display = 'block';
                    } else if (this.value === 'out') {
                        document.getElementById('form-keluar').style.display = 'block';
                    } else if (this.value === 'adjust') {
                        document.getElementById('form-penyesuaian').style.display = 'block';
                    }
                });
            });

            // Handle item selection
            document.getElementById('barang').addEventListener('change', function() {
                const selectedItem = itemsData.find(item => item.id == this.value);
                if (selectedItem) {
                    document.getElementById('currentStock').textContent = `Stok saat ini: ${selectedItem.stok}`;
                } else {
                    document.getElementById('currentStock').textContent = 'Stok saat ini: -';
                }
            });

            // Handle form submit
            document.getElementById('formStock').addEventListener('submit', async function(e) {
                e.preventDefault();
                await submitStockTransaction();
            });
        });

        async function loadItemsForSelect() {
            try {
                const response = await getItems();
                if (response.status === 'success') {
                    itemsData = response.data;
                    const select = document.getElementById('barang');
                    select.innerHTML = '<option value="">Pilih Barang...</option>' +
                        itemsData.map(item =>
                            `<option value="${item.id}">${item.nama_barang} (Stok: ${item.stok})</option>`
                        ).join('');
                }
            } catch (error) {
                console.error('Error loading items:', error);
            }
        }

        async function loadSuppliers() {
            try {
                const response = await getSuppliers();
                if (response.status === 'success') {
                    const select = document.getElementById('supplierSelect');
                    select.innerHTML = '<option value="">Pilih Supplier...</option>' +
                        response.data.map(sup =>
                            `<option value="${sup.id}">${sup.nama_suppliers}</option>`
                        ).join('');
                }
            } catch (error) {
                console.error('Error loading suppliers:', error);
            }
        }

        async function loadRecentHistory() {
            try {
                const response = await getStockHistories();
                if (response.status === 'success') {
                    const tbody = document.getElementById('riwayatTable');
                    const histories = response.data.slice(0, 10);

                    if (histories.length > 0) {
                        tbody.innerHTML = histories.map(h => `
                            <tr>
                                <td>${formatDateTime(h.tanggal)}</td>
                                <td>${h.nama_barang || '-'}</td>
                                <td class="${h.tipe === 'in' ? 'text-success' : h.tipe === 'out' ? 'text-danger' : 'text-primary'} fw-semibold">
                                    ${h.tipe === 'in' ? 'Masuk' : h.tipe === 'out' ? 'Keluar' : 'Penyesuaian'}
                                </td>
                                <td>${h.jumlah}</td>
                                <td>${h.keterangan || '-'}</td>
                            </tr>
                        `).join('');
                    } else {
                        tbody.innerHTML = '<tr><td colspan="5" class="text-center">Belum ada riwayat</td></tr>';
                    }
                }
            } catch (error) {
                console.error('Error loading history:', error);
            }
        }

        async function submitStockTransaction() {
            const itemId = document.getElementById('barang').value;
            const tipe = document.querySelector('input[name="tipe"]:checked').value;
            const keterangan = document.getElementById('keterangan').value;

            if (!itemId) {
                showAlert('Pilih barang terlebih dahulu', 'warning');
                return;
            }

            try {
                let response;

                if (tipe === 'in') {
                    const jumlah = document.getElementById('jumlahMasuk').value;
                    const supplierId = document.getElementById('supplierSelect').value;

                    if (!jumlah || jumlah <= 0) {
                        showAlert('Masukkan jumlah yang valid', 'warning');
                        return;
                    }

                    response = await stockIn({
                        item_id: parseInt(itemId),
                        jumlah: parseInt(jumlah),
                        supplier_id: supplierId ? parseInt(supplierId) : null,
                        keterangan: keterangan
                    });

                } else if (tipe === 'out') {
                    const jumlah = document.getElementById('jumlahKeluar').value;

                    if (!jumlah || jumlah <= 0) {
                        showAlert('Masukkan jumlah yang valid', 'warning');
                        return;
                    }

                    response = await stockOut({
                        item_id: parseInt(itemId),
                        jumlah: parseInt(jumlah),
                        keterangan: keterangan
                    });

                } else if (tipe === 'adjust') {
                    const stokBaru = document.getElementById('stokBaru').value;

                    if (stokBaru === '' || stokBaru < 0) {
                        showAlert('Masukkan stok baru yang valid', 'warning');
                        return;
                    }

                    response = await stockAdjust({
                        item_id: parseInt(itemId),
                        stok_baru: parseInt(stokBaru),
                        keterangan: keterangan
                    });
                }

                if (response.status === 'success') {
                    showAlert(response.message || 'Transaksi stok berhasil', 'success');
                    document.getElementById('formStock').reset();
                    document.getElementById('currentStock').textContent = 'Stok saat ini: -';
                    loadItemsForSelect();
                    loadRecentHistory();
                }

            } catch (error) {
                showAlert('Gagal menyimpan transaksi stok: ' + error.message, 'danger');
            }
        }
    </script>
</body>

</html>