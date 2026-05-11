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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.min.css">
    
    <style>
        :root {
            --sg-topbar-height: 72px;
            --sg-sidebar-width: 260px;
            --sg-primary-gradient: linear-gradient(135deg, #4f46e5 0%, #9333ea 100%);
            --sg-card-shadow-light: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            --sg-card-shadow-dark: 0 20px 40px rgba(0,0,0,0.3);
            --sg-transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        body {
            font-family: 'Inter', sans-serif !important;
            letter-spacing: -0.015em;
        }

        /* Support for Dark Mode and Light Mode backgrounds */
        [data-bs-theme="light"] body { 
            background-color: #f8fafc !important; /* Soft Slate 50/100 mix */
            color: #1e293b !important; /* Slate 800 */
        }
        [data-bs-theme="dark"] body { 
            background-color: #0c111d !important; 
        }

        /* Light Theme Typography & Readability */
        [data-bs-theme="light"] .text-muted, 
        [data-bs-theme="light"] .secondary-text {
            color: #64748b !important; /* Slate 500 */
        }

        [data-bs-theme="light"] h1, [data-bs-theme="light"] h2, [data-bs-theme="light"] h3, 
        [data-bs-theme="light"] h4, [data-bs-theme="light"] h5, [data-bs-theme="light"] h6 {
            color: #0f172a !important; /* Slate 900 */
            letter-spacing: -0.025em;
        }

        [data-bs-theme="light"] .card {
            background: #ffffff !important;
            border: 1px solid rgba(226, 232, 240, 0.8) !important; /* Slate 200 soft */
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04), 0 8px 10px -6px rgba(0, 0, 0, 0.04) !important;
        }

        /* Tables (Light Mode) */
        [data-bs-theme="light"] .table thead th {
            background-color: #f1f5f9 !important; /* Slate 100 */
            color: #475569 !important; /* Slate 600 */
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #e2e8f0 !important;
        }

        [data-bs-theme="light"] .table tbody td {
            border-bottom: 1px solid #f1f5f9 !important;
            color: #334155 !important; /* Slate 700 */
        }

        /* Forms (Light Mode) */
        [data-bs-theme="light"] .form-control,
        [data-bs-theme="light"] .form-select {
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            color: #1e293b !important;
        }

        [data-bs-theme="light"] .form-control:focus,
        [data-bs-theme="light"] .form-select:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1) !important;
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

        /* Sidebar Sub-menu Styles */
        .sub-menu {
            list-style: none;
            padding-left: 54px;
            margin-bottom: 10px;
        }

        .sub-menu .menu-link {
            padding: 8px 16px !important;
            margin: 2px 16px 2px 0 !important;
            font-size: 13px;
            border-radius: 8px;
        }

        .menu-arrow {
            margin-left: auto;
            transition: transform 0.2s ease;
        }

        [aria-expanded="true"] .menu-arrow {
            transform: rotate(90deg);
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

        /* TomSelect Dropdown Fix for Tables */
        .ts-dropdown {
            z-index: 9999 !important;
        }
        .ts-wrapper.form-select-sm .ts-control {
            min-height: 28px !important;
            padding: 2px 8px !important;
        }
        .table-responsive {
            overflow: visible !important;
        }
        @media (max-width: 991.98px) {
            .table-responsive {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch;
            }
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

        @media (min-width: 768px) and (max-width: 991.98px) {
            .search-wrap.search-open .app-search { max-width: 260px; width: 260px; }
            .topbar .button-toggle-menu,
            .topbar-button { width: 40px; height: 40px; min-width: 40px; }
            .brand-chip { padding: 6px 8px; font-size: 0.9rem; }
        }

        @media (max-width: 767px) {
            .search-wrap.search-open .app-search { max-width: 160px; width: 160px; }
        }
        
        .navbar-header { position: relative; }
        @media (max-width: 576px) {
            .navbar-header.searching #search-wrap {
                position: absolute;
                left: 8px;
                right: 8px;
                top: 8px;
                z-index: 1100;
            }
            .navbar-header.searching #search-wrap .app-search {
                max-width: none !important;
                width: 100% !important;
                opacity: 1 !important;
                margin-right: 0 !important;
            }
            .navbar-header.searching #search-wrap .search-toggle-btn {
                display: none !important;
            }
            .navbar-header.searching .button-toggle-menu,
            .navbar-header.searching .brand-chip,
            .navbar-header.searching .topbar-button,
            .navbar-header.searching #page-header-user-dropdown {
                visibility: hidden !important;
            }
        }

        .topbar > .logo-box a {
            display: flex;
            align-items: center;
        }

        .topbar > .logo-box .brand-name { 
            display: inline-block !important;
            color: #1e293b !important;
        }
        [data-bs-theme="dark"] .topbar > .logo-box .brand-name { 
            color: #f1f5f9 !important;
        }
        /* Ensure only one brand text on desktop: hide short label on md+ */
        @media (min-width: 992px) {
            .topbar > .logo-box .brand-name-short { display: none !important; }
        }

        .topbar .navbar-header {
            padding: 0 28px;
            height: 100%;
        }
        
        /* Global form controls dark theme */
        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select {
            background-color: #1e293b !important;
            color: #e5e7eb !important;
            border-color: #475569 !important;
        }
        [data-bs-theme="dark"] .form-select {
            background-image: var(--bs-form-select-bg-img), linear-gradient(#0b1120, #0b1120) !important;
            background-color: #0b1120 !important;
            border-color: #1f2937 !important;
            color: #e5e7eb !important;
        }
        [data-bs-theme="dark"] .form-select option,
        [data-bs-theme="dark"] .form-control option {
            background-color: #0f172a !important;
            color: #e5e7eb !important;
        }
        [data-bs-theme="dark"] .form-select:disabled,
        [data-bs-theme="dark"] .form-control:disabled {
            background-color: #1f2937 !important;
            color: #9ca3af !important;
            border-color: #374151 !important;
        }
        [data-bs-theme="dark"] .form-select:focus,
        [data-bs-theme="dark"] .form-control:focus {
            background-image: var(--bs-form-select-bg-img), linear-gradient(#020617, #020617) !important;
            background-color: #020617 !important;
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.2) !important;
            color: #f8fafc !important;
        }
        [data-bs-theme="dark"] .form-control:focus,
        [data-bs-theme="dark"] .form-select:focus {
            background-color: #0f172a !important;
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.2) !important;
            color: #f8fafc !important;
        }
        [data-bs-theme="dark"] .ts-wrapper .ts-control {
            background-color: #0b1120 !important;
            color: #e5e7eb !important;
            border-color: #1f2937 !important;
            border-radius: .375rem !important;
        }
        [data-bs-theme="dark"] .ts-wrapper.focus .ts-control {
            background-color: #020617 !important;
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 2px rgba(99,102,241,0.35) !important;
            color: #f8fafc !important;
        }
        [data-bs-theme="dark"] .ts-control input {
            color: #e5e7eb !important;
        }
        [data-bs-theme="dark"] .ts-dropdown {
            background-color: #0b1120 !important;
            color: #e5e7eb !important;
            border-color: #1f2937 !important;
        }
        [data-bs-theme="dark"] .ts-dropdown .active {
            background: #111827 !important;
            color: #f8fafc !important;
        }
        .table .ts-wrapper {
            min-width: 140px;
            width: auto;
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
        /* Consistent look across large monitors: center and cap width */
        @media (min-width: 1400px) {
            .page-content { max-width: 1280px; margin-left: auto; margin-right: auto; }
            .topbar .container { max-width: 1280px; }
        }
        @media (min-width: 1800px) {
            .page-content { max-width: 1400px; }
            .topbar .container { max-width: 1400px; }
        }
        @media (max-width: 1200px) {
            .page-content { padding: 32px !important; }
        }
        @media (max-width: 992px) {
            .page-content { padding: 28px !important; }
        }
        @media (max-width: 768px) {
            .page-content { padding: 22px !important; }
        }
        @media (max-width: 576px) {
            .page-content { padding: 16px !important; }
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
        
        /* Toast & Notification */
        #toast-container {
            position: fixed;
            top: 80px;
            right: 16px;
            z-index: 1080;
            display: flex;
            flex-direction: column;
            gap: 12px;
            pointer-events: none;
        }
        .toast-item {
            min-width: 260px;
            max-width: 360px;
            background: #ffffff;
            color: #1f2937;
            border: 1px solid #e5e7eb;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            border-radius: 12px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            pointer-events: auto;
        }
        .toast-item .toast-icon {
            color: #ef4444;
            font-size: 18px;
            line-height: 1;
        }
        .toast-item .toast-text {
            flex: 1;
            font-size: 14px;
            margin: 0;
        }
        .toast-item .toast-action {
            white-space: nowrap;
        }
        [data-bs-theme="dark"] .toast-item {
            background: #0f172a;
            color: #e5e7eb;
            border-color: rgba(255,255,255,0.1);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        /* Global Preloader - Modern Geometric Design */
        #global-preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(12px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.5s;
        }

        #global-preloader.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .loader-visual {
            position: relative;
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loader-cube {
            width: 40px;
            height: 40px;
            background: var(--sg-primary-gradient);
            border-radius: 12px;
            animation: cube-morph 2s ease-in-out infinite;
            box-shadow: 0 0 30px rgba(79, 70, 229, 0.5);
        }

        .loader-circle {
            position: absolute;
            width: 80px;
            height: 80px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: circle-pulse 2s ease-in-out infinite;
        }

        @keyframes cube-morph {
            0% { transform: scale(1) rotate(0deg); border-radius: 12px; }
            25% { transform: scale(1.2) rotate(90deg); border-radius: 50%; }
            50% { transform: scale(1) rotate(180deg); border-radius: 12px; }
            75% { transform: scale(0.8) rotate(270deg); border-radius: 4px; }
            100% { transform: scale(1) rotate(360deg); border-radius: 12px; }
        }

        @keyframes circle-pulse {
            0% { transform: scale(0.8); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 0.2; border-width: 4px; }
            100% { transform: scale(0.8); opacity: 0.5; }
        }

        .loader-text {
            margin-top: 32px;
            color: #f8fafc;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            animation: text-glow 1.5s ease-in-out infinite;
        }

        @keyframes text-glow {
            0%, 100% { opacity: 0.5; text-shadow: 0 0 0px rgba(255,255,255,0); }
            50% { opacity: 1; text-shadow: 0 0 10px rgba(255,255,255,0.5); }
        }

        body.loading-state {
            overflow: hidden !important;
            pointer-events: none !important;
        }

        .brand-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 10px;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            color: #1e293b !important;
            text-decoration: none !important;
            font-weight: 700;
            letter-spacing: -0.01em;
        }
        .brand-chip i { color: #f97316; }
        [data-bs-theme="dark"] .brand-chip {
            background: rgba(51, 65, 85, 0.5);
            border-color: rgba(71, 85, 105, 0.5);
            color: #f1f5f9 !important;
        }
        @media (max-width: 576px) {
            .topbar { height: 64px; }
            .topbar .navbar-header { padding: 0 16px; }
            .topbar .button-toggle-menu,
            .topbar-button { width: 38px; height: 38px; min-width: 38px; }
            .search-wrap.search-open .app-search { max-width: 220px; width: 220px; }
            .app-search .form-control:focus { width: 240px; }
            .brand-name-short { font-size: 0.95rem !important; }
        }
        @media (max-width: 576px) {
            #toast-container {
                top: auto;
                right: 8px;
                left: 8px;
                bottom: 16px;
            }
            .toast-item {
                max-width: 100%;
                min-width: 0;
            }
        }
    </style>
    <script src="{{ asset('assets/js/config.min.js') }}"></script>
