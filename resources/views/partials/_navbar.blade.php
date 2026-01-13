<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">

    
        <a class="navbar-brand brand-logo mr-5 d-flex align-items-center" href="#">
            <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="mr-2" style="height:40px;">
            <span class="navbar-brand-text">Notiloan</span>
        </a>


        <a class="navbar-brand brand-logo-mini" href="#">
            <img src="{{ asset('assets/images/logo-mini.svg') }}" alt="logo" />
        </a>

    </div>

    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">

        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="icon-menu"></span>
        </button>

        <ul class="navbar-nav mr-lg-2">
            <li class="nav-item nav-search d-none d-lg-block">
                <div class="input-group">
                    <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                        <span class="input-group-text" id="search">
                            <i class="icon-search"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" placeholder="Search now">
                </div>
            </li>
        </ul>

        <ul class="navbar-nav navbar-nav-right">

            <!-- NOTIFICATION -->
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" href="#" data-toggle="dropdown">
                    <i class="icon-bell mx-0"></i>
                    <span class="count"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list">
                    <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                </div>
            </li>

            <!-- PROFILE -->
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    <img src="{{ asset('assets/images/user.png') }}" alt="profile" />
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown">
                    <a class="dropdown-item">
                        <i class="ti-settings text-primary"></i> Settings
                    </a>
                    <a class="dropdown-item">
                        <i class="ti-power-off text-primary"></i> Logout
                    </a>
                </div>
            </li>

            <li class="nav-item nav-settings d-none d-lg-flex">
                <a class="nav-link" href="#">
                    <i class="icon-ellipsis"></i>
                </a>
            </li>

        </ul>

        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>

    </div>
</nav>
