<?php
// Get current page for active highlighting
$currentPage = $currentPage ?? basename($_SERVER['PHP_SELF']);
?>
<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <!-- Brand/Logo -->
        <div class="sidebar-brand">
            <h4><i class="fas fa-boxes me-2"></i>Inventory</h4>
            <small>Sistem Manajemen Stok</small>
        </div>

        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Menu Utama</div>

                <a class="nav-link <?= $currentPage == 'index.php' ? 'active' : '' ?>" href="index.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

                <a class="nav-link <?= $currentPage == 'table_stock.php' ? 'active' : '' ?>" href="table_stock.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                    Data Barang
                </a>

                <div class="sb-sidenav-menu-heading">Master Data</div>

                <a class="nav-link <?= $currentPage == 'kategori.php' ? 'active' : '' ?>" href="kategori.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div>
                    Kategori
                </a>

                <a class="nav-link <?= $currentPage == 'supplier.php' ? 'active' : '' ?>" href="supplier.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-truck"></i></div>
                    Supplier
                </a>

                <div class="sb-sidenav-menu-heading">Stok Management</div>

                <a class="nav-link <?= $currentPage == 'kelolastock.php' ? 'active' : '' ?>" href="kelolastock.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-exchange-alt"></i></div>
                    Kelola Stok
                </a>

                <a class="nav-link <?= $currentPage == 'riwayatstock.php' ? 'active' : '' ?>" href="riwayatstock.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-history"></i></div>
                    Riwayat Stok
                </a>

                <div class="sb-sidenav-menu-heading">Laporan</div>

                <a class="nav-link <?= $currentPage == 'laporan.php' ? 'active' : '' ?>" href="laporan.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-bar"></i></div>
                    Laporan & Analisis
                </a>

                <div class="sb-sidenav-menu-heading">Pengaturan</div>

                <a class="nav-link <?= $currentPage == 'manajemenuser.php' ? 'active' : '' ?>" href="manajemenuser.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Manajemen User
                </a>
            </div>
        </div>

        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <strong><?= htmlspecialchars($userName ?? 'Kelompok 6') ?></strong>
        </div>
    </nav>
</div>