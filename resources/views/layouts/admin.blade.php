<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title Meta -->
    <meta charset="utf-8" />
    <title>@yield('title', 'Sagaki Distribution') | Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Sagaki Distribution Management System" />
    <meta name="author" content="FoxPixel" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets/js/config.min.js') }}"></script>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <div class="main-nav">
            <!-- Sidebar Logo -->
            <div class="logo-box">
                <a href="{{ route('dashboard') }}" class="logo-dark">
                    <img src="{{ asset('assets/images/logo-sm.png') }}" class="logo-sm" alt="logo sm">
                    <img src="{{ asset('assets/images/logo-dark.png') }}" class="logo-lg" alt="logo dark">
                </a>

                <a href="{{ route('dashboard') }}" class="logo-light">
                    <img src="{{ asset('assets/images/logo-sm.png') }}" class="logo-sm" alt="logo sm">
                    <img src="{{ asset('assets/images/logo-white.png') }}" class="logo-lg" alt="logo light">
                </a>
            </div>

            <div class="h-100" data-simplebar>

                <ul class="navbar-nav" id="navbar-nav">

                    <li class="menu-item pt-2">
                        <a class="menu-link" href="{{ route('dashboard') }}">
                            <span class="nav-icon">
                                <i class="ri-dashboard-2-line"></i>
                            </span>
                            <span class="nav-text"> Dashboard </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ route('customers.index') }}">
                            <span class="nav-icon">
                                <i class="ri-group-2-line"></i>
                            </span>
                            <span class="nav-text"> Customers </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ route('refs.index') }}">
                            <span class="nav-icon">
                                <i class="ri-user-star-line"></i>
                            </span>
                            <span class="nav-text"> Rep Agents </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ route('vendors.index') }}">
                            <span class="nav-icon">
                                <i class="ri-user-settings-line"></i>
                            </span>
                            <span class="nav-text"> Vendors </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ route('products.index') }}">
                            <span class="nav-icon">
                                <i class="ri-shopping-bag-3-line"></i>
                            </span>
                            <span class="nav-text"> Items </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <header class="topbar d-flex">
            <!-- Sidebar Logo -->
            <div class="logo-box">
                <a href="{{ route('dashboard') }}" class="logo-dark">
                    <img src="{{ asset('assets/images/logo-sm.png') }}" class="logo-sm" alt="logo sm">
                    <img src="{{ asset('assets/images/logo-dark.png') }}" class="logo-lg" alt="logo dark">
                </a>

                <a href="{{ route('dashboard') }}" class="logo-light">
                    <img src="{{ asset('assets/images/logo-sm.png') }}" class="logo-sm" alt="logo sm">
                    <img src="{{ asset('assets/images/logo-white.png') }}" class="logo-lg" alt="logo light">
                </a>
            </div>

            <div class="container">
                <div class="navbar-header">

                    <!-- Menu Toggle Button (sm-hover) -->
                    <button type="button" class="btn btn-link d-flex button-sm-hover button-toggle-menu"
                        aria-label="Show Full Sidebar">
                        <i class="ri-menu-2-line button-sm-hover-icon text-white"></i>
                    </button>

                    <div class="d-flex align-items-center gap-2">
                        <!-- App Search-->
                        <form class="app-search d-none d-md-block me-auto">
                            <div class="position-relative">
                                <input type="search" class="form-control" placeholder="Start typing..."
                                    autocomplete="off" value="">
                                <i class="ri-search-line search-widget-icon"></i>
                            </div>
                        </form>
                    </div>

                    <div class="d-flex align-items-center gap-2 ms-auto">
                        <!-- Theme Color (Light/Dark) -->
                        <div class="topbar-item">
                            <button type="button" class="topbar-button" id="light-dark-mode">
                                <i class="ri-moon-line fs-20 align-middle light-mode"></i>
                                <i class="ri-sun-line fs-20 align-middle dark-mode"></i>
                            </button>
                        </div>

                        <!-- User -->
                        <div class="dropdown topbar-item">
                            <a type="button" class="topbar-button p-0" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center gap-2">
                                    <img class="rounded-circle" width="32"
                                        src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="user-image">
                                    <span class="d-lg-flex flex-column gap-1 d-none">
                                        <h5 class="my-0 fs-13 text-uppercase text-reset fw-bold">
                                            {{ Auth::user()->name }}
                                        </h5>
                                    </span>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="bx bx-user-circle fs-18 align-middle me-2"></i><span
                                        class="align-middle">My Account</span>
                                </a>
                                <div class="dropdown-divider my-1"></div>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                                <a class="dropdown-item" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bx bx-log-out fs-18 align-middle me-2"></i><span
                                        class="align-middle">Logout</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="page-container">
            <div class="page-content">
                @yield('content')
            </div>

            <!-- ========== Footer Start ========== -->
            <footer class="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center">
                            <script>document.write(new Date().getFullYear())</script> &copy; NerdTech Labs. All
                            rights reserved.
                        </div>
                    </div>
                </div>
            </footer>
            <!-- ========== Footer End ========== -->
        </div>
    </div>
    <!-- END Wrapper -->

    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    @yield('scripts')

</body>

</html>