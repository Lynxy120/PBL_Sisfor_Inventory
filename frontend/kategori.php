<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Data Kategori - Grosiran Ayah</title>
    <?php include 'includes/head.php'; ?>
</head>

<body class="sb-nav-fixed">
    <?php include 'includes/header.php'; ?>

    <div id="layoutSidenav">
        <?php include 'includes/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Data Kategori</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Kategori</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                <i class="fas fa-plus me-1"></i> Tambah Kategori
                            </button>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kategori</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="categoriesTableBody">
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

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addCategoryForm">
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="categoryName" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveCategory()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editCategoryForm">
                        <input type="hidden" id="editCategoryId">
                        <div class="mb-3">
                            <label for="editCategoryName" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="editCategoryName" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="updateCategoryData()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/scripts.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadCategories();
        });

        async function loadCategories() {
            try {
                const response = await getCategories();
                if (response.status === 'success') {
                    const tbody = document.getElementById('categoriesTableBody');
                    const categories = response.data;

                    if (categories.length > 0) {
                        tbody.innerHTML = categories.map((cat, index) => `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${cat.nama_kategori}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editCategory(${cat.id}, '${cat.nama_kategori}')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteCategoryData(${cat.id})">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        `).join('');
                    } else {
                        tbody.innerHTML = '<tr><td colspan="3" class="text-center">Belum ada data kategori</td></tr>';
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Gagal memuat data kategori', 'danger');
            }
        }

        async function saveCategory() {
            const name = document.getElementById('categoryName').value;
            if (!name) {
                showAlert('Nama kategori harus diisi', 'warning');
                return;
            }

            try {
                const response = await createCategory({
                    nama_kategori: name
                });
                if (response.status === 'success') {
                    showAlert('Kategori berhasil ditambahkan', 'success');
                    document.getElementById('categoryName').value = '';
                    bootstrap.Modal.getInstance(document.getElementById('addCategoryModal')).hide();
                    loadCategories();
                }
            } catch (error) {
                showAlert('Gagal menambahkan kategori', 'danger');
            }
        }

        function editCategory(id, name) {
            document.getElementById('editCategoryId').value = id;
            document.getElementById('editCategoryName').value = name;
            new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
        }

        async function updateCategoryData() {
            const id = document.getElementById('editCategoryId').value;
            const name = document.getElementById('editCategoryName').value;

            try {
                const response = await updateCategory(id, {
                    nama_kategori: name
                });
                if (response.status === 'success') {
                    showAlert('Kategori berhasil diperbarui', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('editCategoryModal')).hide();
                    loadCategories();
                }
            } catch (error) {
                showAlert('Gagal memperbarui kategori', 'danger');
            }
        }

        async function deleteCategoryData(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus kategori ini?')) return;

            try {
                const response = await deleteCategory(id);
                if (response.status === 'success') {
                    showAlert('Kategori berhasil dihapus', 'success');
                    loadCategories();
                }
            } catch (error) {
                showAlert('Gagal menghapus kategori', 'danger');
            }
        }
    </script>
</body>

</html>