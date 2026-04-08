<link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">

<link rel="stylesheet" href="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('js/select.dataTables.min.css') }}">

<link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css') }}">
<link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    :root {
        --theme-green-900: #123524;
        --theme-green-800: #1b5e43;
        --theme-green-700: #1f6f50;
        --theme-green-600: #2f7a3e;
        --theme-green-500: #2d8a60;
        --theme-green-400: #4ba97b;
        --theme-green-300: #8bcfb0;
        --theme-green-200: #d7efe4;
        --theme-green-100: #eef8f2;
        --theme-ink: #243126;
        --theme-muted: #6e7d73;
        --theme-border: #d6e8dc;
        --theme-surface: #f7fbf8;
        --theme-shadow: 0 14px 30px rgba(18, 53, 36, 0.08);
    }

    body {
        background: linear-gradient(180deg, #f5fbf7 0%, #edf7f0 100%);
        color: var(--theme-ink);
    }

    .page-body-wrapper,
    .content-wrapper {
        background: transparent !important;
    }

    .main-panel > .content-wrapper {
        padding-top: 24px;
    }

    .card,
    .surface-card,
    .metric-card {
        border-color: var(--theme-border);
        box-shadow: var(--theme-shadow);
    }

    .search-input {
        width: 200px;
        border-radius: 8px;
        height: 38px;
    }

    .form-control,
    .select2-container .select2-selection--single {
        border-color: #cfe0d5 !important;
        box-shadow: none !important;
    }

    .form-control:focus,
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: var(--theme-green-500) !important;
        box-shadow: 0 0 0 0.2rem rgba(47, 122, 62, 0.12) !important;
    }

    .btn-primary,
    .btn-success {
        background: linear-gradient(135deg, var(--theme-green-700), var(--theme-green-500));
        border-color: var(--theme-green-700);
        color: #fff;
        border-radius: 10px;
        font-weight: 600;
        box-shadow: 0 10px 20px rgba(31, 111, 80, 0.16);
    }

    .btn-primary:hover,
    .btn-primary:focus,
    .btn-success:hover,
    .btn-success:focus {
        background: linear-gradient(135deg, var(--theme-green-800), var(--theme-green-600));
        border-color: var(--theme-green-800);
        color: #fff;
    }

    .btn-outline-primary,
    .btn-outline-info {
        border-color: var(--theme-green-500);
        color: var(--theme-green-700);
    }

    .btn-outline-primary:hover,
    .btn-outline-info:hover {
        background: var(--theme-green-100);
        border-color: var(--theme-green-600);
        color: var(--theme-green-900);
    }

    .btn-info {
        background: #e8f6ee;
        border-color: #cbe9d8;
        color: var(--theme-green-800);
    }

    .btn-info:hover {
        background: #d6efe2;
        border-color: #b8dfca;
        color: var(--theme-green-900);
    }

    .btn-warning {
        background: #fff4d7;
        border-color: #f0d899;
        color: #8c6500;
    }

    .btn-danger {
        box-shadow: none;
    }

    .btn-secondary {
        border-radius: 10px;
    }

    .sidebar-header-horizontal {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 18px 15px;
        border-bottom: 1px solid var(--theme-border);
    }

    .sidebar-logo-horizontal {
        width: 38px;
    }

    .sidebar-title-horizontal,
    .navbar-brand-text {
        font-size: 18px;
        font-weight: 700;
        color: var(--theme-green-700);
        white-space: nowrap;
    }

    .navbar {
        background: rgba(255, 255, 255, 0.92);
        border-bottom: 1px solid var(--theme-border);
        backdrop-filter: blur(10px);
        box-shadow: 0 10px 30px rgba(20, 40, 28, 0.05);
    }

    .navbar .nav-link,
    .navbar .icon-menu,
    .navbar .icon-bell,
    .navbar .icon-ellipsis {
        color: var(--theme-green-800) !important;
    }

    .navbar-dropdown {
        border: 1px solid var(--theme-border);
        border-radius: 16px;
        box-shadow: var(--theme-shadow);
    }

    .dropdown-item:active,
    .dropdown-item:focus,
    .dropdown-item:hover {
        background: var(--theme-green-100);
        color: var(--theme-green-900);
    }

    .sidebar-menu-header {
        margin: 12px 15px 8px;
        padding: 8px 12px;
        background: linear-gradient(135deg, var(--theme-green-100), #e4f3ea);
        color: var(--theme-green-700);
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        border-radius: 10px;
        letter-spacing: 0.6px;
    }

    .sidebar {
        background: linear-gradient(180deg, #ffffff 0%, #f4fbf6 100%);
        border-right: 1px solid var(--theme-border);
        box-shadow: inset -1px 0 0 rgba(18, 53, 36, 0.03);
    }

    .sidebar .nav {
        margin-top: 0;
        padding: 0 10px;
    }

    .sidebar .nav .nav-item {
        margin-bottom: 6px;
    }

    .sidebar .nav .nav-item .nav-link {
        border-radius: 14px;
        transition: all 0.2s ease;
        color: #4e5d53;
    }

    .sidebar .nav .nav-item .nav-link .menu-title,
    .sidebar .nav .nav-item .nav-link i {
        color: inherit;
        transition: inherit;
    }

    .sidebar .nav .nav-item .nav-link:hover {
        background: var(--theme-green-100);
        color: var(--theme-green-800);
        transform: translateX(2px);
    }

    .sidebar .nav:not(.sub-menu) > .nav-item:hover > .nav-link,
    .sidebar .nav:not(.sub-menu) > .nav-item:focus-within > .nav-link,
    .sidebar .nav:not(.sub-menu) > .nav-item > .nav-link:hover,
    .sidebar .nav:not(.sub-menu) > .nav-item > .nav-link:focus {
        background: var(--theme-green-100) !important;
        color: var(--theme-green-800) !important;
    }

    .sidebar .nav:not(.sub-menu) > .nav-item:hover > .nav-link i,
    .sidebar .nav:not(.sub-menu) > .nav-item:hover > .nav-link .menu-title,
    .sidebar .nav:not(.sub-menu) > .nav-item:focus-within > .nav-link i,
    .sidebar .nav:not(.sub-menu) > .nav-item:focus-within > .nav-link .menu-title,
    .sidebar .nav:not(.sub-menu) > .nav-item > .nav-link:hover i,
    .sidebar .nav:not(.sub-menu) > .nav-item > .nav-link:hover .menu-title,
    .sidebar .nav:not(.sub-menu) > .nav-item > .nav-link:focus i,
    .sidebar .nav:not(.sub-menu) > .nav-item > .nav-link:focus .menu-title {
        color: var(--theme-green-800) !important;
    }

    .sidebar .nav .nav-item.active .nav-link {
        background: linear-gradient(135deg, var(--theme-green-700), var(--theme-green-500));
        color: #fff;
        box-shadow: 0 10px 20px rgba(31, 111, 80, 0.18);
    }

    .sidebar .nav .nav-item.active .nav-link .menu-title,
    .sidebar .nav .nav-item.active .nav-link i {
        color: #fff;
    }

    .sidebar .nav:not(.sub-menu) > .nav-item.active > .nav-link,
    .sidebar .nav:not(.sub-menu) > .nav-item.active:hover > .nav-link,
    .sidebar .nav:not(.sub-menu) > .nav-item.active > .nav-link:hover,
    .sidebar .nav:not(.sub-menu) > .nav-item.active > .nav-link[aria-expanded="true"] {
        background: linear-gradient(135deg, var(--theme-green-700), var(--theme-green-500)) !important;
        color: #fff !important;
    }

    .sidebar .nav:not(.sub-menu) > .nav-item.active > .nav-link i,
    .sidebar .nav:not(.sub-menu) > .nav-item.active > .nav-link .menu-title,
    .sidebar .nav:not(.sub-menu) > .nav-item.active:hover > .nav-link i,
    .sidebar .nav:not(.sub-menu) > .nav-item.active:hover > .nav-link .menu-title,
    .sidebar .nav:not(.sub-menu) > .nav-item.active > .nav-link:hover i,
    .sidebar .nav:not(.sub-menu) > .nav-item.active > .nav-link:hover .menu-title {
        color: #fff !important;
    }

    .navbar .nav-link:hover,
    .navbar .nav-link:focus,
    .navbar .nav-item:hover .nav-link,
    .navbar .nav-item:focus-within .nav-link {
        color: var(--theme-green-700) !important;
    }

    .table {
        color: var(--theme-ink);
    }

    .table thead th {
        border-top: 0;
        color: var(--theme-green-700);
        background: #f4faf6;
    }

    .badge.bg-success,
    .badge.badge-success {
        background: var(--theme-green-600) !important;
    }

    .badge.bg-info,
    .badge.badge-info {
        background: #2d8a60 !important;
    }

    a {
        color: var(--theme-green-700);
    }

    a:hover {
        color: var(--theme-green-900);
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        transition: all 0.25s ease;
        box-shadow: 0 6px 16px rgba(31, 111, 80, 0.14);
    }

    .btn-action i {
        font-size: 18px;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 20px rgba(31, 111, 80, 0.18);
    }

    .chart-wrapper {
        max-width: 380px;
        margin: 0 auto;
    }

    .pagination {
        margin-bottom: 0;
    }

    .pagination .page-item .page-link {
        border-radius: 8px !important;
        margin-left: 4px;
        padding: 6px 12px;
        font-size: 14px;
        color: var(--theme-green-700);
        border-color: #d5e5db;
    }

    .pagination .page-item.active .page-link {
        background-color: var(--theme-green-700);
        border-color: var(--theme-green-700);
        color: #fff;
    }

    .pagination .page-item .page-link:hover {
        background: var(--theme-green-100);
    }

    .pagination-wrapper {
        display: flex;
        justify-content: flex-end;
    }

    .select2-container {
        width: 100% !important;
    }

    .select2-container .select2-selection--single {
        height: 38px !important;
        border-radius: 8px !important;
        padding: 6px 12px !important;
        display: flex !important;
        align-items: center !important;
        background-color: #fff !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: normal !important;
        padding-left: 0 !important;
        color: #495057 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100% !important;
        right: 10px !important;
    }

    .select2-dropdown {
        border-radius: 10px !important;
        border: 1px solid #cedfD4 !important;
        box-shadow: var(--theme-shadow);
    }

    .select2-search__field {
        border-radius: 6px !important;
        padding: 6px !important;
    }
</style>

@if (request()->routeIs('dashboard'))
    @include('partials.styles.dashboard')
@endif

@if (request()->routeIs('data.index'))
    @include('partials.styles.data-index')
@endif

@if (request()->routeIs('pembayaran.index'))
    @include('partials.styles.pembayaran-index')
@endif

@if (request()->routeIs('notif.index'))
    @include('partials.styles.notifikasi-index')
@endif
