<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Laporan & Analisis - Grosiran Ayah</title>
    <?php include 'includes/head.php'; ?>
</head>

<body class="sb-nav-fixed">
    <?php include 'includes/header.php'; ?>

    <div id="layoutSidenav">
        <?php include 'includes/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Laporan & Analisis</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Laporan</li>
                    </ol>

                    <!-- Summary Cards -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="small">Total Transaksi</div>
                                            <h3 class="mb-0" id="totalTransactions">-</h3>
                                        </div>
                                        <i class="fas fa-shopping-cart fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="small">Total Penjualan</div>
                                            <h3 class="mb-0" id="totalSales">-</h3>
                                        </div>
                                        <i class="fas fa-dollar-sign fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-warning text-dark mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="small">Stok Rendah</div>
                                            <h3 class="mb-0" id="lowStockItems">-</h3>
                                        </div>
                                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-info text-white mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="small">Total Barang</div>
                                            <h3 class="mb-0" id="totalItems">-</h3>
                                        </div>
                                        <i class="fas fa-box fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tables -->
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-trophy me-1"></i> Produk Terlaris</span>
                                    <div class="d-flex gap-2">
                                        <input type="date" id="reportStartDate" class="form-control form-control-sm" style="width: auto;">
                                        <input type="date" id="reportEndDate" class="form-control form-control-sm" style="width: auto;">
                                        <button class="btn btn-sm btn-primary" onclick="loadTopSelling()">Filter</button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table id="datatablesSimple">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Barang</th>
                                                <th>Kategori</th>
                                                <th>Jumlah Terjual</th>
                                                <th>Total Pendapatan</th>
                                            </tr>
                                        </thead>
                                        <tbody id="topSellingTableBody">
                                            <tr>
                                                <td colspan="5" class="text-center">Memuat data...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Low Stock Warning -->
                        <div class="col-lg-4">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <i class="fas fa-exclamation-circle me-1"></i> Peringatan Stok Rendah
                                </div>
                                <div class="card-body p-0">
                                    <div id="lowStockList" class="list-group list-group-flush">
                                        <div class="p-4 text-center text-muted">
                                            Memuat data...
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-white text-center">
                                    <a href="kelolastock.php" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-plus me-1"></i> Tambah Stok Sekarang
                                    </a>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set default dates
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);

            document.getElementById('reportStartDate').value = firstDay.toISOString().split('T')[0];
            document.getElementById('reportEndDate').value = today.toISOString().split('T')[0];

            loadReportData();
        });

        async function loadReportData() {
            await Promise.all([
                loadSalesReport(),
                loadTopSelling(),
                loadLowStock(),
                loadItemsCount()
            ]);
        }

        async function loadSalesReport() {
            try {
                const startDate = document.getElementById('reportStartDate').value;
                const endDate = document.getElementById('reportEndDate').value;

                const response = await getSalesReport(startDate, endDate);
                if (response.status === 'success') {
                    const data = response.data;
                    let totalTransactions = 0;
                    let totalSales = 0;

                    data.forEach(d => {
                        totalTransactions += parseInt(d.total_transaksi);
                        totalSales += parseFloat(d.total_penjualan);
                    });

                    document.getElementById('totalTransactions').textContent = totalTransactions.toLocaleString('id-ID');
                    document.getElementById('totalSales').textContent = formatCurrency(totalSales);
                }
            } catch (error) {
                console.error('Error loading sales report:', error);
                document.getElementById('totalTransactions').textContent = '0';
                document.getElementById('totalSales').textContent = formatCurrency(0);
            }
        }

        async function loadTopSelling() {
            try {
                const startDate = document.getElementById('reportStartDate').value;
                const endDate = document.getElementById('reportEndDate').value;

                const response = await getTopSellingReport(startDate, endDate, 10);
                if (response.status === 'success') {
                    const tbody = document.getElementById('topSellingTableBody');
                    const topSelling = response.data;

                    if (topSelling.length > 0) {
                        tbody.innerHTML = topSelling.map((item, index) => `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.nama_barang}</td>
                                <td>${item.nama_kategori || '-'}</td>
                                <td><span class="badge bg-primary">${item.total_terjual}</span></td>
                                <td>${formatCurrency(item.total_pendapatan)}</td>
                            </tr>
                        `).join('');
                    } else {
                        tbody.innerHTML = '<tr><td colspan="5" class="text-center">Belum ada data penjualan</td></tr>';
                    }
                }
            } catch (error) {
                console.error('Error loading top selling:', error);
            }
        }

        async function loadLowStock() {
            try {
                const response = await getLowStockReport(10);
                if (response.status === 'success') {
                    const lowStockList = document.getElementById('lowStockList');
                    const lowStockItems = response.data;

                    document.getElementById('lowStockItems').textContent = lowStockItems.length;

                    if (lowStockItems.length > 0) {
                        lowStockList.innerHTML = lowStockItems.map(item => `
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${item.nama_barang}</strong>
                                    <br>
                                    <small class="text-muted">${item.nama_kategori || 'Tanpa Kategori'}</small>
                                </div>
                                <span class="badge bg-danger">${item.stok} pcs</span>
                            </div>
                        `).join('');
                    } else {
                        lowStockList.innerHTML = '<div class="p-4 text-center text-success"><i class="fas fa-check-circle me-2"></i>Semua stok aman</div>';
                    }
                }
            } catch (error) {
                console.error('Error loading low stock:', error);
            }
        }

        async function loadItemsCount() {
            try {
                const response = await getItems();
                if (response.status === 'success') {
                    document.getElementById('totalItems').textContent = response.data.length;
                }
            } catch (error) {
                console.error('Error loading items count:', error);
            }
        }
    </script>
</body>

</html>