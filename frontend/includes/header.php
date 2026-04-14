<?php
// Check if we need to sync localStorage data to session
// This helps with the localStorage to PHP session bridge

// Get user data from session (set by sync-session.php)
$userName = $_SESSION['user_name'] ?? null;
$userRole = $_SESSION['user_role'] ?? null;
$userId = $_SESSION['user_id'] ?? null;

// If session is empty but we're not on login page, default to Guest
if (!$userName) {
    $userName = 'Guest';
    $userRole = '';
    $userId = null;
}
?>

<!-- Navbar -->
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand -->
    <a class="navbar-brand ps-3" href="index.php">
        <i class="fas fa-store me-2"></i>Grosiran Ayah
    </a>
    <!-- Sidebar Toggle -->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!" style="cursor: pointer; transition: all 0.3s ease;">
        <i class="fas fa-bars" style="font-size: 1.1rem; color: #fff;"></i>
    </button>
    <!-- Navbar Search -->
    <div class="d-none d-md-inline-block ms-auto me-3" style="position: relative; width: 250px;">
        <div class="input-group">
            <span class="input-group-text bg-light border-0">
                <i class="fas fa-search text-muted"></i>
            </span>
            <input
                type="text"
                id="navbarSearchInput"
                class="form-control border-0 bg-light"
                placeholder="Cari barang..."
                aria-label="Cari barang"
                style="border-radius: 0 0.375rem 0.375rem 0;">
        </div>
        <!-- Search Results Dropdown -->
        <div id="searchResultsContainer" style="display: none; position: absolute; top: 100%; left: 0; right: 0; z-index: 1050; margin-top: 8px;">
            <div class="card border-0 shadow-lg" style="border-radius: 0.5rem;">
                <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                    <div id="searchLoadingState" style="padding: 20px; text-align: center; display: none;">
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="text-muted">Mencari...</span>
                    </div>
                    <div id="searchResultsList" style="padding: 10px;"></div>
                    <div id="searchEmptyState" style="padding: 20px; text-align: center; display: none;">
                        <i class="fas fa-inbox text-muted" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                        <p class="text-muted mb-0">Tidak ada barang yang cocok</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Navbar User -->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="gap: 8px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem;">
                    <?php echo strtoupper(substr($userName ?? 'U', 0, 1)); ?>
                </div>
                <div style="display: flex; flex-direction: column; align-items: flex-start; font-size: 0.9rem;">
                    <span style="font-weight: 600; color: #fff;"><?= htmlspecialchars($userName ?? 'User') ?></span>
                    <span style="font-size: 0.75rem; color: #adb5bd;">
                        <?php
                        $roleDisplay = match ($userRole ?? '') {
                            'super_admin' => '👑 Super Admin',
                            'admin_gudang' => '📦 Admin Gudang',
                            'sales' => '💼 Sales',
                            default => '👤 Guest'
                        };
                        echo $roleDisplay;
                        ?>
                    </span>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" style="border-radius: 8px; border: 1px solid #e9ecef; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <li style="padding: 8px 12px; border-bottom: 1px solid #e9ecef;">
                    <small style="color: #6c757d; display: block;">
                        <strong>Role:</strong>
                        <?php
                        echo match ($userRole ?? '') {
                            'super_admin' => 'Super Admin',
                            'admin_gudang' => 'Admin Gudang',
                            'sales' => 'Sales',
                            default => 'Guest'
                        };
                        ?>
                    </small>
                </li>
                <li><a class="dropdown-item text-danger" href="login.php" onclick="logout()" style="transition: all 0.2s ease;">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a></li>
            </ul>
        </li>
    </ul>
</nav>

