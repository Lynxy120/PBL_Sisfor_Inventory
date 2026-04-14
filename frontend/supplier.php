<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Data Supplier - Grosiran Ayah</title>
    <?php include 'includes/head.php'; ?>
</head>

<body class="sb-nav-fixed">
    <?php include 'includes/header.php'; ?>

    <div id="layoutSidenav">
        <?php include 'includes/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Data Supplier</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Supplier</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                                <i class="fas fa-plus me-1"></i> Tambah Supplier
                            </button>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Supplier</th>
                                        <th>Kontak</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="suppliersTableBody">
                                    <tr>
                                        <td colspan="4" class="text-center">Memuat data...</td>
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

    <!-- Add Supplier Modal -->
    <div class="modal fade" id="addSupplierModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addSupplierForm">
                        <div class="mb-3">
                            <label for="supplierName" class="form-label">Nama Supplier</label>
                            <input type="text" class="form-control" id="supplierName" required>
                        </div>
                        <div class="mb-3">
                            <label for="supplierContact" class="form-label">Kontak</label>
                            <input type="text" class="form-control" id="supplierContact">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveSupplier()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Supplier Modal -->
    <div class="modal fade" id="editSupplierModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editSupplierForm">
                        <input type="hidden" id="editSupplierId">
                        <div class="mb-3">
                            <label for="editSupplierName" class="form-label">Nama Supplier</label>
                            <input type="text" class="form-control" id="editSupplierName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editSupplierContact" class="form-label">Kontak</label>
                            <input type="text" class="form-control" id="editSupplierContact">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="updateSupplierData()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/scripts.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadSuppliers();
        });

        async function loadSuppliers() {
            try {
                const response = await getSuppliers();
                if (response.status === 'success') {
                    const tbody = document.getElementById('suppliersTableBody');
                    const suppliers = response.data;

                    if (suppliers.length > 0) {
                        tbody.innerHTML = suppliers.map((sup, index) => `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${sup.nama_suppliers}</td>
                                <td>${sup.kontak || '-'}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editSupplier(${sup.id}, '${sup.nama_suppliers}', '${sup.kontak || ''}')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteSupplierData(${sup.id})">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        `).join('');
                    } else {
                        tbody.innerHTML = '<tr><td colspan="4" class="text-center">Belum ada data supplier</td></tr>';
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Gagal memuat data supplier', 'danger');
            }
        }

        async function saveSupplier() {
            const name = document.getElementById('supplierName').value;
            const contact = document.getElementById('supplierContact').value;

            if (!name) {
                showAlert('Nama supplier harus diisi', 'warning');
                return;
            }

            try {
                const response = await createSupplier({
                    nama_suppliers: name,
                    kontak: contact
                });
                if (response.status === 'success') {
                    showAlert('Supplier berhasil ditambahkan', 'success');
                    document.getElementById('supplierName').value = '';
                    document.getElementById('supplierContact').value = '';
                    bootstrap.Modal.getInstance(document.getElementById('addSupplierModal')).hide();
                    loadSuppliers();
                }
            } catch (error) {
                showAlert('Gagal menambahkan supplier', 'danger');
            }
        }

        function editSupplier(id, name, contact) {
            document.getElementById('editSupplierId').value = id;
            document.getElementById('editSupplierName').value = name;
            document.getElementById('editSupplierContact').value = contact;
            new bootstrap.Modal(document.getElementById('editSupplierModal')).show();
        }

        async function updateSupplierData() {
            const id = document.getElementById('editSupplierId').value;
            const name = document.getElementById('editSupplierName').value;
            const contact = document.getElementById('editSupplierContact').value;

            try {
                const response = await updateSupplier(id, {
                    nama_suppliers: name,
                    kontak: contact
                });
                if (response.status === 'success') {
                    showAlert('Supplier berhasil diperbarui', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('editSupplierModal')).hide();
                    loadSuppliers();
                }
            } catch (error) {
                showAlert('Gagal memperbarui supplier', 'danger');
            }
        }

        async function deleteSupplierData(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus supplier ini?')) return;

            try {
                const response = await deleteSupplier(id);
                if (response.status === 'success') {
                    showAlert('Supplier berhasil dihapus', 'success');
                    loadSuppliers();
                }
            } catch (error) {
                showAlert('Gagal menghapus supplier', 'danger');
            }
        }
    </script>
</body>

</html>