</head>

<body class="loading-state">
    <!-- Global Preloader -->
    <div id="global-preloader">
        <div class="loader-visual">
            <div class="loader-circle"></div>
            <div class="loader-cube"></div>
        </div>
        <div class="loader-text">Loading, please wait...</div>
    </div>

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

                    <li class="menu-title">Operations</li>

                    <li class="menu-item">
                        @php($pendingApprovalCount = \App\Models\User::where('is_active', false)->count())
                        <a class="menu-link" href="{{ route('approvals.index') }}" id="approvals-link">
                            <span class="nav-icon">
                                <i class="ri-user-follow-line"></i>
                            </span>
                            <span class="nav-text">
                                Pending Approvals
                                <span id="pending-approvals-badge" class="badge bg-danger-subtle text-danger ms-2 align-middle" style="{{ $pendingApprovalCount > 0 ? '' : 'display:none' }}">{{ $pendingApprovalCount }}</span>
                            </span>
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
                        <a class="menu-link" data-bs-toggle="collapse" href="#menuSales" role="button" aria-expanded="false" aria-controls="menuSales">
                            <span class="nav-icon">
                                <i class="ri-shopping-cart-2-line"></i>
                            </span>
                            <span class="nav-text"> Sales </span>
                            <i class="ri-arrow-right-s-line menu-arrow"></i>
                        </a>
                        <div class="collapse" id="menuSales">
                            <ul class="sub-menu">
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('customers.index') }}">
                                        <span class="nav-text">Customers</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('sales-orders.index') }}">
                                        <span class="nav-text">Sales Order</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('invoices.index') }}">
                                        <span class="nav-text">Invoice</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('sales-returns.index') }}">
                                        <span class="nav-text">Sales Return</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="menu-item">
                        <a class="menu-link" data-bs-toggle="collapse" href="#menuPurchasing" role="button" aria-expanded="false" aria-controls="menuPurchasing">
                            <span class="nav-icon">
                                <i class="ri-handbag-line"></i>
                            </span>
                            <span class="nav-text"> Purchasing </span>
                            <i class="ri-arrow-right-s-line menu-arrow"></i>
                        </a>
                        <div class="collapse" id="menuPurchasing">
                            <ul class="sub-menu">
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('vendors.index') }}">
                                        <span class="nav-text">Vendors</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('purchase-orders.index') }}">
                                        <span class="nav-text">Purchase Order</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('grns.index') }}">
                                        <span class="nav-text">GRN</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('grn-returns.index') }}">
                                        <span class="nav-text">GRN Return</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="menu-item">
                        <a class="menu-link" data-bs-toggle="collapse" href="#menuInventory" role="button" aria-expanded="false" aria-controls="menuInventory">
                            <span class="nav-icon">
                                <i class="ri-archive-line"></i>
                            </span>
                            <span class="nav-text"> Inventory </span>
                            <i class="ri-arrow-right-s-line menu-arrow"></i>
                        </a>
                        <div class="collapse" id="menuInventory">
                            <ul class="sub-menu">
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('products.index') }}">
                                        <span class="nav-text">Item</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('inventory-transfers.index') }}">
                                        <span class="nav-text">Transfer Note</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('inventory-issues.index') }}">
                                        <span class="nav-text">Issue Note</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('stock-adjustments.index') }}">
                                        <span class="nav-text">Stock Adjustment</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="menu-title">Payment</li>
                    <li class="menu-item">
                        <a class="menu-link" data-bs-toggle="collapse" href="#menuPayment" role="button" aria-expanded="false" aria-controls="menuPayment">
                            <span class="nav-icon">
                                <i class="ri-secure-payment-line"></i>
                            </span>
                            <span class="nav-text"> Payments </span>
                            <i class="ri-arrow-right-s-line menu-arrow"></i>
                        </a>
                        <div class="collapse" id="menuPayment">
                            <ul class="sub-menu">
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('pay-bills.supplier.create') }}">
                                        <span class="nav-text">Supplier Bills</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('pay-bills.customer.create') }}">
                                        <span class="nav-text">Customer Bills</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('pay-bills.index') }}">
                                        <span class="nav-text">History</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="menu-title">Reports</li>
                    <li class="menu-item">
                        <a class="menu-link" data-bs-toggle="collapse" href="#menuReports" role="button" aria-expanded="false" aria-controls="menuReports">
                            <span class="nav-icon">
                                <i class="ri-file-chart-line"></i>
                            </span>
                            <span class="nav-text"> Inventory Reports </span>
                            <i class="ri-arrow-right-s-line menu-arrow"></i>
                        </a>
                        <div class="collapse" id="menuReports">
                            <ul class="sub-menu">
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('reports.stock-by-site') }}">
                                        <span class="nav-text">Location Summary</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('reports.stock-valuation-summary') }}">
                                        <span class="nav-text">Inventory Summary</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('reports.stock-valuation-details') }}">
                                        <span class="nav-text">Inventory Details</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
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
                <div class="d-flex align-items-center">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" class="logo-sm" alt="Sagaki">
                        <img src="{{ asset('assets/images/logo-dark.png') }}" class="logo-lg" alt="Sagaki Distribution">
                        <span class="brand-name brand-name-full ms-1" title="Sagaki Distribution">Sagaki Distribution</span>
                        <span class="brand-name brand-name-short ms-1" title="Sagaki Distribution">Sagaki</span>
                    </a>
                    <!-- (Reverted) No menu icon inside logo area -->

                    <!-- Desktop menu toggle removed to restore original layout -->
                </div>
            </div>

            <div class="container">
                <div class="navbar-header">

                    <!-- Menu Toggle Button (original placement & visibility on all breakpoints) -->
                    <button type="button" class="btn btn-link d-flex button-sm-hover button-toggle-menu me-2"
                        aria-label="Show Full Sidebar" title="Menu">
                        <i class="ri-menu-2-line button-sm-hover-icon text-white"></i>
                    </button>

                    <!-- Brand chip hidden on md+ to avoid duplication; visible only on mobiles below -->

                    <!-- Mobile-only brand chip moved to right side tools cluster -->

                    <div class="d-flex align-items-center gap-2 flex-grow-1 min-width-0 justify-content-end justify-content-md-start">
                        <div class="d-lg-none me-2">
                            <a href="{{ route('dashboard') }}" class="brand-chip">
                                <i class="ri-fire-line"></i>
                                <span>Sagaki</span>
                            </a>
                        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        (function() {
            var wrap = document.getElementById('search-wrap');
            var btn = document.getElementById('search-toggle-btn');
            var input = document.getElementById('topbar-search-input');
            var header = document.querySelector('.navbar-header');
            if (wrap && btn && input) {
                btn.addEventListener('click', function() {
                    wrap.classList.toggle('search-open');
                    if (header) header.classList.toggle('searching', wrap.classList.contains('search-open'));
                    if (wrap.classList.contains('search-open')) {
                        input.focus();
                    }
                });
                input.addEventListener('blur', function() {
                    if (!input.value.trim()) {
                        wrap.classList.remove('search-open');
                        if (header) header.classList.remove('searching');
                    }
                });
            }
        })();
    </script>
    <script>
        (function() {
            var selects = document.querySelectorAll('select.form-select');
            selects.forEach(function(el) {
                if (el.tomselect) return;
                /* GRN create page wires #loadDropdown itself (copy prior GRN + fill form). */
                if (el.id === 'loadDropdown') return;
                var plugins = [];
                if (el.hasAttribute('multiple')) { plugins.push('remove_button'); } else { plugins.push('clear_button'); }
                new TomSelect(el, {
                    create: false,
                    allowEmptyOption: true,
                    plugins: plugins,
                    maxOptions: 10000,
                    searchField: ['text','code','keywords'],
                    selectOnTab: true,
                    closeAfterSelect: true,
                    dropdownParent: 'body',
                    render: {
                        no_results: function(data, escape) { return '<div class="no-results">No results</div>'; }
                    },
                    onItemAdd: function() {
                        this.setTextboxValue('');
                        this.refreshOptions(false);
                    },
                    onDropdownClose: function() {
                        this.setTextboxValue('');
                    },
                    onBlur: function() {
                        this.setTextboxValue('');
                    }
                });
            });
        })();
    </script>
    <script>
        (function() {
            var badge = document.getElementById('pending-approvals-badge');
            var link = document.getElementById('approvals-link');
            if (!badge || !link) return;
            var last = parseInt(badge.textContent || '0', 10) || 0;
            var url = "{{ route('approvals.count') }}";
            var container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                document.body.appendChild(container);
            }
            function showToast(message) {
                var t = document.createElement('div');
                t.className = 'toast-item';
                var icon = document.createElement('i');
                icon.className = 'ri-notification-3-line toast-icon';
                var text = document.createElement('div');
                text.className = 'toast-text';
                text.textContent = message;
                var action = document.createElement('a');
                action.href = link.getAttribute('href');
                action.textContent = 'Review';
                action.className = 'btn btn-sm btn-soft-danger toast-action';
                t.appendChild(icon);
                t.appendChild(text);
                t.appendChild(action);
                container.appendChild(t);
                setTimeout(function() {
                    if (t.parentNode) t.parentNode.removeChild(t);
                }, 6000);
            }
            function setBadge(count) {
                badge.textContent = count;
                if (count > 0) {
                    badge.style.display = '';
                } else {
                    badge.style.display = 'none';
                }
            }
            function poll() {
                fetch(url, { 
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        var c = parseInt((data && data.count) || 0, 10) || 0;
                        if (c > last) {
                            var diff = c - last;
                            var msg = diff === 1 ? '1 new user awaiting approval' : (diff + ' new users awaiting approval');
                            showToast(msg);
                        }
                        last = c;
                        setBadge(c);
                    })
                    .catch(function() {});
            }
            setInterval(poll, 15000);
            setTimeout(poll, 1000);
        })();
    </script>
    <script>
        (function() {
            const preloader = document.getElementById('global-preloader');
            let activeRequests = 0;
            
            window.showPreloader = function() {
                activeRequests++;
                if (preloader) {
                    preloader.classList.remove('hidden');
                    document.body.classList.add('loading-state');
                }
            };

            window.hidePreloader = function() {
                activeRequests--;
                if (activeRequests <= 0) {
                    activeRequests = 0;
                    if (preloader) {
                        preloader.classList.add('hidden');
                        document.body.classList.remove('loading-state');
                    }
                }
            };

            // Auto-hide on initial page load completion
            window.addEventListener('load', function() {
                // For initial load, we force hide after a small delay
                activeRequests = 0;
                setTimeout(window.hidePreloader, 500);
            });

            // Intercept Fetch API
            const originalFetch = window.fetch;
            window.fetch = async (...args) => {
                const url = typeof args[0] === 'string' ? args[0] : (args[0] instanceof Request ? args[0].url : '');
                
                // Exclude background polling tasks (like approval counts or specific stock checks)
                const isBackground = url.includes('approvals/count') || url.includes('stock?location');
                
                if (!isBackground) window.showPreloader();
                
                try {
                    const response = await originalFetch(...args);
                    if (!isBackground) window.hidePreloader();
                    return response;
                } catch (error) {
                    if (!isBackground) window.hidePreloader();
                    throw error;
                }
            };

            // Intercept jQuery AJAX if present
            if (window.jQuery) {
                $(document).ajaxStart(function() {
                    window.showPreloader();
                });
                $(document).ajaxStop(function() {
                    window.hidePreloader();
                });
            }
        })();
    </script>
    @stack('scripts')

</body>

</html>
 