<script>
    // Global data cache untuk items
    let itemsCache = null;

    // Load items saat page load
    document.addEventListener('DOMContentLoaded', function() {
        loadItemsForSearch();
    });

    // Load items dari API
    async function loadItemsForSearch() {
        try {
            const response = await fetch('http://localhost:8001/items', {
                headers: getAuthHeaders()
            });
            const result = await response.json();
            if (result.status === 'success') {
                itemsCache = result.data || [];
            }
        } catch (error) {
            console.log('Failed to load items for search');
        }
    }

    // Search input handler
    const searchInput = document.getElementById('navbarSearchInput');
    const searchContainer = document.getElementById('searchResultsContainer');
    const searchLoadingState = document.getElementById('searchLoadingState');
    const searchResultsList = document.getElementById('searchResultsList');
    const searchEmptyState = document.getElementById('searchEmptyState');

    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();

        if (query.length === 0) {
            searchContainer.style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(() => performSearch(query), 300);
    });

    function performSearch(query) {
        if (!itemsCache || itemsCache.length === 0) {
            showSearchEmpty();
            return;
        }

        const lowerQuery = query.toLowerCase();
        const results = itemsCache.filter(item =>
            item.nama_barang.toLowerCase().includes(lowerQuery) ||
            (item.nama_kategori && item.nama_kategori.toLowerCase().includes(lowerQuery))
        ).slice(0, 8); // Limit 8 results

        showSearchResults(results, query);
    }

    function showSearchResults(results, query) {
        searchLoadingState.style.display = 'none';
        searchContainer.style.display = 'block';

        if (results.length === 0) {
            searchResultsList.innerHTML = '';
            searchEmptyState.style.display = 'block';
            return;
        }

        searchEmptyState.style.display = 'none';
        searchResultsList.innerHTML = results.map(item => `
        <a href="javascript:void(0)" onclick="viewItemDetail(${item.id})" class="search-result-item" style="display: flex; align-items: center; padding: 12px; border-bottom: 1px solid #f0f0f0; text-decoration: none; color: inherit; transition: background-color 0.2s; cursor: pointer;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='white'">
            <div style="flex: 1;">
                <div style="font-weight: 500; color: #212529; margin-bottom: 4px;">
                    ${item.nama_barang}
                </div>
                <div style="font-size: 0.85rem; color: #6c757d;">
                    <span class="badge bg-secondary me-2">${item.nama_kategori || 'N/A'}</span>
                    <span class="text-info">Stok: ${item.stok}</span>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-weight: 600; color: #198754; font-size: 0.95rem;">
                    Rp ${parseInt(item.harga_jual).toLocaleString('id-ID')}
                </div>
            </div>
        </a>
    `).join('');
    }

    function showSearchEmpty() {
        searchLoadingState.style.display = 'none';
        searchResultsList.innerHTML = '';
        searchEmptyState.style.display = 'block';
        searchContainer.style.display = 'block';
    }

    function viewItemDetail(itemId) {
        // Implement: bisa buka detail modal atau halaman detail barang
        const item = itemsCache.find(i => i.id === itemId);
        if (item) {
            // Contoh: Buka modal atau bisa arahkan ke halaman detail
            showAlert('info', `${item.nama_barang}`, `Harga: Rp ${parseInt(item.harga_jual).toLocaleString('id-ID')}<br>Stok: ${item.stok}<br>Kategori: ${item.nama_kategori}`);
        }
        searchInput.value = '';
        searchContainer.style.display = 'none';
    }

    // Close search container saat klik di luar
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.d-none.d-md-inline-block') &&
            !event.target.closest('#searchResultsContainer')) {
            searchContainer.style.display = 'none';
        }
    });

    // Keyboard navigation
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            searchContainer.style.display = 'none';
            this.value = '';
        }
    });

    // ============ Sync localStorage to PHP session ============
    let syncInProgress = false;

    function syncUserDataToSession() {
        // Prevent multiple sync attempts
        if (syncInProgress) return;

        const userId = localStorage.getItem('user_id');
        const userName = localStorage.getItem('user_name');
        const userRole = localStorage.getItem('user_role');

        // Only sync if we have user data
        if (userId && userName) {
            syncInProgress = true;

            // Send user data to sync-session.php to store in session
            fetch('includes/sync-session.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `user_id=${userId}&user_name=${encodeURIComponent(userName)}&user_role=${encodeURIComponent(userRole || '')}`
                }).then(response => response.json())
                .then(data => {
                    console.log('Sync result:', data);
                    // Only reload if sync was successful
                    if (data.status === 'success') {
                        location.reload();
                    }
                })
                .catch(err => {
                    console.error('Sync error:', err);
                    syncInProgress = false;
                });
        }
    }

    // Aggressive sync on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Always check and sync localStorage data immediately
        const userId = localStorage.getItem('user_id');

        if (userId) {
            // Wait a moment for DOM to be fully rendered, then check if we need to sync
            setTimeout(() => {
                // Check if currently showing "Guest"
                const pageContent = document.body.innerText;

                if (pageContent.includes('Guest')) {
                    console.log('Detected Guest user, attempting sync...');
                    syncUserDataToSession();
                }
            }, 500);
        }
    });

    // Also sync on page visibility change (when tab becomes active)
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden && localStorage.getItem('user_id')) {
            const pageContent = document.body.innerText;
            if (pageContent.includes('Guest')) {
                console.log('Page became visible, syncing user data...');
                syncUserDataToSession();
            }
        }
    });
</script>