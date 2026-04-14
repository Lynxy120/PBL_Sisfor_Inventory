<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Riwayat Stok - Grosiran Ayah</title>
    <?php include 'includes/head.php'; ?>
</head>

<body class="sb-nav-fixed">
    <?php include 'includes/header.php'; ?>

    <div id="layoutSidenav">
        <?php include 'includes/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Riwayat Stok</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Riwayat Stok</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex gap-2 align-items-center">
                                <label class="form-label mb-0">Filter:</label>
                                <input type="date" id="startDate" class="form-control form-control-sm" style="width: auto;">
                                <span>-</span>
                                <input type="date" id="endDate" class="form-control form-control-sm" style="width: auto;">
                                <button class="btn btn-sm btn-primary" onclick="loadHistory()">
                                    <i class="fas fa-search me-1"></i> Filter
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" onclick="resetFilter()">
                                    <i class="fas fa-redo me-1"></i> Reset
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama Barang</th>
                                        <th>Tipe</th>
                                        <th>Jumlah</th>
                                        <th>Supplier</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody id="historyTableBody">
                                    <tr>
                                        <td colspan="7" class="text-center">Memuat data...</td>
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

    <?php include 'includes/scripts.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set default dates
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);

            document.getElementById('startDate').value = firstDay.toISOString().split('T')[0];
            document.getElementById('endDate').value = today.toISOString().split('T')[0];

            loadHistory();

            // Auto-refresh history setiap 30 detik
            setInterval(loadHistory, 30000);
        });

        async function loadHistory() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            try {
                let response;
                if (startDate && endDate) {
                    // Adjust end date to include the entire day (add 23:59:59)
                    const endDateTime = endDate + ' 23:59:59';
                    response = await getStockHistoryByDateRange(startDate, endDateTime);
                } else {
                    response = await getStockHistories();
                }

                if (response.status === 'success') {
                    const tbody = document.getElementById('historyTableBody');
                    const histories = response.data;

                    if (histories.length > 0) {
                        tbody.innerHTML = histories.map((h, index) => `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${formatDateTime(h.tanggal)}</td>
                                <td>${h.nama_barang || '-'}</td>
                                <td>
                                    <span class="badge ${
                                        h.tipe === 'in' ? 'bg-success' : 
                                        h.tipe === 'out' ? 'bg-danger' : 'bg-primary'
                                    }">
                                        ${h.tipe === 'in' ? 'Masuk' : h.tipe === 'out' ? 'Keluar' : 'Penyesuaian'}
                                    </span>
                                </td>
                                <td>${h.jumlah}</td>
                                <td>${h.nama_suppliers || '-'}</td>
                                <td>${h.keterangan || '-'}</td>
                            </tr>
                        `).join('');
                    } else {
                        tbody.innerHTML = '<tr><td colspan="7" class="text-center">Tidak ada data riwayat</td></tr>';
                    }
                } else {
                    const tbody = document.getElementById('historyTableBody');
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error: ' + (response.message || 'Gagal memuat data') + '</td></tr>';
                }
            } catch (error) {
                console.error('Error:', error);
                const tbody = document.getElementById('historyTableBody');
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Gagal memuat data. Pastikan Anda sudah login dan backend running.</td></tr>';
            }
        }

        function resetFilter() {
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);

            document.getElementById('startDate').value = firstDay.toISOString().split('T')[0];
            document.getElementById('endDate').value = today.toISOString().split('T')[0];

            loadHistory();
        }
    </script>
</body>

</html>