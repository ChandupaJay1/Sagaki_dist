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

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    <style>
        :root {
            --sg-topbar-height: 72px;
            --sg-sidebar-width: 260px;
            --sg-primary-gradient: linear-gradient(135deg, #4f46e5 0%, #9333ea 100%);
            --sg-card-shadow-light: 0 10px 30px rgba(0,0,0,0.06), 0 4px 8px rgba(0,0,0,0.02);
            --sg-card-shadow-dark: 0 20px 40px rgba(0,0,0,0.3);
            --sg-transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        body {
            font-family: 'Inter', sans-serif !important;
            letter-spacing: -0.015em;
        }

        /* Support for Dark Mode and Light Mode backgrounds */
        [data-bs-theme="light"] body { 
            background-color: #f7f9fc !important; 
        }
        [data-bs-theme="dark"] body { 
            background-color: #0c111d !important; 
        }

        .main-nav {
            background: #0f172a !important; 
            box-shadow: 12px 0 50px rgba(0,0,0,0.15);
            z-index: 1050;
        }

        .main-nav .logo-box {
            background: #0f172a !important;
            height: var(--sg-topbar-height);
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid rgba(255,255,255,0.05) !important;
        }

        /* Sidebar Menu Items */
        .navbar-nav .menu-link {
            color: rgba(255,255,255,0.5) !important;
            font-weight: 500;
            padding: 13px 20px !important;
            border-radius: 12px;
            margin: 6px 16px;
            transition: var(--sg-transition);
        }

        .navbar-nav .menu-link:hover {
            color: #ffffff !important;
            background: rgba(255,255,255,0.08);
            transform: translateX(5px);
        }

        .navbar-nav .menu-item.active .menu-link,
        .navbar-nav .menu-link.active {
            color: #ffffff !important;
            background: var(--sg-primary-gradient) !important;
            box-shadow: 0 10px 20px rgba(79,70,229,0.35);
        }

        .nav-icon i {
            font-size: 21px;
            transition: var(--sg-transition);
        }

        /* Fix Sidebar Icon Hover & Active Invisibility */
        .navbar-nav .menu-link:hover .nav-icon,
        .navbar-nav .menu-item.active .menu-link .nav-icon,
        .navbar-nav .menu-link.active .nav-icon {
            background: transparent !important;
            color: #ffffff !important;
        }

        .navbar-nav .menu-link:hover .nav-icon i,
        .navbar-nav .menu-item.active .menu-link .nav-icon i,
        .navbar-nav .menu-link.active .nav-icon i {
            color: #ffffff !important;
        }

        /* Topbar - Premium Header */
        .topbar {
            background: linear-gradient(180deg, #ffffff 0%, #fafbff 100%) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(79, 70, 229, 0.08) !important;
            height: var(--sg-topbar-height);
            z-index: 1040;
            position: sticky;
            top: 0;
            box-shadow: 0 4px 24px rgba(79, 70, 229, 0.06);
        }

        [data-bs-theme="dark"] .topbar {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%) !important;
            border-bottom: 1px solid rgba(255,255,255,0.06) !important;
            box-shadow: 0 4px 30px rgba(0,0,0,0.3);
        }

        /* Header logo area - sleek, visible border in light theme */
        .topbar > .logo-box {
            background: transparent !important;
            border: none !important;
            border-right: 1px solid #e2e8f0 !important;
            padding: 0 24px !important;
            min-width: var(--sg-sidebar-width);
        }

        .topbar > .logo-box a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 12px;
            border-radius: 14px;
            transition: var(--sg-transition);
        }

        .topbar > .logo-box a:hover {
            background: rgba(79, 70, 229, 0.06);
        }

        [data-bs-theme="dark"] .topbar > .logo-box {
            border-right-color: rgba(255,255,255,0.06) !important;
        }

        /* Hide full logo image everywhere so "Metor" is never shown - only icon + our name */
        .topbar > .logo-box .logo-lg,
        .main-nav .logo-box .logo-lg {
            display: none !important;
        }

        /* Sidebar: hide template logo images (Metor); show only our icon + "Sagaki Distribution" */
        .main-nav .logo-box .logo-sm {
            display: none !important;
        }

        .main-nav .logo-box .sidebar-brand-icon {
            font-size: 26px;
            color: #f97316;
            margin-right: 10px;
            display: flex;
            align-items: center;
        }

        .topbar > .logo-box .logo-sm {
            max-height: 32px;
            width: auto;
        }

        /* Brand name "Sagaki Distribution" - always visible & beautiful */
        .brand-name {
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            white-space: nowrap;
            margin-left: 10px;
            display: inline-block !important;
        }

        /* Short brand name - hidden on desktop, shown on mobile to avoid "Sagaki Distributi" truncation */
        .brand-name-short { display: none !important; }

        /* Mobile: show short name, hide long; compact topbar logo; search visible */
        @media (max-width: 991px) {
            .main-nav .brand-name-full { display: none !important; }
            .main-nav .brand-name-short { display: inline-block !important; }
            .topbar > .logo-box .brand-name-full { display: none !important; }
            .topbar > .logo-box .brand-name-short { display: inline-block !important; }
            .topbar > .logo-box { min-width: auto !important; padding-left: 12px !important; padding-right: 12px !important; }
            .main-nav .logo-box .brand-name-short { margin-left: 8px; font-size: 0.95rem; }
            .topbar > .logo-box .brand-name { font-size: 0.9rem; }
        }

        @media (min-width: 992px) {
            .brand-name-short { display: none !important; }
        }

        .main-nav .logo-box a {
            display: flex;
            align-items: center;
        }

        .main-nav .logo-box .brand-name {
            color: #ffffff !important;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }

        /* Search: icon by default, click to expand bar */
        .search-wrap { display: flex; align-items: center; min-width: 0; }
        .search-toggle-btn {
            width: 42px; height: 42px;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            background: #f1f5f9;
            color: #475569;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.25s ease;
        }
        .search-toggle-btn:hover { color: #4f46e5; border-color: #4f46e5; background: rgba(79,70,229,0.06); }
        [data-bs-theme="dark"] .search-toggle-btn { background: rgba(51,65,85,0.5); border-color: rgba(71,85,105,0.5); color: #94a3b8; }
        [data-bs-theme="dark"] .search-toggle-btn:hover { color: #a5b4fc; }

        .app-search { overflow: hidden; transition: max-width 0.3s ease, opacity 0.25s ease; max-width: 0; opacity: 0; margin-right: 0; width: auto; }
        .app-search .form-control { transition: width 0.2s ease; }
        .search-wrap.search-open .app-search { max-width: 280px; width: 280px; opacity: 1; margin-right: 8px; }
        .search-wrap.search-open .app-search .form-control { width: 100% !important; }

        @media (min-width: 992px) {
            .search-wrap.search-open .app-search { max-width: 320px; width: 320px; }
        }

        @media (max-width: 767px) {
            .search-wrap.search-open .app-search { max-width: 160px; width: 160px; }
        }

        .topbar > .logo-box a {
            display: flex;
            align-items: center;
        }

        /* Topbar name: always visible - light theme = dark text, dark theme = light text (Metor removed) */
        .topbar > .logo-box .brand-name {
            color: #1e293b !important;
        }

        [data-bs-theme="dark"] .topbar > .logo-box .brand-name {
            color: #f1f5f9 !important;
        }

        .topbar .navbar-header {
            padding: 0 28px;
            height: 100%;
        }

        /* Menu toggle in header - clearly visible in light theme */
        .topbar .button-toggle-menu {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #f1f5f9 !important;
            border: 1px solid #cbd5e1 !important;
            color: #475569 !important;
            transition: var(--sg-transition);
        }

        .topbar .button-toggle-menu:hover {
            background: rgba(79, 70, 229, 0.08) !important;
            color: #4f46e5 !important;
            border-color: #4f46e5 !important;
        }

        .topbar .button-sm-hover-icon {
            color: inherit !important;
        }

        [data-bs-theme="dark"] .topbar .button-toggle-menu {
            background: rgba(51, 65, 85, 0.5) !important;
            border-color: rgba(71, 85, 105, 0.5) !important;
            color: #94a3b8 !important;
        }

        [data-bs-theme="dark"] .topbar .button-toggle-menu:hover {
            background: rgba(79, 70, 229, 0.2) !important;
            color: #a5b4fc !important;
        }

        /* User dropdown menu - polished */
        .topbar .dropdown-menu {
            border-radius: 16px !important;
            border: 1px solid rgba(226, 232, 240, 0.9) !important;
            box-shadow: 0 12px 40px rgba(0,0,0,0.1) !important;
            padding: 8px !important;
            margin-top: 8px !important;
        }

        .topbar .dropdown-item {
            border-radius: 10px !important;
            padding: 10px 14px !important;
            font-weight: 500;
            transition: background 0.2s ease;
        }

        .topbar .dropdown-item:hover {
            background: rgba(79, 70, 229, 0.08) !important;
        }

        [data-bs-theme="dark"] .topbar .dropdown-menu {
            border-color: rgba(255,255,255,0.08) !important;
            box-shadow: 0 16px 48px rgba(0,0,0,0.4) !important;
        }

        /* Search - visible & beautiful in light theme */
        .app-search .form-control {
            background: #f1f5f9 !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 14px !important;
            padding-left: 46px !important;
            height: 46px;
            font-size: 14px;
            width: 280px;
            color: #1e293b !important;
            transition: var(--sg-transition);
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .app-search .form-control::placeholder {
            color: #64748b !important;
            font-weight: 500;
        }

        .app-search .form-control:focus {
            background: #ffffff !important;
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12) !important;
            width: 320px;
        }

        [data-bs-theme="dark"] .app-search .form-control {
            background: rgba(30, 41, 59, 0.8) !important;
            border-color: rgba(71, 85, 105, 0.8) !important;
            color: #f1f5f9 !important;
        }

        [data-bs-theme="dark"] .app-search .form-control::placeholder {
            color: #94a3b8 !important;
        }

        [data-bs-theme="dark"] .app-search .form-control:focus {
            background: #1e293b !important;
        }

        /* Search icon - clearly visible in light theme */
        .app-search .search-widget-icon {
            left: 18px !important;
            color: #475569 !important;
            font-size: 18px;
            transition: color 0.2s ease;
        }

        .app-search .form-control:focus ~ .search-widget-icon,
        .app-search:focus-within .search-widget-icon {
            color: #4f46e5 !important;
        }

        [data-bs-theme="dark"] .app-search .search-widget-icon {
            color: #94a3b8 !important;
        }

        /* Theme toggle - clearly visible in light theme */
        .topbar-button {
            height: 42px;
            padding: 0 14px;
            min-width: 42px;
            background: #f1f5f9 !important;
            color: #475569 !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 12px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--sg-transition);
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        }

        [data-bs-theme="dark"] .topbar-button {
            background: rgba(51, 65, 85, 0.5) !important;
            color: #94a3b8 !important;
            border-color: rgba(71, 85, 105, 0.5) !important;
        }

        .topbar-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.12);
            border-color: #4f46e5 !important;
            color: #4f46e5 !important;
            background: rgba(79, 70, 229, 0.08) !important;
        }

        [data-bs-theme="dark"] .topbar-button:hover {
            background: rgba(79, 70, 229, 0.15) !important;
            color: #a5b4fc !important;
        }

        /* Sidebar Category Headers */
        .menu-title {
            color: rgba(255, 255, 255, 0.3) !important;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 24px 20px 8px 30px !important;
            list-style: none;
        }

        /* User profile pill - visible & premium in light theme */
        #page-header-user-dropdown {
            height: 46px !important;
            padding: 0 18px 0 8px !important;
            border-radius: 14px !important;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
            border: 1px solid #cbd5e1 !important;
            width: auto !important;
            gap: 12px;
            transition: var(--sg-transition);
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }

        #page-header-user-dropdown .avatar-sm {
            width: 36px !important;
            height: 36px !important;
        }

        #page-header-user-dropdown .avatar-sm img {
            width: 36px !important;
            height: 36px !important;
            border: 2px solid rgba(79, 70, 229, 0.2) !important;
        }

        #page-header-user-dropdown .fs-12 {
            font-size: 13px !important;
            font-weight: 700 !important;
            color: #1e293b !important;
            letter-spacing: 0.02em;
        }

        #page-header-user-dropdown .extra-small {
            font-size: 11px !important;
            color: #64748b !important;
            font-weight: 600 !important;
        }

        [data-bs-theme="dark"] #page-header-user-dropdown {
            background: linear-gradient(135deg, rgba(30,41,59,0.9) 0%, rgba(51,65,85,0.5) 100%) !important;
            border: 1px solid rgba(255,255,255,0.08) !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }

        [data-bs-theme="dark"] #page-header-user-dropdown .fs-12 {
            color: #f1f5f9 !important;
        }

        [data-bs-theme="dark"] #page-header-user-dropdown .extra-small {
            color: #94a3b8 !important;
        }

        #page-header-user-dropdown:hover {
            background: #ffffff !important;
            border-color: rgba(79, 70, 229, 0.25) !important;
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.12) !important;
            transform: translateY(-1px);
        }

        [data-bs-theme="dark"] #page-header-user-dropdown:hover {
            background: rgba(51, 65, 85, 0.8) !important;
            border-color: rgba(129, 140, 248, 0.2) !important;
            box-shadow: 0 8px 28px rgba(79, 70, 229, 0.2) !important;
        }

        /* Utility Classes */
        .extra-small {
            font-size: 11px !important;
            line-height: 1.2;
        }

        .fs-28 { font-size: 28px !important; }
        .transition-all { transition: var(--sg-transition); }
        .hover-translate:hover {
            transform: translateY(-3px) translateX(3px);
            background: #ffffff !important;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
        }

        [data-bs-theme="dark"] .hover-translate:hover {
            background: #1e293b !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important;
        }

        /* Global Content Spacing */
        .page-content {
            padding: 40px !important;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Status & Badge Refinements */
        .pulse {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
            margin-right: 8px;
            box-shadow: 0 0 0 rgba(var(--bs-primary-rgb), 0.4);
            animation: pulse-animation 2s infinite;
        }

        @keyframes pulse-animation {
            0% { box-shadow: 0 0 0 0 rgba(var(--bs-primary-rgb), 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(var(--bs-primary-rgb), 0); }
            100% { box-shadow: 0 0 0 0 rgba(var(--bs-primary-rgb), 0); }
        }

        .badge-soft {
            padding: 6px 14px;
            font-weight: 700;
            border-radius: 50px;
            border: none;
            display: inline-flex;
            align-items: center;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Card - The "Patta" Look */
        .card {
            background: #ffffff !important;
            border: 1px solid #edf2f7 !important;
            box-shadow: var(--sg-card-shadow-light);
            border-radius: 24px !important;
            transition: var(--sg-transition);
        }

        [data-bs-theme="dark"] .card {
            background: #111827 !important;
            border: 1px solid rgba(255,255,255,0.06) !important;
            box-shadow: var(--sg-card-shadow-dark);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        [data-bs-theme="dark"] .card:hover {
            box-shadow: 0 30px 60px rgba(0,0,0,0.4);
        }

        /* Hide vertical line in sidebar - Comprehensive Fix */
        .main-nav::before, .main-nav::after, .navbar-nav::before, .navbar-nav::after, 
        #navbar-nav::before, #navbar-nav::after, .menu-item::after, .menu-item::before, 
        .menu-link::after, .menu-link::before, .nav-icon::after, .nav-icon::before {
            display: none !important;
            content: none !important;
            border: none !important;
            width: 0 !important;
        }

        .main-nav { border-right: none !important; }

        /* Scrollbar */
        [data-simplebar] .simplebar-scrollbar:before {
            background: rgba(255,255,255,0.1) !important;
        }
    </style>
    <script src="{{ asset('assets/js/config.min.js') }}"></script>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <div class="main-nav">
            <!-- Sidebar Logo - no Metor; icon + Sagaki Distribution only -->
            <div class="logo-box">
                <a href="{{ route('dashboard') }}" class="logo-dark">
                    <i class="ri-fire-line sidebar-brand-icon"></i>
                    <img src="{{ asset('assets/images/logo-sm.png') }}" class="logo-sm" alt="">
                    <img src="{{ asset('assets/images/logo-dark.png') }}" class="logo-lg" alt="">
                    <span class="brand-name brand-name-full" title="Sagaki Distribution">Sagaki Distribution</span>
                    <span class="brand-name brand-name-short" title="Sagaki Distribution">Sagaki</span>
                </a>

                <a href="{{ route('dashboard') }}" class="logo-light">
                    <i class="ri-fire-line sidebar-brand-icon"></i>
                    <img src="{{ asset('assets/images/logo-sm.png') }}" class="logo-sm" alt="">
                    <img src="{{ asset('assets/images/logo-white.png') }}" class="logo-lg" alt="">
                    <span class="brand-name brand-name-full" title="Sagaki Distribution">Sagaki Distribution</span>
                    <span class="brand-name brand-name-short" title="Sagaki Distribution">Sagaki</span>
                </a>
            </div>

            <div class="h-100" data-simplebar>

                <ul class="navbar-nav" id="navbar-nav">

                    <li class="menu-title">Menu</li>

                    <li class="menu-item pt-1">
                        <a class="menu-link" href="{{ route('dashboard') }}">
                            <span class="nav-icon">
                                <i class="ri-dashboard-2-line"></i>
                            </span>
                            <span class="nav-text"> Dashboard </span>
                        </a>
                    </li>

                    <li class="menu-title">Master Tables</li>

                    <li class="menu-item">
                        <a class="menu-link" href="{{ route('master-tables') }}">
                            <span class="nav-icon">
                                <i class="ri-database-2-line"></i>
                            </span>
                            <span class="nav-text"> Master Tables </span>
                        </a>
                    </li>

                    <li class="menu-title">User Network</li>

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

                    <li class="menu-title">Operations</li>

                    <li class="menu-item">
                        <a class="menu-link" href="{{ route('approvals.index') }}">
                            <span class="nav-icon">
                                <i class="ri-user-follow-line"></i>
                            </span>
                            <span class="nav-text"> Pending Approvals </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ route('routes.index') }}">
                            <span class="nav-icon">
                                <i class="ri-route-line"></i>
                            </span>
                            <span class="nav-text"> Routes </span>
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

                    <li class="menu-title">System</li>

                    <li class="menu-item">
                        <a class="menu-link" href="{{ route('admins.index') }}">
                            <span class="nav-icon">
                                <i class="ri-shield-user-line"></i>
                            </span>
                            <span class="nav-text"> Admins </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <header class="topbar d-flex">
            <!-- Header Logo - full on desktop, short on mobile -->
            <div class="logo-box">
                <a href="{{ route('dashboard') }}" class="logo-dark">
                    <img src="{{ asset('assets/images/logo-sm.png') }}" class="logo-sm" alt="Sagaki">
                    <img src="{{ asset('assets/images/logo-dark.png') }}" class="logo-lg" alt="Sagaki Distribution">
                    <span class="brand-name brand-name-full" title="Sagaki Distribution">Sagaki Distribution</span>
                    <span class="brand-name brand-name-short" title="Sagaki Distribution">Sagaki</span>
                </a>

                <a href="{{ route('dashboard') }}" class="logo-light">
                    <img src="{{ asset('assets/images/logo-sm.png') }}" class="logo-sm" alt="Sagaki">
                    <img src="{{ asset('assets/images/logo-white.png') }}" class="logo-lg" alt="Sagaki Distribution">
                    <span class="brand-name brand-name-full" title="Sagaki Distribution">Sagaki Distribution</span>
                    <span class="brand-name brand-name-short" title="Sagaki Distribution">Sagaki</span>
                </a>
            </div>

            <div class="container">
                <div class="navbar-header">

                    <!-- Menu Toggle Button (sm-hover) -->
                    <button type="button" class="btn btn-link d-flex button-sm-hover button-toggle-menu"
                        aria-label="Show Full Sidebar">
                        <i class="ri-menu-2-line button-sm-hover-icon text-white"></i>
                    </button>

                    <div class="d-flex align-items-center gap-2 flex-grow-1 min-width-0 justify-content-end justify-content-md-start">
                        <!-- Search: icon toggles search bar -->
                        <div class="search-wrap" id="search-wrap">
                            <button type="button" class="search-toggle-btn me-2" id="search-toggle-btn" aria-label="Search">
                                <i class="ri-search-line fs-20"></i>
                            </button>
                            <form class="app-search" action="#" method="get" role="search">
                                <div class="position-relative">
                                    <input type="search" class="form-control" id="topbar-search-input" placeholder="Search..."
                                        autocomplete="off" value="" aria-label="Search">
                                    <i class="ri-search-line search-widget-icon"></i>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-4 ms-auto">
                        <!-- Theme Color (Light/Dark) -->
                        <div class="topbar-item">
                            <button type="button" class="topbar-button" id="light-dark-mode">
                                <i class="ri-moon-line fs-20 align-middle light-mode"></i>
                                <i class="ri-sun-line fs-20 align-middle dark-mode"></i>
                            </button>
                        </div>

                        <!-- User -->
                        <div class="dropdown topbar-item">
                            <a type="button" class="topbar-button d-flex align-items-center" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm">
                                        <img class="rounded-circle border border-2 border-primary-subtle" width="32" height="32"
                                            src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="user-image">
                                    </div>
                                    <div class="d-none d-lg-block text-start">
                                        <p class="my-0 fs-12 fw-bold text-dark-emphasis text-uppercase" style="letter-spacing: 0.5px;">
                                            {{ Auth::user()->name }}
                                        </p>
                                        <p class="my-0 extra-small text-muted fw-medium">Administrator</p>
                                    </div>
                                    <i class="ri-arrow-down-s-line text-muted ms-1 fs-16"></i>
                                </div>
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
    <script>
        (function() {
            var wrap = document.getElementById('search-wrap');
            var btn = document.getElementById('search-toggle-btn');
            var input = document.getElementById('topbar-search-input');
            if (wrap && btn && input) {
                btn.addEventListener('click', function() {
                    wrap.classList.toggle('search-open');
                    if (wrap.classList.contains('search-open')) {
                        input.focus();
                    }
                });
                input.addEventListener('blur', function() {
                    if (!input.value.trim()) wrap.classList.remove('search-open');
                });
            }
        })();
    </script>
    @yield('scripts')

</body>

</html>