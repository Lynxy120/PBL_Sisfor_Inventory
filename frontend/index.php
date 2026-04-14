<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard - Grosiran Ayah</title>
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
                        <div class="col-xl-3 col-md-3">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body"><b>Total Barang</b></div>
                                <div class="card-footer d-flex align-items-center justify-content-between bg-primary">
                                    <div class="value-row">
                                        <h2 id="totalItems" class="text-white">-</h2>
                                    </div>
                                    <div class="big text-white"><i class="fas fa-box fa-2x"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-3">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body"><b>Total Kategori</b></div>
                                <div class="card-footer d-flex align-items-center justify-content-between bg-success">
                                    <div class="value-row">
                                        <h2 id="totalCategories" class="text-white">-</h2>
                                    </div>
                                    <div class="big text-white"><i class="fas fa-tags fa-2x"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-3">
                            <div class="card bg-info text-white mb-4">
                                <div class="card-body"><b>Total Supplier</b></div>
                                <div class="card-footer d-flex align-items-center justify-content-between bg-info">
                                    <div class="value-row">
                                        <h2 id="totalSuppliers" class="text-white">-</h2>
                                    </div>
                                    <div class="big text-white"><i class="fas fa-truck fa-2x"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-3">
                            <div class="card bg-warning text-white mb-4">
                                <div class="card-body"><b>Stok Rendah</b></div>
                                <div class="card-footer d-flex align-items-center justify-content-between bg-warning">
                                    <div class="value-row">
                                        <h2 id="lowStockCount" class="text-white">-</h2>
                                    </div>
                                    <div class="big text-white"><i class="fas fa-exclamation-triangle fa-2x"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-table me-1"></i>
                                    Data Barang Terbaru
                                </div>
                                <div class="card-body">
                                    <table id="datatablesSimple">
                                        <thead>
                                            <tr>
                                                <th>Nama Produk</th>
                                                <th>Foto</th>
                                                <th>Kategori</th>
                                                <th>Harga Beli</th>
                                                <th>Harga Jual</th>
                                                <th>Stok</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemsTableBody">
                                            <tr>
                                                <td colspan="7" class="text-center">Memuat data...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Top Selling Items -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <i class="bi bi-graph-up-arrow me-2 text-success"></i> Produk Terlaris
                                </div>
                                <div class="card-body px-0 py-2">
                                    <ul class="list-group list-group-flush" id="topSellingList">
                                        <li class="list-group-item text-center py-3">Memuat data...</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Table -->
                </div>
            </main>
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>

    <?php include 'includes/scripts.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>

    <script>
        // Load dashboard data on page load
        document.addEventListener('DOMContentLoaded', async function() {
            await loadDashboardData();
        });

        async function loadDashboardData() {
            try {
                // Load items
                const itemsResponse = await getItems();
                if (itemsResponse.status === 'success') {
                    const items = itemsResponse.data;
                    document.getElementById('totalItems').textContent = items.length;

                    // Populate items table
                    const tbody = document.getElementById('itemsTableBody');
                    if (items.length > 0) {
                        tbody.innerHTML = items.slice(0, 10).map(item => `
                            <tr>
                                <td>${item.nama_barang}</td>
                                <td>
                                    ${item.foto ? `<img src="${API_BASE_URL}/uploads/${item.foto}" alt="foto" style="width:48px;height:48px;object-fit:cover;border-radius:6px;">` : '<span class="text-muted">-</span>'}
                                </td>
                                <td>${item.nama_kategori || '-'}</td>
                                <td>${formatCurrency(item.harga_beli)}</td>
                                <td>${formatCurrency(item.harga_jual)}</td>
                                <td>${item.stok}</td>
                                <td>
                                    ${item.stok < 10 
                                        ? '<span class="badge bg-danger">Stok Rendah</span>' 
                                        : '<span class="badge bg-success">Tersedia</span>'}
                                </td>
                            </tr>
                        `).join('');
                    } else {
                        tbody.innerHTML = '<tr><td colspan="7" class="text-center">Belum ada data barang</td></tr>';
                    }
                }

                // Load categories
                const categoriesResponse = await getCategories();
                if (categoriesResponse.status === 'success') {
                    document.getElementById('totalCategories').textContent = categoriesResponse.data.length;
                }

                // Load suppliers
                const suppliersResponse = await getSuppliers();
                if (suppliersResponse.status === 'success') {
                    document.getElementById('totalSuppliers').textContent = suppliersResponse.data.length;
                }

                // Load low stock report
                const lowStockResponse = await getLowStockReport(10);
                if (lowStockResponse.status === 'success') {
                    document.getElementById('lowStockCount').textContent = lowStockResponse.data.length;
                }

                // Load top selling items
                const today = new Date();
                const startDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                const endDate = today.toISOString().split('T')[0];

                const topSellingResponse = await getTopSellingReport(startDate, endDate, 5);
                if (topSellingResponse.status === 'success') {
                    const topSelling = topSellingResponse.data;
                    const topSellingList = document.getElementById('topSellingList');

                    if (topSelling.length > 0) {
                        topSellingList.innerHTML = topSelling.map((item, index) => `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-primary me-2">#${index + 1}</span>
                                    ${item.nama_barang}
                                </div>
                                <span class="badge bg-success">${item.total_terjual} terjual</span>
                            </li>
                        `).join('');
                    } else {
                        topSellingList.innerHTML = '<li class="list-group-item text-center py-3">Belum ada data penjualan</li>';
                    }
                }

            } catch (error) {
                console.error('Error loading dashboard:', error);
            }
        }
    </script>
</body>

</html>