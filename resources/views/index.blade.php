<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from foxpixel.vercel.app/metor/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 16 Jan 2026 18:56:06 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->

<head>
     <!-- Title Meta -->
     <meta charset="utf-8" />
     <title>Analytics | Metor - Responsive Admin Dashboard Template</title>
     <meta name="viewport" content="width=device-width, initial-scale=1" />
     <meta name="description"
          content="A fully responsive premium Bootstrap admin dashboard template for modern web applications." />
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
                         <img src="assets/images/logo-sm.png" class="logo-sm" alt="logo sm">
                         <img src="assets/images/logo-dark.png" class="logo-lg" alt="logo dark">
                    </a>

                    <a href="{{ route('dashboard') }}" class="logo-light">
                         <img src="assets/images/logo-sm.png" class="logo-sm" alt="logo sm">
                         <img src="assets/images/logo-white.png" class="logo-lg" alt="logo light">
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
                                   <span class="badge bg-success badge-pill text-end">9+</span>
                              </a>
                         </li>

                         <li class="menu-item">
                              <a class="menu-link" href="orders.html">
                                   <span class="nav-icon">
                                        <i class="ri-shopping-cart-line"></i>
                                   </span>
                                   <span class="nav-text"> Orders </span>
                              </a>
                         </li>

                         <li class="menu-item">
                              <a class="menu-link menu-arrow" href="#sidebarProduct" data-bs-toggle="collapse"
                                   role="button" aria-expanded="false" aria-controls="sidebarProduct">
                                   <span class="nav-icon">
                                        <i class="ri-shopping-basket-2-line"></i>
                                   </span>
                                   <span class="nav-text"> Products </span>
                              </a>
                              <div class="collapse" id="sidebarProduct">
                                   <ul class="sub-menu-nav">
                                        <li class="sub-menu-item">
                                             <a class="sub-menu-link" href="product-listing.html">Listing</a>
                                        </li>
                                        <li class="sub-menu-item">
                                             <a class="sub-menu-link" href="product-add-edit.html">Add/Edit Product</a>
                                        </li>
                                   </ul>
                              </div>
                         </li>

                         <li class="menu-item">
                              <a class="menu-link menu-arrow" href="#sidebarCategories" data-bs-toggle="collapse"
                                   role="button" aria-expanded="false" aria-controls="sidebarCategories">
                                   <span class="nav-icon">
                                        <i class="ri-equalizer-2-line"></i>
                                   </span>
                                   <span class="nav-text"> Categories </span>
                              </a>
                              <div class="collapse" id="sidebarCategories">
                                   <ul class="sub-menu-nav">
                                        <li class="sub-menu-item">
                                             <a class="sub-menu-link" href="categories-listing.html">Listing</a>
                                        </li>
                                        <li class="sub-menu-item">
                                             <a class="sub-menu-link" href="categories-add-edit.html">Add/Edit
                                                  Categories</a>
                                        </li>
                                   </ul>
                              </div>
                         </li>

                         <li class="menu-item">
                              <a class="menu-link" href="customers.html">
                                   <span class="nav-icon">
                                        <i class="ri-group-2-line"></i>
                                   </span>
                                   <span class="nav-text"> Customers </span>
                              </a>
                         </li>

                         <li class="menu-item">
                              <a class="menu-link" href="menu-cards.html">
                                   <span class="nav-icon">
                                        <i class="ri-restaurant-line"></i>
                                   </span>
                                   <span class="nav-text"> Menu Cards </span>
                              </a>
                         </li>

                         <li class="menu-item">
                              <a class="menu-link" href="pos.html">
                                   <span class="nav-icon">
                                        <i class="ri-mac-line"></i>
                                   </span>
                                   <span class="nav-text"> POS </span>
                              </a>
                         </li>

                         <li class="menu-item">
                              <a class="menu-link" href="reports.html">
                                   <span class="nav-icon">
                                        <i class="ri-bar-chart-box-ai-line"></i>
                                   </span>
                                   <span class="nav-text"> Reports </span>
                              </a>
                         </li>

                         <li class="menu-item">
                              <a class="menu-link" href="riders.html">
                                   <span class="nav-icon">
                                        <i class="ri-motorbike-line"></i>
                                   </span>
                                   <span class="nav-text"> Riders </span>
                              </a>
                         </li>

                         <li class="menu-item">
                              <a class="menu-link" href="offers.html">
                                   <span class="nav-icon">
                                        <i class="ri-discount-percent-line"></i>
                                   </span>
                                   <span class="nav-text"> Offers </span>
                              </a>
                         </li>

                         <li class="menu-item">
                              <a class="menu-link" href="locations.html">
                                   <span class="nav-icon">
                                        <i class="ri-map-pin-range-line"></i>
                                   </span>
                                   <span class="nav-text"> Locations </span>
                              </a>
                         </li>

                         <li class="menu-item">
                              <a class="menu-link" href="media.html">
                                   <span class="nav-icon">
                                        <i class="ri-image-circle-ai-line"></i>
                                   </span>
                                   <span class="nav-text"> Media </span>
                              </a>
                         </li>

                         <li class="menu-item">
                              <a class="menu-link" href="settings.html">
                                   <span class="nav-icon">
                                        <i class="ri-store-3-line"></i>
                                   </span>
                                   <span class="nav-text"> Store Settings </span>
                              </a>
                         </li>

                         <li class="menu-item">
                              <a class="menu-link" href="todo.html">
                                   <span class="nav-icon">
                                        <i class="ri-task-line"></i>
                                   </span>
                                   <span class="nav-text"> Todo </span>
                              </a>
                         </li>

                         <li class="menu-item">
                              <a class="menu-link" href="manage-apps.html">
                                   <span class="nav-icon">
                                        <i class="ri-apps-2-ai-line"></i>
                                   </span>
                                   <span class="nav-text"> Manage Apps </span>
                              </a>
                         </li>

                         <li class="menu-item">
                              <a class="menu-link menu-arrow" href="#sidebarInvoice" data-bs-toggle="collapse"
                                   role="button" aria-expanded="false" aria-controls="sidebarInvoice">
                                   <span class="nav-icon">
                                        <i class="ri-file-list-3-line"></i>
                                   </span>
                                   <span class="nav-text"> Invoices </span>
                              </a>
                              <div class="collapse" id="sidebarInvoice">
                                   <ul class="sub-menu-nav">
                                        <li class="sub-menu-item">
                                             <a class="sub-menu-link" href="invoices.html">Invoices</a>
                                        </li>
                                        <li class="sub-menu-item">
                                             <a class="sub-menu-link" href="invoice-details.html">Invoice Details</a>
                                        </li>
                                   </ul>
                              </div>
                         </li>

                         <li class="menu-item">
                              <a class="menu-link menu-arrow" href="#sidebarPages" data-bs-toggle="collapse"
                                   role="button" aria-expanded="false" aria-controls="sidebarPages">
                                   <span class="nav-icon">
                                        <i class="ri-pages-line"></i>
                                   </span>
                                   <span class="nav-text"> Pages </span>
                              </a>
                              <div class="collapse" id="sidebarPages">
                                   <ul class="sub-menu-nav">
                                        <li class="sub-menu-item">
                                             <a class="sub-menu-link" href="pages-starter.html">Welcome</a>
                                        </li>
                                        <li class="sub-menu-item">
                                             <a class="sub-menu-link" href="pages-profile.html">Profile</a>
                                        </li>
                                        <li class="sub-menu-item">
                                             <a class="sub-menu-link" href="pages-faqs.html">FAQs</a>
                                        </li>
                                        <li class="sub-menu-item">
                                             <a class="sub-menu-link" href="pages-gallery.html">Gallery</a>
                                        </li>
                                        <li class="sub-menu-item">
                                             <a class="sub-menu-link" href="pages-comingsoon.html">Coming Soon</a>
                                        </li>
                                        <li class="sub-menu-item">
                                             <a class="sub-menu-link" href="pages-timeline.html">Timeline</a>
                                        </li>
                                        <li class="sub-menu-item">
                                             <a class="sub-menu-link" href="pages-pricing.html">Pricing</a>
                                        </li>
                                        <li class="sub-menu-item">
                                             <a class="sub-menu-link" href="pages-maintenance.html">Maintenance</a>
                                        </li>
                                        <li class="sub-menu-item">
                                             <a class="sub-menu-link" href="{{ route('not-found') }}">404 Error</a>
                                        </li>
                                   </ul>
                              </div>
                         </li>

                         <li class="menu-item">
                              <a class="menu-link menu-arrow" href="#sidebarAuthentication" data-bs-toggle="collapse"
                                   role="button" aria-expanded="false" aria-controls="sidebarAuthentication">
                                   <span class="nav-icon">
                                        <i class="ri-shield-user-line"></i>
                                   </span>
                                   <span class="nav-text"> Authentication </span>
                              </a>
                              <div class="collapse" id="sidebarAuthentication">
                                   <ul class="sub-menu-nav">
                                        <li class="sub-menu-item">
                                             <a class="sub-menu-link" href="{{ route('login') }}">Sign In</a>
                                        </li>
                                        <li class="sub-menu-item">
                                             <a class="sub-menu-link" href="{{ route('register') }}">Sign Up</a>
                                        </li>
                                        <li class="sub-menu-item">
                                             <a class="sub-menu-link" href="auth-password.html">Reset Password</a>
                                        </li>
                                        <li class="sub-menu-item">
                                             <a class="sub-menu-link" href="auth-lock-screen.html">Lock Screen</a>
                                        </li>
                                   </ul>
                              </div>
                         </li>

                         <li class="menu-title">❋ UI Components ❋</li>

                         <li class="menu-item">
                              <a class="menu-link" href="ui-accordion.html">
                                   <span class="nav-icon">
                                        <i class="ri-shapes-line"></i>
                                   </span>
                                   <span class="nav-text"> Components </span>
                              </a>
                         </li>
                    </ul>
               </div>
          </div>

          <header class="topbar d-flex">
               <!-- Sidebar Logo -->
               <div class="logo-box">
                    <a href="{{ route('dashboard') }}" class="logo-dark">
                         <img src="assets/images/logo-sm.png" class="logo-sm" alt="logo sm">
                         <img src="assets/images/logo-dark.png" class="logo-lg" alt="logo dark">
                    </a>

                    <a href="{{ route('dashboard') }}" class="logo-light">
                         <img src="assets/images/logo-sm.png" class="logo-sm" alt="logo sm">
                         <img src="assets/images/logo-white.png" class="logo-lg" alt="logo light">
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

                              <!-- Notification -->
                              <div class="dropdown topbar-item">
                                   <button type="button" class="topbar-button" id="page-header-notifications-dropdown"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="topbar-badge border border-2 border-info rounded-pill">18<span
                                                  class="visually-hidden">unread messages</span></span>
                                   </button>
                                   <div class="dropdown-menu pt-0 dropdown-lg dropdown-menu-end"
                                        aria-labelledby="page-header-notifications-dropdown">
                                        <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                                             <div class="row align-items-center">
                                                  <div class="col">
                                                       <h6 class="m-0 fs-16 fw-semibold"> Notifications</h6>
                                                  </div>
                                             </div>
                                        </div>
                                        <div data-simplebar style="max-height: 280px;">
                                             <!-- Item -->
                                             <a href="javascript:void(0);"
                                                  class="dropdown-item py-3 border-bottom text-wrap">
                                                  <p class="mb-0"><span class="fw-medium">Olivia Bennett</span>
                                                       mentioned you in a comment <span>"This update really improves the
                                                            user experience! 🚀"</span></p>
                                             </a>
                                             <!-- Item -->
                                             <a href="javascript:void(0);" class="dropdown-item py-3 border-bottom">
                                                  <p class="mb-0 fw-semibold">Daniel Roberts</p>
                                                  <p class="mb-0 text-wrap">
                                                       Just sent over the revised proposal. Let me know your thoughts.
                                                  </p>
                                             </a>
                                             <!-- Item -->
                                             <a href="javascript:void(0);" class="dropdown-item py-3 border-bottom">
                                                  <p class="mb-0 fw-semibold">Rachel Green</p>
                                                  <p class="mb-0 text-wrap">
                                                       Approved your request for the new project timeline. ✅
                                                  </p>
                                             </a>
                                             <!-- Item -->
                                             <a href="javascript:void(0);" class="dropdown-item py-3 border-bottom">
                                                  <p class="mb-0 fw-semibold text-wrap">You have <b>8</b> new project
                                                       updates awaiting review.</p>
                                             </a>
                                             <!-- Item -->
                                             <a href="javascript:void(0);" class="dropdown-item py-3 border-bottom">
                                                  <p class="mb-0 fw-semibold">Ethan Williams</p>
                                                  <p class="mb-0 text-wrap">
                                                       Uploaded the latest marketing report for your review.
                                                  </p>
                                             </a>

                                        </div>
                                   </div>
                              </div>

                              <!-- User -->
                              <div class="dropdown topbar-item">
                                   <a type="button" class="topbar-button p-0" id="page-header-user-dropdown"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="d-flex align-items-center gap-2">
                                             <img class="rounded-circle" width="32"
                                                  src="assets/images/users/avatar-1.jpg" alt="user-image">
                                             <span class="d-lg-flex flex-column gap-1 d-none">
                                                  <h5 class="my-0 fs-13 text-uppercase text-reset fw-bold">Doris Lietz
                                                  </h5>
                                             </span>
                                        </span>
                                   </a>
                                   <div class="dropdown-menu dropdown-menu-end">

                                        <a class="dropdown-item" href="pages-profile.html">
                                             <i class="bx bx-user-circle fs-18 align-middle me-2"></i><span
                                                  class="align-middle">My Account</span>
                                        </a>

                                        <a class="dropdown-item" href="pages-pricing.html">
                                             <i class="bx bx-wallet fs-18 align-middle me-2"></i><span
                                                  class="align-middle">Pricing</span>
                                        </a>
                                        <a class="dropdown-item" href="pages-faqs.html">
                                             <i class="bx bx-help-circle fs-18 align-middle me-2"></i><span
                                                  class="align-middle">Help</span>
                                        </a>
                                        <a class="dropdown-item" href="pages-gallery.html">
                                             <i class="bx bx-photo-album fs-18 align-middle me-2"></i>
                                             <span class="align-middle">Photos</span>
                                             <span class="align-middle float-end badge badge-soft-danger">New</span>
                                        </a>

                                        <div class="dropdown-divider my-1"></div>

                                        <a class="dropdown-item" href="auth-lock-screen.html">
                                             <i class="bx bx-lock fs-18 align-middle me-2"></i><span
                                                  class="align-middle">Lock screen</span>
                                        </a>
                                        <a class="dropdown-item" href="{{ route('login') }}">
                                             <i class="bx bx-log-out fs-18 align-middle me-2"></i><span
                                                  class="align-middle">Logout</span>
                                        </a>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </header>

          <!-- Start Content here -->
          <div class="page-container">

               <!-- Start Container Fluid -->
               <div class="page-content">
                    <div class="row">
                         <div class="col-12 col-xl-8">
                              <div class="row">

                                   <div class="col-12 col-md-6 col-lg-6">
                                        <div class="card pb-0">
                                             <div class="card-body">
                                                  <div class="d-flex align-items-center justify-content-between">
                                                       <div>
                                                            <p class="mb-3 card-title">Total Revenue</p>
                                                            <h4
                                                                 class="fw-bold text-primary d-flex align-items-center gap-2 mb-0">
                                                                 $35,428.09</h4>
                                                       </div>
                                                       <div>
                                                            <div class="py-2 px-3 rounded-circle bg-primary">
                                                                 <i
                                                                      class="ri-money-dollar-circle-line fs-25 text-white"></i>
                                                            </div>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>

                                   <div class="col-12 col-md-6 col-lg-6">
                                        <div class="card pb-0">
                                             <div class="card-body">
                                                  <div class="d-flex align-items-center justify-content-between">
                                                       <div>
                                                            <p class="mb-3 card-title">Total Orders</p>
                                                            <h4 class="fw-bold d-flex align-items-center gap-2 mb-0">
                                                                 4526</h4>
                                                       </div>
                                                       <div>
                                                            <div class="py-2 px-3 rounded-circle bg-secondary">
                                                                 <i class="ri-restaurant-2-line fs-25 text-white"></i>
                                                            </div>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>

                                   <div class="col-12 col-md-6 col-lg-6">
                                        <div class="card pb-0">
                                             <div class="card-body">
                                                  <div class="d-flex align-items-center justify-content-between">
                                                       <div>
                                                            <p class="mb-3 card-title">Total Customers</p>
                                                            <h4 class="fw-bold d-flex align-items-center gap-2 mb-0">
                                                                 5736</h4>
                                                       </div>
                                                       <div>
                                                            <div class="py-2 px-3 rounded-circle bg-secondary">
                                                                 <i class="ri-group-2-line fs-25 text-white"></i>
                                                            </div>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>

                                   <div class="col-12 col-md-6 col-lg-6">
                                        <div class="card pb-0">
                                             <div class="card-body">
                                                  <div class="d-flex align-items-center justify-content-between">
                                                       <div>
                                                            <p class="mb-3 card-title">Cancelled Orders</p>
                                                            <h4
                                                                 class="fw-bold text-primary d-flex align-items-center gap-2 mb-0">
                                                                 1310</h4>
                                                       </div>
                                                       <div>
                                                            <div class="py-2 px-3 rounded-circle bg-primary">
                                                                 <i class="ri-calendar-close-line fs-25 text-white"></i>
                                                            </div>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>

                                   <div class="col-12">
                                        <div class="card">
                                             <div class="card-header d-flex align-items-center justify-content-between">
                                                  <div>
                                                       <h4 class="card-title mb-0">Revenue Summary</h4>
                                                  </div>
                                                  <div class="dropdown">
                                                       <a href="#"
                                                            class="dropdown-toggle btn btn-sm btn-outline-light rounded"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            Monthly
                                                       </a>
                                                       <div class="dropdown-menu dropdown-menu-end">
                                                            <a href="#!" class="dropdown-item">Week</a>
                                                            <a href="#!" class="dropdown-item">Months</a>
                                                            <a href="#!" class="dropdown-item">Years</a>
                                                       </div>
                                                  </div>
                                             </div>

                                             <div class="card-body">
                                                  <div id="revenue_summary" class="apex-charts"></div>
                                             </div>
                                        </div>
                                   </div>

                                   <div class="col-12 col-lg-6">
                                        <div class="card">
                                             <div class="card-header d-flex align-items-center justify-content-between">
                                                  <div>
                                                       <h4 class="card-title mb-0">Daily Delivery Chart</h4>
                                                  </div>
                                                  <div class="dropdown">
                                                       <a href="#"
                                                            class="dropdown-toggle btn btn-sm btn-link text-uppercase fw-semibold"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            Weekly
                                                       </a>
                                                       <div class="dropdown-menu dropdown-menu-end">
                                                            <a href="#!" class="dropdown-item">Week</a>
                                                            <a href="#!" class="dropdown-item">Months</a>
                                                            <a href="#!" class="dropdown-item">Years</a>
                                                       </div>
                                                  </div>
                                             </div>
                                             <div class="card-body ps-0">
                                                  <div class="text-center">
                                                       <p class="text-muted mb-0">Yeah! You have delivered <span
                                                                 class="text-primary fw-bold">910</span> orders today
                                                       </p>
                                                  </div>
                                                  <div id="basic-heatmap" class="apex-charts"></div>
                                             </div>
                                        </div>
                                   </div>

                                   <div class="col-12 col-lg-6">
                                        <div class="card">
                                             <div class="card-header d-flex align-items-center justify-content-between">
                                                  <div>
                                                       <h4 class="card-title mb-0">Orders Overview</h4>
                                                  </div>
                                                  <div class="dropdown">
                                                       <a href="#"
                                                            class="dropdown-toggle btn btn-sm btn-link text-uppercase fw-semibold"
                                                            data-bs-toggle="dropdown" aria-expanded="false">Weekly</a>
                                                       <div class="dropdown-menu dropdown-menu-end">
                                                            <a href="#!" class="dropdown-item">Week</a>
                                                            <a href="#!" class="dropdown-item">Months</a>
                                                            <a href="#!" class="dropdown-item">Years</a>
                                                       </div>
                                                  </div>
                                             </div>
                                             <div class="card-body">
                                                  <div class="text-center">
                                                       <p class="text-muted mb-0">Yeah! You have received <span
                                                                 class="text-success fw-bold">+33</span> new orders
                                                            today</p>
                                                  </div>
                                                  <div id="datalabels-column2" class="apex-charts"
                                                       data-colors="#604ae3"></div>
                                             </div>
                                        </div>
                                   </div>

                                   <div class="col-12">
                                        <div class="card">
                                             <div class="card-header d-flex align-items-center justify-content-between">
                                                  <div>
                                                       <h4 class="card-title mb-0">Delivered Status</h4>
                                                  </div>
                                                  <div class="dropdown">
                                                       <a href="#"
                                                            class="dropdown-toggle text-dark btn btn-sm btn-link text-uppercase fw-semibold px-0"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            Daily
                                                       </a>
                                                       <div class="dropdown-menu dropdown-menu-end">
                                                            <a href="#!" class="dropdown-item">Week</a>
                                                            <a href="#!" class="dropdown-item">Months</a>
                                                            <a href="#!" class="dropdown-item">Years</a>
                                                       </div>
                                                  </div>
                                             </div>

                                             <div class="card-body p-0">
                                                  <div class="table-responsive">
                                                       <table class="table table-sm table-hover mb-0">
                                                            <thead>
                                                                 <tr>
                                                                      <th>Date</th>
                                                                      <th>Payment Via</th>
                                                                      <th>Status</th>
                                                                      <th>Amount ($)</th>
                                                                 </tr>
                                                            </thead>

                                                            <tbody>
                                                                 <tr>
                                                                      <td>2025-08-06</td>
                                                                      <td>Stripe</td>
                                                                      <td><span
                                                                                class="badge badge-soft-success">Success</span>
                                                                      </td>
                                                                      <td>210.00</td>
                                                                 </tr>
                                                                 <tr>
                                                                      <td>2025-08-05</td>
                                                                      <td>UPI</td>
                                                                      <td><span
                                                                                class="badge badge-soft-warning">Pending</span>
                                                                      </td>
                                                                      <td>135.50</td>
                                                                 </tr>
                                                                 <tr>
                                                                      <td>2025-08-04</td>
                                                                      <td>PayPal</td>
                                                                      <td><span
                                                                                class="badge badge-soft-danger">Failed</span>
                                                                      </td>
                                                                      <td>320.75</td>
                                                                 </tr>
                                                                 <tr>
                                                                      <td>2025-08-03</td>
                                                                      <td>Debit Card</td>
                                                                      <td><span
                                                                                class="badge badge-soft-success">Success</span>
                                                                      </td>
                                                                      <td>89.99</td>
                                                                 </tr>
                                                                 <tr>
                                                                      <td>2025-08-02</td>
                                                                      <td>Bank Transfer</td>
                                                                      <td><span
                                                                                class="badge badge-soft-success">Success</span>
                                                                      </td>
                                                                      <td>150.45</td>
                                                                 </tr>
                                                                 <tr>
                                                                      <td>2025-08-01</td>
                                                                      <td>Credit Card</td>
                                                                      <td><span
                                                                                class="badge badge-soft-danger">Failed</span>
                                                                      </td>
                                                                      <td>400.20</td>
                                                                 </tr>
                                                                 <tr>
                                                                      <td>2025-07-31</td>
                                                                      <td>Cash</td>
                                                                      <td><span
                                                                                class="badge badge-soft-warning">Pending</span>
                                                                      </td>
                                                                      <td>95.00</td>
                                                                 </tr>
                                                            </tbody>
                                                       </table>
                                                  </div>
                                             </div>

                                             <div class="card-footer border-top text-center p-3">
                                                  <a href="#!"
                                                       class="link-primary text-decoration-underline fw-medium">Show
                                                       More <i class="ri-arrow-right-up-line"></i></a>
                                             </div>
                                        </div>
                                   </div>

                              </div>
                         </div>

                         <div class="col-12 col-xl-4">
                              <div class="row">
                                   <div class="col-12">
                                        <div class="card">
                                             <div class="card-header d-flex align-items-center justify-content-between">
                                                  <div>
                                                       <h4 class="card-title mb-0">Other Outlets</h4>
                                                  </div>
                                                  <div class="dropdown">
                                                       <a href="#" class="dropdown-toggle rounded arrow-none"
                                                            data-bs-toggle="dropdown" aria-expanded="true">
                                                            <i class="ri-edit-box-line fs-20"></i>
                                                       </a>
                                                       <div class="dropdown-menu dropdown-menu-end">
                                                            <a href="javascript:void(0);" class="dropdown-item">Create
                                                                 New Outlet</a>
                                                            <a href="javascript:void(0);" class="dropdown-item">New
                                                                 Areas</a>
                                                       </div>
                                                  </div>
                                             </div>

                                             <div style="height: 333px;" data-simplebar>
                                                  <div class="border rounded-2 p-3 my-3 mx-4">
                                                       <h6 class="text-uppercase fw-bold">Toronto - Canada <span
                                                                 class="ms-auto fw-medium float-end"><i
                                                                      class="ri-star-fill text-warning"></i> 4.2</span>
                                                       </h6>
                                                       <span class="fw-medium fs-18"><i
                                                                 class="ri-map-pin-range-line"></i></span>
                                                       <span class="fw-medium ms-1">88 Bloor St W, Toronto, ON M5S
                                                            1M5</span>
                                                       <div class="mt-1">
                                                            <span class="fw-medium fs-18"><i
                                                                      class="ri-phone-line"></i></span>
                                                            <a href="#!" class="fw-medium link-primary ms-1">+1
                                                                 416-555-1122</a>
                                                       </div>
                                                  </div>

                                                  <div class="border rounded-2 p-3 my-3 mx-4">
                                                       <h6 class="text-uppercase fw-bold">Berlin - Germany <span
                                                                 class="ms-auto fw-medium float-end"><i
                                                                      class="ri-star-fill text-warning"></i> 4.6</span>
                                                       </h6>
                                                       <span class="fw-medium fs-18"><i
                                                                 class="ri-map-pin-range-line"></i></span>
                                                       <span class="fw-medium ms-1">Kurfürstendamm 21, 10719
                                                            Berlin</span>
                                                       <div class="mt-1">
                                                            <span class="fw-medium fs-18"><i
                                                                      class="ri-phone-line"></i></span>
                                                            <a href="#!" class="fw-medium link-primary ms-1">+49 30 5555
                                                                 3333</a>
                                                       </div>
                                                  </div>

                                                  <div class="border rounded-2 p-3 my-3 mx-4">
                                                       <h6 class="text-uppercase fw-bold">Dubai - UAE <span
                                                                 class="ms-auto fw-medium float-end"><i
                                                                      class="ri-star-fill text-warning"></i> 4.9</span>
                                                       </h6>
                                                       <span class="fw-medium fs-18"><i
                                                                 class="ri-map-pin-range-line"></i></span>
                                                       <span class="fw-medium ms-1">Sheikh Zayed Rd, Dubai Marina</span>
                                                       <div class="mt-1">
                                                            <span class="fw-medium fs-18"><i
                                                                      class="ri-phone-line"></i></span>
                                                            <a href="#!" class="fw-medium link-primary ms-1">+971 4 555
                                                                 7890</a>
                                                       </div>
                                                  </div>

                                                  <div class="border rounded-2 p-3 my-3 mx-4">
                                                       <h6 class="text-uppercase fw-bold">Mumbai - India <span
                                                                 class="ms-auto fw-medium float-end"><i
                                                                      class="ri-star-fill text-warning"></i> 4.5</span>
                                                       </h6>
                                                       <span class="fw-medium fs-18"><i
                                                                 class="ri-map-pin-range-line"></i></span>
                                                       <span class="fw-medium ms-1">90 Linking Rd, Bandra West,
                                                            Mumbai</span>
                                                       <div class="mt-1">
                                                            <span class="fw-medium fs-18"><i
                                                                      class="ri-phone-line"></i></span>
                                                            <a href="#!" class="fw-medium link-primary ms-1">+91 22 5555
                                                                 1212</a>
                                                       </div>
                                                  </div>

                                                  <div class="border rounded-2 p-3 my-3 mx-4">
                                                       <h6 class="text-uppercase fw-bold">Tokyo - Japan <span
                                                                 class="ms-auto fw-medium float-end"><i
                                                                      class="ri-star-fill text-warning"></i> 4.7</span>
                                                       </h6>
                                                       <span class="fw-medium fs-18"><i
                                                                 class="ri-map-pin-range-line"></i></span>
                                                       <span class="fw-medium ms-1">1 Chome-1-2 Oshiage, Sumida City,
                                                            Tokyo</span>
                                                       <div class="mt-1">
                                                            <span class="fw-medium fs-18"><i
                                                                      class="ri-phone-line"></i></span>
                                                            <a href="#!" class="fw-medium link-primary ms-1">+81
                                                                 3-5555-6789</a>
                                                       </div>
                                                  </div>

                                                  <div class="border rounded-2 p-3 my-3 mx-4">
                                                       <h6 class="text-uppercase fw-bold">Paris - France <span
                                                                 class="ms-auto fw-medium float-end"><i
                                                                      class="ri-star-fill text-warning"></i> 4.4</span>
                                                       </h6>
                                                       <span class="fw-medium fs-18"><i
                                                                 class="ri-map-pin-range-line"></i></span>
                                                       <span class="fw-medium ms-1">99 Champs-Élysées, 75008
                                                            Paris</span>
                                                       <div class="mt-1">
                                                            <span class="fw-medium fs-18"><i
                                                                      class="ri-phone-line"></i></span>
                                                            <a href="#!" class="fw-medium link-primary ms-1">+33 1 55 55
                                                                 5555</a>
                                                       </div>
                                                  </div>
                                             </div>

                                             <div class="card-footer border-top text-center p-3">
                                                  <a href="#!"
                                                       class="link-primary text-decoration-underline fw-medium">Show
                                                       More <i class="ri-arrow-right-up-line"></i></a>
                                             </div>
                                        </div>
                                   </div>

                                   <div class="col-12">
                                        <div class="card">
                                             <div class="card-header d-flex align-items-center justify-content-between">
                                                  <div>
                                                       <h4 class="card-title mb-0">Daily Trending Menu</h4>
                                                  </div>
                                                  <div>
                                                       <a href="#!"
                                                            class="text-dark btn btn-sm btn-link text-uppercase fw-semibold px-0">View
                                                            Menu <i class="ri-arrow-right-line"></i></a>
                                                  </div>
                                             </div>
                                             <div class="card-body my-3 mx-4 p-0">
                                                  <div
                                                       class="d-flex flex-wrap align-items-center gap-3 border rounded-2 p-3 mb-3">
                                                       <div
                                                            class="rounded bg-warning-subtle avatar-lg d-flex align-items-center justify-content-center">
                                                            <img src="assets/images/food-icon/pic12.png" alt=""
                                                                 class="avatar-lg">
                                                       </div>
                                                       <div>
                                                            <a href="#!" class="text-dark fs-14 fw-medium">Italian
                                                                 Burata Pizza</a>
                                                            <p class="fs-14 my-1">$12.00</p>
                                                            <a href="#!"
                                                                 class="link-dark link-offset-2 text-decoration-underline fw-medium">View
                                                                 Details <i class="ri-arrow-right-up-line"></i></a>
                                                       </div>
                                                       <div class="ms-auto text-lg-end">
                                                            <p class="text-primary fs-16 fw-medium">#1</p>
                                                            <p class="mb-0 fw-medium">Orders: 22x</p>
                                                       </div>
                                                  </div>
                                                  <div
                                                       class="d-flex flex-wrap align-items-center gap-3 border rounded-2 p-3 mb-3">
                                                       <div
                                                            class="rounded bg-warning-subtle avatar-lg d-flex align-items-center justify-content-center">
                                                            <img src="assets/images/food-icon/pic19.png" alt=""
                                                                 class="avatar-lg">
                                                       </div>
                                                       <div>
                                                            <a href="#!" class="text-dark fs-14 fw-medium">Veg Indian
                                                                 Thali</a>
                                                            <p class="fs-14 my-1">$14.00</p>
                                                            <a href="#!"
                                                                 class="link-dark link-offset-2 text-decoration-underline fw-medium">View
                                                                 Details <i class="ri-arrow-right-up-line"></i></a>
                                                       </div>
                                                       <div class="ms-auto text-lg-end">
                                                            <p class="text-primary fs-16 fw-medium">#2</p>
                                                            <p class="mb-0 fw-medium">Orders: 13x</p>
                                                       </div>
                                                  </div>
                                                  <div
                                                       class="d-flex flex-wrap align-items-center gap-3 border rounded-2 p-3 mb-0">
                                                       <div
                                                            class="rounded bg-warning-subtle avatar-lg d-flex align-items-center justify-content-center">
                                                            <img src="assets/images/food-icon/pic17.png" alt=""
                                                                 class="avatar-lg">
                                                       </div>
                                                       <div>
                                                            <a href="#!" class="text-dark fs-14 fw-medium">Chocolate
                                                                 Lava Cake</a>
                                                            <p class="fs-14 my-1">$10.00</p>
                                                            <a href="#!"
                                                                 class="link-dark link-offset-2 text-decoration-underline fw-medium">View
                                                                 Details <i class="ri-arrow-right-up-line"></i></a>
                                                       </div>
                                                       <div class="ms-auto text-lg-end">
                                                            <p class="text-primary fs-16 fw-medium">#3</p>
                                                            <p class="mb-0 fw-medium">Orders: 10x</p>
                                                       </div>
                                                  </div>
                                                  <div
                                                       class="d-flex flex-wrap align-items-center gap-3 border rounded-2 p-3 mb-3">
                                                       <div
                                                            class="rounded bg-warning-subtle avatar-lg d-flex align-items-center justify-content-center">
                                                            <img src="assets/images/food-icon/pic12.png" alt=""
                                                                 class="avatar-lg">
                                                       </div>
                                                       <div>
                                                            <a href="#!" class="text-dark fs-14 fw-medium">Italian
                                                                 Burata Pizza</a>
                                                            <p class="fs-14 my-1">$12.00</p>
                                                            <a href="#!"
                                                                 class="link-dark link-offset-2 text-decoration-underline fw-medium">View
                                                                 Details <i class="ri-arrow-right-up-line"></i></a>
                                                       </div>
                                                       <div class="ms-auto text-lg-end">
                                                            <p class="text-primary fs-16 fw-medium">#1</p>
                                                            <p class="mb-0 fw-medium">Orders: 22x</p>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>

                                   <div class="col-12">
                                        <div class="card">
                                             <div class="card-header d-flex align-items-center justify-content-between">
                                                  <div>
                                                       <h4 class="card-title mb-0">Special Menu</h4>
                                                  </div>
                                                  <div>
                                                       <a href="#!"
                                                            class="text-dark btn btn-sm btn-link text-uppercase fw-semibold px-0">View
                                                            Menu <i class="ri-arrow-right-line"></i></a>
                                                  </div>
                                             </div>
                                             <div style="height: 400px;" data-simplebar>
                                                  <div
                                                       class="d-flex flex-wrap align-items-center gap-2 border rounded-2 p-3 my-3 mx-4">
                                                       <div>
                                                            <img src="assets/images/food-icon/pic16.png" alt=""
                                                                 class="avatar-lg">
                                                       </div>
                                                       <div>
                                                            <a href="#!" class="text-dark fs-15 fw-medium">Pasta &
                                                                 Spaghetti</a>
                                                            <p class="mb-2">16+ Options</p>
                                                            <p class="mb-0 fw-semibold"><i
                                                                      class="ri-star-fill text-warning me-1 fs-15"></i>4.3/5
                                                            </p>
                                                       </div>
                                                  </div>

                                                  <div
                                                       class="d-flex flex-wrap align-items-center gap-2 border rounded-2 p-3 mb-3 mx-4">
                                                       <div>
                                                            <img src="assets/images/food-icon/pic13.png" alt=""
                                                                 class="avatar-lg">
                                                       </div>
                                                       <div>
                                                            <a href="#!" class="text-dark fs-15 fw-medium">Chinese
                                                                 Platter</a>
                                                            <p class="mb-2">35+ Options</p>
                                                            <p class="mb-0 fw-semibold"><i
                                                                      class="ri-star-fill text-warning me-1 fs-15"></i>4.3/5
                                                            </p>
                                                       </div>
                                                  </div>

                                                  <div
                                                       class="d-flex flex-wrap align-items-center gap-2 border rounded-2 p-3 mb-3 mx-4">
                                                       <div>
                                                            <img src="assets/images/food-icon/pic17.png" alt=""
                                                                 class="avatar-lg">
                                                       </div>
                                                       <div>
                                                            <a href="#!" class="text-dark fs-15 fw-medium">Chocolate
                                                                 Lava Cake</a>
                                                            <p class="mb-2">40+ Options</p>
                                                            <p class="mb-0 fw-semibold"><i
                                                                      class="ri-star-fill text-warning me-1 fs-15"></i>4.3/5
                                                            </p>
                                                       </div>
                                                  </div>

                                                  <div
                                                       class="d-flex flex-wrap align-items-center gap-2 border rounded-2 p-3 mb-3 mx-4">
                                                       <div>
                                                            <img src="assets/images/food-icon/pic15.png" alt=""
                                                                 class="avatar-lg">
                                                       </div>
                                                       <div>
                                                            <a href="#!" class="text-dark fs-15 fw-medium">Tacos &
                                                                 Burritos</a>
                                                            <p class="mb-2">24+ Options</p>
                                                            <p class="mb-0 fw-semibold"><i
                                                                      class="ri-star-fill text-warning me-1 fs-15"></i>4.3/5
                                                            </p>
                                                       </div>
                                                  </div>

                                                  <div
                                                       class="d-flex flex-wrap align-items-center gap-2 border rounded-2 p-3 mb-3 mx-4">
                                                       <div>
                                                            <img src="assets/images/food-icon/pic16.png" alt=""
                                                                 class="avatar-lg">
                                                       </div>
                                                       <div>
                                                            <a href="#!" class="text-dark fs-15 fw-medium">Pizza</a>
                                                            <p class="mb-2">30+ Options</p>
                                                            <p class="mb-0 fw-semibold"><i
                                                                      class="ri-star-fill text-warning me-1 fs-15"></i>4.5/5
                                                            </p>
                                                       </div>
                                                  </div>

                                                  <div
                                                       class="d-flex flex-wrap align-items-center gap-2 border rounded-2 p-3 mb-3 mx-4">
                                                       <div>
                                                            <img src="assets/images/food-icon/pic17.png" alt=""
                                                                 class="avatar-lg">
                                                       </div>
                                                       <div>
                                                            <a href="#!" class="text-dark fs-15 fw-medium">Sushi</a>
                                                            <p class="mb-2">20+ Options</p>
                                                            <p class="mb-0 fw-semibold"><i
                                                                      class="ri-star-fill text-warning me-1 fs-15"></i>4.7/5
                                                            </p>
                                                       </div>
                                                  </div>

                                                  <div
                                                       class="d-flex flex-wrap align-items-center gap-2 border rounded-2 p-3 mb-3 mx-4">
                                                       <div>
                                                            <img src="assets/images/food-icon/pic19.png" alt=""
                                                                 class="avatar-lg">
                                                       </div>
                                                       <div>
                                                            <a href="#!" class="text-dark fs-15 fw-medium">Pasta</a>
                                                            <p class="mb-2">18+ Options</p>
                                                            <p class="mb-0 fw-semibold"><i
                                                                      class="ri-star-fill text-warning me-1 fs-15"></i>4.6/5
                                                            </p>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
               <!-- End Container Fluid -->
               <!-- ========== Footer Start ========== -->
               <footer class="footer">
                    <div class="container">
                         <div class="row">
                              <div class="col-12 text-center">
                                   <script>document.write(new Date().getFullYear())</script> &copy; Metor. All rights
                                   reserved by FoxPixel</a>
                              </div>
                         </div>
                    </div>
               </footer>
               <!-- ========== Footer End ========== -->
          </div>
          <!-- End Page Content -->
     </div>
     <!-- END Wrapper -->

     <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
     <script src="{{ asset('assets/js/app.min.js') }}"></script>

     <!-- Dashboard Js -->
     <script src="{{ asset('assets/js/pages/dashboard.js') }}"></script>

</body>


<!-- Mirrored from foxpixel.vercel.app/metor/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 16 Jan 2026 18:56:31 GMT -->

</html>