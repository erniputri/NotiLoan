<link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">

<link rel="stylesheet" href="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('js/select.dataTables.min.css') }}">


<link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css') }}">
<link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">

<!-- SELECT2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .search-input {
        width: 200px;
        border-radius: 8px;
        height: 38px;
    }

    .btn-success {
        border-radius: 8px;
        height: 38px;
        font-weight: 500;
    }

    .sidebar-header-horizontal {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 18px 15px;
        border-bottom: 1px solid #e4e4e4;

    }

    .sidebar-logo-horizontal {
        width: 38px;
    }

    .sidebar-title-horizontal {
        font-size: 18px;
        font-weight: 600;
        color: #2f7a3e;
    }

    .navbar-brand-text {
        font-size: 18px;
        font-weight: 600;
        color: #2f7a3e;
        white-space: nowrap;
    }

    .navbar-right-logo {
        height: 28px;
        width: auto;
        opacity: 0.95;
    }

    .sidebar-menu-header {
        margin: 12px 15px 8px;
        padding: 8px 12px;
        background-color: #f5f6fa;
        color: #6c757d;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        border-radius: 6px;
        letter-spacing: 0.6px;
    }


    .sidebar .nav {
        margin-top: 0;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 500;
        transition: all 0.25s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    .btn-action i {
        font-size: 18px;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
    }

    .chart-wrapper {
        max-width: 380px;
        /* ukuran grafik */
        margin: 0 auto;
        /* posisi ke tengah */
    }

    .pagination {
        margin-bottom: 0;
    }

    .pagination .page-item .page-link {
        border-radius: 8px !important;
        margin-left: 4px;
        padding: 6px 12px;
        font-size: 14px;
    }

    .pagination .page-item.active .page-link {
        background-color: #4B49AC;
        border-color: #4B49AC;
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
        border: 1px solid #ced4da !important;
        border-radius: 6px !important;
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
        border-radius: 8px !important;
        border: 1px solid #ced4da !important;
    }

    .select2-search__field {
        border-radius: 6px !important;
        padding: 6px !important;
    }
</style>
