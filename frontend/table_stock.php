<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Data Barang - Grosiran Ayah</title>
    <?php include 'includes/head.php'; ?>
</head>

<body class="sb-nav-fixed">
    <?php include 'includes/header.php'; ?>

    <div id="layoutSidenav">
        <?php include 'includes/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Data Barang</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Barang</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                                <i class="fas fa-plus me-1"></i> Tambah Barang
                            </button>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Foto</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Harga Beli</th>
                                        <th>Harga Jual</th>
                                        <th>Stok</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsTableBody">
                                    <tr>
                                        <td colspan="9" class="text-center">Memuat data...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>

    <!-- Add Item Modal -->
    <div class="modal fade" id="addItemModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addItemForm" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="itemName" class="form-label">Nama Barang</label>
                                <input type="text" class="form-control" id="itemName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="itemCategory" class="form-label">Kategori</label>
                                <select class="form-select" id="itemCategory" required>
                                    <option value="">Pilih Kategori...</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="itemBuyPrice" class="form-label">Harga Beli</label>
                                <input type="number" class="form-control" id="itemBuyPrice" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="itemSellPrice" class="form-label">Harga Jual</label>
                                <input type="number" class="form-control" id="itemSellPrice" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="itemStock" class="form-label">Stok Awal</label>
                                <input type="number" class="form-control" id="itemStock" value="0">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="itemPhoto" class="form-label">Foto Produk</label>
                            <input type="file" class="form-control" id="itemPhoto" accept="image/*">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveItem()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div class="modal fade" id="editItemModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editItemForm" enctype="multipart/form-data">
                        <input type="hidden" id="editItemId">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editItemName" class="form-label">Nama Barang</label>
                                <input type="text" class="form-control" id="editItemName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editItemCategory" class="form-label">Kategori</label>
                                <select class="form-select" id="editItemCategory" required>
                                    <option value="">Pilih Kategori...</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="editItemBuyPrice" class="form-label">Harga Beli</label>
                                <input type="number" class="form-control" id="editItemBuyPrice" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editItemSellPrice" class="form-label">Harga Jual</label>
                                <input type="number" class="form-control" id="editItemSellPrice" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editItemStock" class="form-label">Stok</label>
                                <input type="number" class="form-control" id="editItemStock" value="0">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editItemPhoto" class="form-label">Foto Produk (Kosongkan jika tidak ingin mengubah)</label>
                            <input type="file" class="form-control" id="editItemPhoto" accept="image/*">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="updateItemData()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/scripts.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadCategories();
            loadItems();
        });

        async function loadCategories() {
            try {
                const response = await getCategories();
                if (response.status === 'success') {
                    const categories = response.data;
                    const options = categories.map(cat =>
                        `<option value="${cat.id}">${cat.nama_kategori}</option>`
                    ).join('');

                    document.getElementById('itemCategory').innerHTML =
                        '<option value="">Pilih Kategori...</option>' + options;
                    document.getElementById('editItemCategory').innerHTML =
                        '<option value="">Pilih Kategori...</option>' + options;
                }
            } catch (error) {
                console.error('Error loading categories:', error);
            }
        }

        async function loadItems() {
            try {
                const response = await getItems();
                if (response.status === 'success') {
                    const tbody = document.getElementById('itemsTableBody');
                    const items = response.data;

                    if (items.length > 0) {
                        tbody.innerHTML = items.map((item, index) => `
                            <tr>
                                <td>${index + 1}</td>
                                <td>
                                    ${item.foto ? `<img src="${API_BASE_URL}/uploads/${item.foto}" alt="foto" style="width:48px;height:48px;object-fit:cover;border-radius:6px;">` : '<span class="text-muted">-</span>'}
                                </td>
                                <td>${item.nama_barang}</td>
                                <td>${item.nama_kategori || '-'}</td>
                                <td>${formatCurrency(item.harga_beli)}</td>
                                <td>${formatCurrency(item.harga_jual)}</td>
                                <td>${item.stok}</td>
                                <td>
                                    ${item.stok < 10 
                                        ? '<span class="badge bg-danger">Stok Rendah</span>' 
                                        : '<span class="badge bg-success">Tersedia</span>'}
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editItem(${JSON.stringify(item).replace(/"/g, '&quot;')})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteItemData(${item.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `).join('');
                    } else {
                        tbody.innerHTML = '<tr><td colspan="9" class="text-center">Belum ada data barang</td></tr>';
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Gagal memuat data barang', 'danger');
            }
        }

        async function saveItem() {
            const formData = new FormData();
            formData.append('nama_barang', document.getElementById('itemName').value);
            formData.append('kategori_id', document.getElementById('itemCategory').value);
            formData.append('harga_beli', document.getElementById('itemBuyPrice').value);
            formData.append('harga_jual', document.getElementById('itemSellPrice').value);
            formData.append('stok', document.getElementById('itemStock').value || 0);

            const photoInput = document.getElementById('itemPhoto');
            if (photoInput.files.length > 0) {
                formData.append('foto', photoInput.files[0]);
            }

            try {
                const response = await createItem(formData);
                if (response.status === 'success') {
                    showAlert('Barang berhasil ditambahkan', 'success');
                    document.getElementById('addItemForm').reset();
                    bootstrap.Modal.getInstance(document.getElementById('addItemModal')).hide();
                    loadItems();
                }
            } catch (error) {
                showAlert('Gagal menambahkan barang', 'danger');
            }
        }

        async function editItem(item) {
            // Pastikan kategori sudah terisi sebelum set value
            await loadCategories();
            document.getElementById('editItemId').value = item.id;
            document.getElementById('editItemName').value = item.nama_barang;
            document.getElementById('editItemCategory').value = item.kategori_id;
            document.getElementById('editItemBuyPrice').value = item.harga_beli;
            document.getElementById('editItemSellPrice').value = item.harga_jual;
            document.getElementById('editItemStock').value = item.stok;
            new bootstrap.Modal(document.getElementById('editItemModal')).show();
        }

        async function updateItemData() {
            const id = document.getElementById('editItemId').value;
            const formData = new FormData();
            formData.append('nama_barang', document.getElementById('editItemName').value);
            formData.append('kategori_id', document.getElementById('editItemCategory').value);
            formData.append('harga_beli', document.getElementById('editItemBuyPrice').value);
            formData.append('harga_jual', document.getElementById('editItemSellPrice').value);
            formData.append('stok', document.getElementById('editItemStock').value || 0);
            const photoInput = document.getElementById('editItemPhoto');
            if (photoInput && photoInput.files.length > 0) {
                formData.append('foto', photoInput.files[0]);
            }
            try {
                const response = await updateItem(id, formData);
                if (response.status === 'success') {
                    showAlert('Barang berhasil diperbarui', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('editItemModal')).hide();
                    loadItems();
                } else {
                    showAlert(response.message || 'Gagal memperbarui barang', 'danger');
                }
            } catch (error) {
                if (error && error.message) {
                    showAlert(error.message, 'danger');
                } else {
                    showAlert('Gagal memperbarui barang', 'danger');
                }
            }
        }

        async function deleteItemData(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus barang ini?')) return;
            try {
                const response = await deleteItem(id);
                if (response.status === 'success') {
                    showAlert('Barang berhasil dihapus', 'success');
                    loadItems();
                } else {
                    showAlert(response.message || 'Gagal menghapus barang', 'danger');
                }
            } catch (error) {
                showAlert('Gagal menghapus barang', 'danger');
            }
        }
    </script>
</body>

</html>