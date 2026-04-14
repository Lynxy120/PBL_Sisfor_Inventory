    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Sistem Manajemen Inventori UMKM" />
    <meta name="author" content="Kelompok 6" />
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        /* ============ ROOT VARIABLES ============ */
        :root {
            --primary-color: #0d6efd;
            --success-color: #198754;
            --info-color: #0dcaf0;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-bg: #f8f9fa;
            --border-color: #bbb;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.15);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.2);
            --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.25);
            --transition-speed: 0.3s;
        }

        /* ============ ENHANCED SIDEBAR STYLES ============ */
        .sb-sidenav-dark {
            background: linear-gradient(135deg, #1e1e2f 0%, #2d2d44 100%);
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.2);
        }

        .sb-sidenav-dark .nav-link {
            color: rgba(255, 255, 255, 0.9);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 12px;
            transition: all var(--transition-speed) ease;
            position: relative;
            overflow: hidden;
            font-weight: 500;
        }

        .sb-sidenav-dark .nav-link::before {
            content: '';
            position: absolute;
            left: -100%;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.05);
            transition: left var(--transition-speed) ease;
            z-index: 0;
        }

        .sb-sidenav-dark .nav-link:hover::before {
            left: 0;
        }

        .sb-sidenav-dark .nav-link.active {
            background: linear-gradient(90deg, #4e54c8 0%, #8f94fb 100%);
            color: #fff;
            box-shadow: 0 4px 15px rgba(78, 84, 200, 0.4);
            font-weight: 500;
        }

        .sb-sidenav-dark .sb-sidenav-menu-heading {
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 1px;
            padding: 20px 20px 10px;
            font-weight: 600;
        }

        .sb-nav-link-icon {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin-right: 10px;
            background: rgba(255, 255, 255, 0.08);
            transition: all var(--transition-speed) ease;
        }

        .sb-sidenav-dark .nav-link:hover .sb-nav-link-icon,
        .sb-sidenav-dark .nav-link.active .sb-nav-link-icon {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }

        .sb-sidenav-footer {
            background: rgba(0, 0, 0, 0.2);
            padding: 16px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sb-sidenav-footer .small {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.75rem;
        }

        /* ============ SIDEBAR BRAND ============ */
        .sidebar-brand {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand h4 {
            color: #fff;
            font-weight: 700;
            margin: 0;
            background: linear-gradient(90deg, #4e54c8, #8f94fb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.3rem;
        }

        .sidebar-brand small {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.75rem;
            display: block;
            margin-top: 5px;
        }

        /* ============ ENHANCED TABLE STYLES ============ */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead th {
            background: linear-gradient(135deg, #e8e8ff 0%, #d9d9f2 100%);
            border: 2px solid #5555cc !important;
            padding: 14px 16px;
            font-weight: 700;
            text-align: left;
            color: #000;
            font-size: 0.95rem;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        table tbody td {
            border: 1px solid #999 !important;
            padding: 14px 16px;
            vertical-align: middle;
            font-size: 0.95rem;
            color: #222;
        }

        table tbody tr {
            transition: all var(--transition-speed) ease;
        }

        table tbody tr:hover {
            background-color: #e8f4ff;
            box-shadow: inset 0 0 8px rgba(13, 110, 253, 0.1);
        }

        table tbody tr:nth-child(even) {
            background-color: #f5f5ff;
        }

        table tbody tr:nth-child(even):hover {
            background-color: #e0e8ff;
        }

        /* ============ DATATABLES STYLING ============ */
        .dataTable-table {
            border: 1px solid var(--border-color);
        }

        .dataTable-table thead th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 2px solid var(--border-color) !important;
            font-weight: 600;
        }

        .dataTable-container {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .dataTable-wrapper .dataTable-top,
        .dataTable-wrapper .dataTable-bottom {
            padding: 12px 16px;
            background-color: #f8f9fa;
            border-top: 1px solid var(--border-color);
        }

        .dataTable-wrapper .dataTable-top {
            border-top: none;
            border-bottom: 1px solid var(--border-color);
        }

        /* ============ CARD ENHANCEMENTS ============ */
        .card {
            border: 2px solid #bbb;
            border-radius: 10px;
            box-shadow: var(--shadow-md);
            transition: all var(--transition-speed) ease;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .card-header {
            background: linear-gradient(135deg, #e8eaff 0%, #d5d9ff 100%);
            border-bottom: 3px solid #5555cc;
            font-weight: 700;
            padding: 16px 20px;
            color: #000;
        }

        .card-header.bg-primary,
        .card-header.bg-success,
        .card-header.bg-info,
        .card-header.bg-warning {
            color: #fff !important;
            font-weight: 700;
        }

        .card-header.bg-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%) !important;
            border-bottom: 3px solid #003080 !important;
        }

        .card-header.bg-success {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%) !important;
            border-bottom: 3px solid #003d15 !important;
        }

        .card-header.bg-info {
            background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%) !important;
            border-bottom: 3px solid #003d5c !important;
        }

        .card-header.bg-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ccaa00 100%) !important;
            border-bottom: 3px solid #665500 !important;
            color: #000 !important;
        }

        .card-body {
            padding: 20px;
        }

        .card-footer {
            background-color: #f8f9fa;
            border-top: 1px solid var(--border-color);
            padding: 14px 20px;
        }

        /* ============ DASHBOARD CARD STATS ============ */
        .card.bg-primary.text-white {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%) !important;
            border: 3px solid #004085 !important;
            box-shadow: var(--shadow-md);
            transition: all var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card.bg-success.text-white {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%) !important;
            border: 3px solid #004d1a !important;
            box-shadow: var(--shadow-md);
            transition: all var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card.bg-info.text-white {
            background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%) !important;
            border: 3px solid #003d5c !important;
            box-shadow: var(--shadow-md);
            transition: all var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card.bg-warning.text-white {
            background: linear-gradient(135deg, #ffc107 0%, #ccaa00 100%) !important;
            border: 3px solid #665500 !important;
            box-shadow: var(--shadow-md);
            transition: all var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
            color: #000 !important;
        }

        .card.bg-primary.text-white:hover,
        .card.bg-success.text-white:hover,
        .card.bg-info.text-white:hover,
        .card.bg-warning.text-white:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .card.bg-primary.text-white .card-footer,
        .card.bg-success.text-white .card-footer,
        .card.bg-info.text-white .card-footer,
        .card.bg-warning.text-white .card-footer {
            border: none !important;
            background: rgba(0, 0, 0, 0.1) !important;
        }

        .card-body h2 {
            font-weight: 700;
            font-size: 2.5rem;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* ============ FORM ENHANCEMENTS ============ */
        .form-control,
        .form-select {
            border: 2px solid #999;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.95rem;
            transition: all var(--transition-speed) ease;
            background-color: #fff;
            color: #222;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
            outline: none;
        }

        .form-control:disabled,
        .form-select:disabled {
            background-color: #e9ecef;
            opacity: 0.7;
        }

        .form-label {
            font-weight: 700;
            color: #000;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-check-input {
            width: 20px;
            height: 20px;
            margin-top: 2px;
            cursor: pointer;
            border: 2px solid var(--border-color);
            border-radius: 4px;
            transition: all var(--transition-speed) ease;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .form-check-label {
            cursor: pointer;
            font-weight: 500;
            color: #333;
        }

        /* ============ BUTTON ENHANCEMENTS ============ */
        .btn {
            border-radius: 8px;
            padding: 10px 18px;
            font-weight: 700;
            transition: all var(--transition-speed) ease;
            text-transform: none;
            letter-spacing: 0;
            border: 2px solid transparent;
            cursor: pointer;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        }

        .btn:hover {
            box-shadow: 0 5px 16px rgba(0, 0, 0, 0.25);
            transform: translateY(-2px);
        }

        .btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            border: none;
        }

        .btn-success {
            background: linear-gradient(135deg, #198754 0%, #156a46 100%);
            border: none;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #bb2d3b 100%);
            border: none;
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #333;
            border: none;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        /* ============ MODAL ENHANCEMENTS ============ */
        .modal-header {
            background: linear-gradient(135deg, #e8eaff 0%, #d5d9ff 100%);
            border-bottom: 3px solid #5555cc;
            padding: 18px 20px;
        }

        .modal-title {
            font-weight: 800;
            color: #000;
            font-size: 1.2rem;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            background-color: #f8f9fa;
            border-top: 1px solid var(--border-color);
            padding: 16px 20px;
        }

        /* ============ ALERT & FEEDBACK ============ */
        .alert {
            border-radius: 8px;
            border: none;
            padding: 14px 16px;
            font-weight: 500;
            box-shadow: var(--shadow-sm);
        }

        .alert-success {
            background-color: #c0f5d0;
            color: #003d1a;
            border-left: 6px solid #0d6d2f;
            font-weight: 600;
        }

        .alert-warning {
            background-color: #ffeb99;
            color: #332200;
            border-left: 6px solid #cc8800;
            font-weight: 600;
        }

        .alert-danger {
            background-color: #ffcccc;
            color: #660000;
            border-left: 6px solid #cc0000;
            font-weight: 600;
        }

        .alert-info {
            background-color: #99ddff;
            color: #003366;
            border-left: 6px solid #0066cc;
            font-weight: 600;
        }

        .invalid-feedback {
            display: block !important;
            color: #cc0000;
            font-size: 0.85rem;
            margin-top: 6px;
            font-weight: 700;
        }

        .is-invalid {
            border-color: #cc0000 !important;
            box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.25);
        }

        .is-valid {
            border-color: #0d6d2f !important;
            box-shadow: 0 0 0 3px rgba(13, 109, 47, 0.25);
        }

        /* ============ LOADING STATE ============ */
        .btn:disabled,
        .btn.loading {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .spinner-border {
            width: 1rem;
            height: 1rem;
            margin-right: 8px;
            vertical-align: middle;
        }

        /* ============ RESPONSIVENESS ============ */
        @media (max-width: 768px) {
            .card-body h2 {
                font-size: 1.8rem;
            }

            .btn {
                padding: 8px 14px;
                font-size: 0.9rem;
            }

            table thead th,
            table tbody td {
                padding: 10px 12px;
                font-size: 0.9rem;
            }

            .card-header {
                padding: 12px 16px;
            }

            .card-body {
                padding: 16px;
            }

            .form-label {
                font-size: 0.9rem;
            }
        }

        /* ============ ANIMATIONS ============ */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn var(--transition-speed) ease forwards;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
            }

            to {
                transform: translateX(0);
            }
        }

        .slide-in {
            animation: slideIn var(--transition-speed) ease forwards;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .pulse {
            animation: pulse 2s ease-in-out infinite;
        }

        /* ============ NAVBAR SEARCH STYLES ============ */
        .sb-topnav {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
        }

        #navbarSearchInput {
            font-size: 0.95rem !important;
            padding: 8px 12px !important;
            background-color: #f8f9fa !important;
            border: 1px solid #dee2e6 !important;
            transition: all var(--transition-speed) ease !important;
        }

        #navbarSearchInput:focus {
            background-color: #ffffff !important;
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15) !important;
            outline: none !important;
        }

        #navbarSearchInput::placeholder {
            color: #adb5bd;
        }

        .input-group .input-group-text {
            border: 1px solid #dee2e6 !important;
            background-color: #f8f9fa !important;
            transition: all var(--transition-speed) ease !important;
        }

        #navbarSearchInput:focus~.input-group-text,
        #navbarSearchInput:focus+.input-group-text {
            border-color: var(--primary-color) !important;
            background-color: #ffffff !important;
        }

        #searchResultsContainer {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
            animation: slideDown 0.3s ease forwards;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .search-result-item {
            border-radius: 4px;
        }

        .search-result-item:hover {
            background-color: #f8f9fa !important;
        }

        #searchResultsContainer .card {
            border: 1px solid #e9ecef !important;
            background-color: #ffffff !important;
        }

        #searchResultsContainer .spinner-border-sm {
            width: 1.2rem;
            height: 1.2rem;
            border-width: 0.2em;
        }

        #searchLoadingState {
            color: #6c757d;
        }

        #searchEmptyState {
            color: #6c757d;
        }

        #searchEmptyState .fas {
            opacity: 0.3;
        }

        /* Scrollbar styling untuk search results */
        #searchResultsList::-webkit-scrollbar {
            width: 6px;
        }

        #searchResultsList::-webkit-scrollbar-track {
            background: transparent;
        }

        #searchResultsList::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }

        #searchResultsList::-webkit-scrollbar-thumb:hover {
            background: #999;
        }

        /* Badge styling dalam search results */
        #searchResultsContainer .badge {
            font-size: 0.75rem !important;
            padding: 4px 8px !important;
            font-weight: 600;
        }

        /* ============ NAVBAR USER PROFILE STYLES ============ */
        #navbarDropdown {
            transition: all var(--transition-speed) ease;
            padding: 6px 12px !important;
            border-radius: 8px;
        }

        #navbarDropdown:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        #navbarDropdown::after {
            border-color: rgba(255, 255, 255, 0.6) !important;
            transition: transform var(--transition-speed) ease;
        }

        #navbarDropdown[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }

        .dropdown-menu {
            border-radius: 8px !important;
            border: 1px solid #e9ecef !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
            min-width: 220px !important;
            margin-top: 8px !important;
            animation: slideDown 0.2s ease forwards;
        }

        .dropdown-item {
            padding: 10px 16px !important;
            transition: all var(--transition-speed) ease;
            border-radius: 4px;
            margin: 4px 8px;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            transform: translateX(4px);
        }

        .dropdown-item.text-danger:hover {
            background-color: #ffe5e5;
            color: #dc3545 !important;
        }

        .dropdown-divider {
            margin: 6px 0 !important;
            opacity: 0.5;
        }

        /* Sidebar toggle button */
        #sidebarToggle {
            transition: all 0.3s ease;
        }

        #sidebarToggle:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
            border-radius: 6px;
        }

        #sidebarToggle:active {
            background-color: rgba(255, 255, 255, 0.15) !important;
        }

        #sidebarToggle:focus {
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2) !important;
        }

        /* User avatar circle */
        .nav-link[id="navbarDropdown"]>div:first-child {
            flex-shrink: 0;
            animation: fadeIn 0.3s ease;
        }

        /* Role badge styling */
        .nav-link[id="navbarDropdown"] span[style*="font-size: 0.75rem"] {
            display: flex;
            gap: 4px;
            align-items: center;
        }
    </style>