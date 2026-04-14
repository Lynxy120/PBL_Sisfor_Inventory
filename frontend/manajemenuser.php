<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manajemen User - Grosiran Ayah</title>
    <?php include 'includes/head.php'; ?>
</head>

<body class="sb-nav-fixed">
    <?php include 'includes/header.php'; ?>

    <div id="layoutSidenav">
        <?php include 'includes/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Manajemen User</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manajemen User</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                <i class="fas fa-plus me-1"></i> Tambah User
                            </button>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Lengkap</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Dibuat Pada</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="usersTableBody">
                                    <tr>
                                        <td colspan="6" class="text-center">Memuat data...</td>
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

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        <div class="mb-3">
                            <label for="userName" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="userName" required>
                        </div>
                        <div class="mb-3">
                            <label for="userUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="userUsername" required>
                        </div>
                        <div class="mb-3">
                            <label for="userPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="userPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="userRole" class="form-label">Role</label>
                            <select class="form-select" id="userRole" required>
                                <option value="">Pilih Role...</option>
                                <option value="super_admin">Super Admin</option>
                                <option value="admin_gudang">Admin Gudang</option>
                                <option value="sales">Sales</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveUser()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <input type="hidden" id="editUserId">
                        <div class="mb-3">
                            <label for="editUserName" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="editUserName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUserUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="editUserUsername" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUserRole" class="form-label">Role</label>
                            <select class="form-select" id="editUserRole" required>
                                <option value="super_admin">Super Admin</option>
                                <option value="admin_gudang">Admin Gudang</option>
                                <option value="sales">Sales</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="updateUserData()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/scripts.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadUsers();
        });

        function getRoleBadge(role) {
            const badges = {
                'super_admin': '<span class="badge bg-danger">Super Admin</span>',
                'admin_gudang': '<span class="badge bg-primary">Admin Gudang</span>',
                'sales': '<span class="badge bg-success">Sales</span>'
            };
            return badges[role] || `<span class="badge bg-secondary">${role}</span>`;
        }

        async function loadUsers() {
            try {
                const response = await getUsers();
                if (response.status === 'success') {
                    const tbody = document.getElementById('usersTableBody');
                    const users = response.data;

                    if (users.length > 0) {
                        tbody.innerHTML = users.map((user, index) => `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${user.nama}</td>
                                <td>${user.username}</td>
                                <td>${getRoleBadge(user.role)}</td>
                                <td>${formatDate(user.created_at)}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editUser(${user.id}, '${user.nama}', '${user.username}', '${user.role}')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteUserData(${user.id})">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        `).join('');
                    } else {
                        tbody.innerHTML = '<tr><td colspan="6" class="text-center">Belum ada data user</td></tr>';
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                const tbody = document.getElementById('usersTableBody');
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Gagal memuat data user. Pastikan Anda login sebagai Super Admin.</td></tr>';
            }
        }

        async function saveUser() {
            const nama = document.getElementById('userName').value;
            const username = document.getElementById('userUsername').value;
            const password = document.getElementById('userPassword').value;
            const role = document.getElementById('userRole').value;

            if (!nama || !username || !password || !role) {
                showAlert('Semua field harus diisi', 'warning');
                return;
            }

            try {
                const response = await createUser({
                    nama,
                    username,
                    password,
                    role
                });
                if (response.status === 'success') {
                    showAlert('User berhasil ditambahkan', 'success');
                    document.getElementById('addUserForm').reset();
                    bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
                    loadUsers();
                }
            } catch (error) {
                showAlert('Gagal menambahkan user', 'danger');
            }
        }

        function editUser(id, nama, username, role) {
            document.getElementById('editUserId').value = id;
            document.getElementById('editUserName').value = nama;
            document.getElementById('editUserUsername').value = username;
            document.getElementById('editUserRole').value = role;
            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        }

        async function updateUserData() {
            const id = document.getElementById('editUserId').value;
            const nama = document.getElementById('editUserName').value;
            const username = document.getElementById('editUserUsername').value;
            const role = document.getElementById('editUserRole').value;

            try {
                const response = await updateUser(id, {
                    nama,
                    username,
                    role
                });
                if (response.status === 'success') {
                    showAlert('User berhasil diperbarui', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
                    loadUsers();
                }
            } catch (error) {
                showAlert('Gagal memperbarui user', 'danger');
            }
        }

        async function deleteUserData(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus user ini?')) return;

            try {
                const response = await deleteUser(id);
                if (response.status === 'success') {
                    showAlert('User berhasil dihapus', 'success');
                    loadUsers();
                }
            } catch (error) {
                showAlert('Gagal menghapus user', 'danger');
            }
        }
    </script>
</body>

</html>