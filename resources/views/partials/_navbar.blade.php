<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5 d-flex align-items-center" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="mr-2 navbar-brand-logo">
            <span class="navbar-brand-text">Notiloan</span>
        </a>

        <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/images/logo-mini.svg') }}" alt="logo" />
        </a>
    </div>

    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="icon-menu"></span>
        </button>

        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item dropdown">
                <a class="nav-link position-relative" href="#" data-toggle="dropdown">
                    <i class="icon-bell mx-0"></i>

                    @if ($navbarNotifCount > 0)
                        <span class="navbar-notif-badge">
                            {{ $navbarNotifCount }}
                        </span>
                    @endif
                </a>

                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list">
                    <p class="mb-0 font-weight-normal float-left dropdown-header">
                        Notifications
                    </p>

                    @forelse ($navbarNotifications as $notif)
                        <a class="dropdown-item preview-item" href="{{ route('notif.index') }}">
                            <div class="preview-item-content">
                                <h6 class="preview-subject font-weight-normal">
                                    {{ $notif->message }}
                                </h6>

                                <p class="font-weight-light small-text mb-0 text-muted">
                                    {{ $notif->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </a>
                    @empty
                        <div class="dropdown-item text-center text-muted">
                            Tidak ada notifikasi
                        </div>
                    @endforelse
                </div>
            </li>

            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    <img src="{{ asset('assets/images/user.png') }}" alt="profile" class="navbar-avatar">
                </a>

                <div class="dropdown-menu dropdown-menu-right navbar-dropdown">
                    <a class="dropdown-item">
                        <i class="ti-settings text-primary"></i> Settings
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="ti-power-off text-primary"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </li>

            <li class="nav-item nav-settings d-none d-lg-flex">
                <a class="nav-link" href="#">
                    <i class="icon-ellipsis"></i>
                </a>
            </li>

            <li class="nav-item d-flex align-items-center ml-3">
                <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="navbar-inline-logo">
            </li>
        </ul>

        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </div>
</nav>
