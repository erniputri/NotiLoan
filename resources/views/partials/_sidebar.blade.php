<nav class="sidebar sidebar-offcanvas" id="sidebar">

    <div class="sidebar-brand-card">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo NotiLoan">
        </div>
        <div>
            <strong>NotiLoan</strong>
            <span>Monitoring pinjaman mitra</span>
        </div>
    </div>

    <div class="sidebar-menu-header">
        MENU
    </div>

    <ul class="nav">
        <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('data.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('data.index') }}">
                <i class="icon-paper menu-icon"></i>
                <span class="menu-title">Data</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('mitra.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('mitra.index') }}">
                <i class="icon-head menu-icon"></i>
                <span class="menu-title">Mitra</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('notif.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('notif.index') }}">
                <i class="icon-bell menu-icon"></i>
                <span class="menu-title">Notifikasi</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('pembayaran.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pembayaran.index') }}">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Pembayaran</span>
            </a>
        </li>

        @if (auth()->user()?->isSuperAdmin())
            <li class="nav-item {{ request()->routeIs('user.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('user.index') }}">
                    <i class="icon-head menu-icon"></i>
                    <span class="menu-title">User</span>
                </a>
            </li>
        @endif
    </ul>

</nav>
