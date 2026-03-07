@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')
<style>
    /* Dashboard-only polish: professional look, no content change */
    .dashboard-welcome {
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.08) 0%, rgba(147, 51, 234, 0.06) 50%, rgba(79, 70, 229, 0.04) 100%);
        border: 1px solid rgba(79, 70, 229, 0.12);
        border-radius: 20px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.75rem;
    }
    [data-bs-theme="dark"] .dashboard-welcome {
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.15) 0%, rgba(147, 51, 234, 0.08) 100%);
        border-color: rgba(255,255,255,0.06);
    }
    .dashboard-welcome .welcome-date { font-size: 12px; color: var(--bs-secondary); font-weight: 600; }
    .dashboard-stat-card {
        border-radius: 20px !important;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.04) !important;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .dashboard-stat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(79, 70, 229, 0.12) !important;
    }
    [data-bs-theme="dark"] .dashboard-stat-card { border-color: rgba(255,255,255,0.06) !important; }
    @media (max-width: 992px) {
        .dashboard-welcome { padding: 1rem 1.25rem; margin-bottom: 1.25rem; }
        .dashboard-stat-card .card-body { padding: 1.25rem !important; }
    }
    @media (max-width: 768px) {
        .dashboard-quick-links a { padding: 6px 10px; font-size: 12px; }
        .dashboard-stat-card .fs-28 { font-size: 22px !important; }
    }
    .dashboard-section-title {
        font-size: 1.05rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        color: var(--bs-body-color);
    }
    .dashboard-chart-card {
        border-radius: 20px !important;
        border: 1px solid rgba(0,0,0,0.04) !important;
        overflow: hidden;
    }
    [data-bs-theme="dark"] .dashboard-chart-card { border-color: rgba(255,255,255,0.06) !important; }
    .dashboard-sidebar-card {
        border-radius: 20px !important;
        border: 1px solid rgba(0,0,0,0.04) !important;
        overflow: hidden;
    }
    [data-bs-theme="dark"] .dashboard-sidebar-card { border-color: rgba(255,255,255,0.06) !important; }
    .dashboard-table-card .table thead th { font-size: 11px; letter-spacing: 0.06em; }
    .dashboard-table-card .table tbody tr { transition: background 0.2s ease; }
    .dashboard-table-card .table tbody tr:hover { background: rgba(79, 70, 229, 0.04) !important; }
    [data-bs-theme="dark"] .dashboard-table-card .table tbody tr:hover { background: rgba(255,255,255,0.04) !important; }
    .dashboard-quick-links a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        color: var(--bs-body-color);
        text-decoration: none;
        background: rgba(255,255,255,0.8);
        border: 1px solid rgba(0,0,0,0.06);
        transition: all 0.2s ease;
    }
    .dashboard-quick-links a:hover {
        background: var(--bs-primary);
        color: #fff;
        border-color: var(--bs-primary);
        transform: translateY(-2px);
    }
    [data-bs-theme="dark"] .dashboard-quick-links a {
        background: rgba(255,255,255,0.05);
        border-color: rgba(255,255,255,0.08);
    }
    [data-bs-theme="dark"] .dashboard-quick-links a:hover {
        background: var(--bs-primary);
        color: #fff;
        border-color: var(--bs-primary);
    }
    .outlet-item:hover, .fast-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.06);
    }
    [data-bs-theme="dark"] .outlet-item:hover, [data-bs-theme="dark"] .fast-item:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.25);
    }
</style>

        <!-- Welcome strip - extra only, no content removed -->
        <div class="dashboard-welcome d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <p class="dashboard-section-title mb-1">Welcome back, {{ Auth::user()->name }}</p>
                <p class="welcome-date mb-0">{{ now()->format('l, F j, Y') }}</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary border-0 px-3 py-2 rounded-pill fw-semibold">
                    <i class="ri-dashboard-2-line me-1"></i> Analytics
                </span>
            </div>
        </div>

        <!-- Quick links - extra only -->
        <div class="dashboard-quick-links d-flex flex-wrap gap-2 mb-4">
            <a href="{{ route('routes.index') }}"><i class="ri-route-line"></i> Routes</a>
            <a href="{{ route('products.index') }}"><i class="ri-shopping-bag-3-line"></i> Items</a>
            <a href="{{ route('customers.index') }}"><i class="ri-group-2-line"></i> Customers</a>
            <a href="{{ route('vendors.index') }}"><i class="ri-user-settings-line"></i> Vendors</a>
            <a href="{{ route('approvals.index') }}"><i class="ri-user-follow-line"></i> Approvals</a>
        </div>

        <div class="row g-5">
            <div class="col-xl-9 col-lg-8">
                <div class="mb-5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="fw-bold text-dark-emphasis mb-0">Business Intelligence</h4>
                        <span class="badge-soft bg-primary-subtle text-primary">
                            <span class="pulse"></span> Live Stats
                        </span>
                    </div>
                    <p class="text-muted small">Comprehensive overview of your distribution network's performance</p>
                </div>
                
                <div class="row g-4 mb-5">
                    <div class="col-12 col-md-6">
                        <div class="card border-0 h-100 dashboard-stat-card">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="icon-shape bg-primary-subtle rounded-4 text-primary shadow-sm d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                                        <i class="ri-money-dollar-circle-fill fs-28"></i>
                                    </div>
                                    <span class="badge-soft bg-success-subtle text-success">
                                        <i class="ri-arrow-up-line"></i> 12.5%
                                    </span>
                                </div>
                                <div>
                                    <p class="mb-1 text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.05em;">Total Revenue</p>
                                    <h3 class="fw-bold text-dark-emphasis mb-0 fs-28">Rs. 0.00</h3>
                                    <p class="text-muted extra-small mt-2 mb-0">Initial Phase - Tracking Pending</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="card border-0 h-100 dashboard-stat-card">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="icon-shape bg-info-subtle rounded-4 text-info shadow-sm d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                                        <i class="ri-shopping-cart-2-fill fs-28"></i>
                                    </div>
                                    <span class="badge-soft bg-info-subtle text-info">
                                        <i class="ri-loader-4-line"></i> 158
                                    </span>
                                </div>
                                <div>
                                    <p class="mb-1 text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.05em;">Total Products</p>
                                    <h3 class="fw-bold text-dark-emphasis mb-0 fs-28">{{ $productCount }}</h3>
                                    <p class="text-muted extra-small mt-2 mb-0">Active inventory items</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="card border-0 h-100 dashboard-stat-card">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="icon-shape bg-success-subtle rounded-4 text-success shadow-sm d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                                        <i class="ri-user-heart-fill fs-28"></i>
                                    </div>
                                    <span class="badge-soft bg-success-subtle text-success">
                                        +24 today
                                    </span>
                                </div>
                                <div>
                                    <p class="mb-1 text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.05em;">Registered Customers</p>
                                    <h3 class="fw-bold text-dark-emphasis mb-0 fs-28">{{ $customerCount }}</h3>
                                    <p class="text-muted extra-small mt-2 mb-0">Total active client base</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="card border-0 h-100 dashboard-stat-card">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="icon-shape bg-warning-subtle rounded-4 text-warning shadow-sm d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                                        <i class="ri-store-2-fill fs-28"></i>
                                    </div>
                                    <span class="badge-soft bg-warning-subtle text-warning">
                                        3 Pending
                                    </span>
                                </div>
                                <div>
                                    <p class="mb-1 text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.05em;">Registered Vendors</p>
                                    <h3 class="fw-bold text-dark-emphasis mb-0 fs-28">{{ $vendorCount }}</h3>
                                    <p class="text-muted extra-small mt-2 mb-0">Certified distribution partners</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-12">
                         <div class="card border-0 shadow-sm dashboard-chart-card">
                              <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex align-items-center justify-content-between">
                                   <div>
                                        <h5 class="card-title mb-0 fw-bold text-dark">Revenue Summary</h5>
                                   </div>
                                   <div class="dropdown">
                                        <a href="#" class="dropdown-toggle btn btn-sm btn-light border-0 px-3 rounded-pill fw-semibold"
                                             data-bs-toggle="dropdown" aria-expanded="false">
                                             Monthly
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                                             <a href="#!" class="dropdown-item py-2">Week</a>
                                             <a href="#!" class="dropdown-item py-2">Months</a>
                                             <a href="#!" class="dropdown-item py-2">Years</a>
                                        </div>
                                   </div>
                              </div>

                              <div class="card-body px-4 pb-4">
                                   <div id="revenue_summary" class="apex-charts"></div>
                              </div>
                         </div>
                    </div>

                    <div class="col-12 col-lg-6">
                         <div class="card border-0 shadow-sm h-100 dashboard-chart-card">
                              <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex align-items-center justify-content-between">
                                   <div>
                                        <h5 class="card-title mb-0 fw-bold text-dark">Daily Delivery</h5>
                                   </div>
                                   <div class="dropdown">
                                        <a href="#" class="dropdown-toggle btn btn-sm btn-link text-decoration-none text-muted fw-bold"
                                             data-bs-toggle="dropdown" aria-expanded="false">
                                             Weekly
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                                             <a href="#!" class="dropdown-item py-2">Week</a>
                                             <a href="#!" class="dropdown-item py-2">Months</a>
                                             <a href="#!" class="dropdown-item py-2">Years</a>
                                        </div>
                                   </div>
                              </div>
                              <div class="card-body px-4 pb-4">
                                   <div class="mb-4">
                                        <p class="text-muted mb-0 small fw-medium">You have delivered <span
                                                  class="text-primary fw-bold">910</span> orders today
                                        </p>
                                   </div>
                                   <div id="basic-heatmap" class="apex-charts"></div>
                              </div>
                         </div>
                    </div>

                    <div class="col-12 col-lg-6">
                         <div class="card border-0 shadow-sm h-100 dashboard-chart-card">
                              <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex align-items-center justify-content-between">
                                   <div>
                                        <h5 class="card-title mb-0 fw-bold text-dark">Orders Overview</h5>
                                   </div>
                                   <div class="dropdown">
                                        <a href="#" class="dropdown-toggle btn btn-sm btn-link text-decoration-none text-muted fw-bold"
                                             data-bs-toggle="dropdown" aria-expanded="false">Weekly</a>
                                        <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                                             <a href="#!" class="dropdown-item py-2">Week</a>
                                             <a href="#!" class="dropdown-item py-2">Months</a>
                                             <a href="#!" class="dropdown-item py-2">Years</a>
                                        </div>
                                   </div>
                              </div>
                              <div class="card-body px-4 pb-4">
                                   <div class="mb-4">
                                        <p class="text-muted mb-0 small fw-medium">Received <span
                                                  class="text-success fw-bold">+33</span> new orders today</p>
                                   </div>
                                   <div id="datalabels-column2" class="apex-charts" data-colors="#6366f1"></div>
                              </div>
                         </div>
                    </div>

                    <div class="col-12">
                         <div class="card border-0 shadow-sm dashboard-chart-card dashboard-table-card">
                              <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex align-items-center justify-content-between">
                                   <div>
                                        <h5 class="card-title mb-0 fw-bold text-dark">Recent Deliveries</h5>
                                   </div>
                                   <div class="dropdown">
                                        <a href="#"
                                             class="dropdown-toggle btn btn-sm btn-light border-0 px-3 rounded-pill fw-semibold"
                                             data-bs-toggle="dropdown" aria-expanded="false">
                                             Daily
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                                             <a href="#!" class="dropdown-item py-2">Week</a>
                                             <a href="#!" class="dropdown-item py-2">Months</a>
                                             <a href="#!" class="dropdown-item py-2">Years</a>
                                        </div>
                                   </div>
                              </div>

                              <div class="card-body p-0 mt-2">
                                   <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                             <thead class="bg-light">
                                                  <tr>
                                                       <th class="ps-4 border-0 text-muted small fw-bold text-uppercase">Date</th>
                                                       <th class="border-0 text-muted small fw-bold text-uppercase">Payment Via</th>
                                                       <th class="border-0 text-muted small fw-bold text-uppercase">Status</th>
                                                       <th class="pe-4 border-0 text-muted small fw-bold text-uppercase text-end">Amount (Rs.)</th>
                                                  </tr>
                                             </thead>

                                             <tbody>
                                                  <tr>
                                                       <td class="ps-4 fw-medium text-dark-emphasis">2025-08-06</td>
                                                       <td class="text-muted small">Stripe Payment</td>
                                                       <td>
                                                            <span class="badge-soft bg-success-subtle text-success">
                                                                <i class="ri-checkbox-circle-fill me-1"></i> Success
                                                            </span>
                                                       </td>
                                                       <td class="pe-4 text-end fw-bold text-dark-emphasis">21,000.00</td>
                                                  </tr>
                                                  <tr>
                                                       <td class="ps-4 fw-medium text-dark-emphasis">2025-08-05</td>
                                                       <td class="text-muted small">UPI Transfer</td>
                                                       <td>
                                                            <span class="badge-soft bg-warning-subtle text-warning">
                                                                <i class="ri-time-fill me-1"></i> Pending
                                                            </span>
                                                       </td>
                                                       <td class="pe-4 text-end fw-bold text-dark-emphasis">13,550.50</td>
                                                  </tr>
                                                  <tr>
                                                       <td class="ps-4 fw-medium text-dark-emphasis">2025-08-04</td>
                                                       <td class="text-muted small">PayPal Wallet</td>
                                                       <td>
                                                            <span class="badge-soft bg-danger-subtle text-danger">
                                                                <i class="ri-close-circle-fill me-1"></i> Failed
                                                            </span>
                                                       </td>
                                                       <td class="pe-4 text-end fw-bold text-dark-emphasis">32,075.00</td>
                                                  </tr>
                                                  <tr>
                                                       <td class="ps-4 fw-medium text-dark-emphasis">2025-08-03</td>
                                                       <td class="text-muted small">Debit Card</td>
                                                       <td>
                                                            <span class="badge-soft bg-success-subtle text-success">
                                                                <i class="ri-checkbox-circle-fill me-1"></i> Success
                                                            </span>
                                                       </td>
                                                       <td class="pe-4 text-end fw-bold text-dark-emphasis">8,999.00</td>
                                                  </tr>
                                                  <tr>
                                                       <td class="ps-4 fw-medium text-dark-emphasis">2025-08-02</td>
                                                       <td class="text-muted small">Bank Transfer</td>
                                                       <td>
                                                            <span class="badge-soft bg-success-subtle text-success">
                                                                <i class="ri-checkbox-circle-fill me-1"></i> Success
                                                            </span>
                                                       </td>
                                                       <td class="pe-4 text-end fw-bold text-dark-emphasis">15,045.00</td>
                                                  </tr>
                                             </tbody>
                                        </table>
                                   </div>
                              </div>

                              <div class="card-footer border-top text-center p-3">
                                   <a href="#!" class="link-primary text-decoration-underline fw-medium">Show
                                        More <i class="ri-arrow-right-up-line"></i></a>
                              </div>
                         </div>
                    </div>

               </div>
          </div>

          <div class="col-xl-3 col-lg-4">
                <div class="card border-0 mb-4 shadow-sm dashboard-sidebar-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="fw-bold text-dark-emphasis mb-0">Major Outlets</h6>
                            <div class="bg-primary-subtle rounded-3 p-1">
                                <i class="ri-map-pin-5-line text-primary fs-18"></i>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="p-3 rounded-4 border border-light-subtle hover-translate transition-all outlet-item" style="background: rgba(var(--bs-primary-rgb), 0.02);">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-bold text-dark-emphasis mb-0 fs-13">Kirindiwela - Main</h6>
                                    <span class="badge bg-warning-subtle text-warning border-0 px-2 rounded-pill extra-small"><i class="ri-star-fill"></i> 4.8</span>
                                </div>
                                <p class="text-muted extra-small mb-2"><i class="ri-map-pin-2-line me-1"></i> No 45, Main St, Kirindiwela</p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="#!" class="extra-small fw-bold text-primary text-decoration-none">+94 33 222 5555</a>
                                    <span class="badge-soft bg-success-subtle text-success">Open Now</span>
                                </div>
                            </div>

                            <div class="p-3 rounded-4 border border-light-subtle hover-translate transition-all outlet-item" style="background: rgba(var(--bs-primary-rgb), 0.02);">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-bold text-dark-emphasis mb-0 fs-13">Gampaha Hub</h6>
                                    <span class="badge-soft bg-warning-subtle text-warning"><i class="ri-star-fill"></i> 4.6</span>
                                </div>
                                <p class="text-muted extra-small mb-2"><i class="ri-map-pin-2-line me-1"></i> 122/A, Kandy Rd, Gampaha</p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="#!" class="extra-small fw-bold text-primary text-decoration-none">+94 33 555 1122</a>
                                    <span class="badge-soft bg-success-subtle text-success">Open Now</span>
                                </div>
                            </div>

                            <div class="p-3 rounded-4 border border-light-subtle hover-translate transition-all outlet-item" style="background: rgba(var(--bs-primary-rgb), 0.02);">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-bold text-dark-emphasis mb-0 fs-13">Nittambuwa Center</h6>
                                    <span class="badge-soft bg-warning-subtle text-warning"><i class="ri-star-fill"></i> 4.2</span>
                                </div>
                                <p class="text-muted extra-small mb-2"><i class="ri-map-pin-2-line me-1"></i> No 8, Highlevel Rd</p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="#!" class="extra-small fw-bold text-primary text-decoration-none">+94 33 777 8888</a>
                                    <span class="badge-soft bg-danger-subtle text-danger">Busy</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm dashboard-sidebar-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="fw-bold text-dark-emphasis mb-0">Fast Moving Items</h6>
                            <span class="badge bg-primary-subtle text-primary border-0 px-2 py-1 rounded-pill extra-small">Trending</span>
                        </div>

                        <div class="space-y-3">
                            <div class="d-flex align-items-center gap-3 p-3 rounded-4 border border-light-subtle transition-all hover-translate fast-item" style="background: #ffffff;">
                                <div class="bg-light-subtle rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <img src="{{ asset('assets/images/food-icon/pic15.png') }}" alt="" class="img-fluid" style="max-height: 30px;">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="text-dark-emphasis fw-bold mb-1 fs-13">Ceylinco Paints</h6>
                                    <p class="text-muted extra-small mb-0">Premium Emulsion</p>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success-subtle text-success border-0 rounded-pill extra-small">+12%</span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3 p-3 rounded-4 border border-light-subtle transition-all hover-translate fast-item" style="background: #ffffff;">
                                <div class="bg-light-subtle rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <img src="{{ asset('assets/images/food-icon/pic10.png') }}" alt="" class="img-fluid" style="max-height: 30px;">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="text-dark-emphasis fw-bold mb-1 fs-13">PVC Fittings</h6>
                                    <p class="text-muted extra-small mb-0">Industrial Grade</p>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success-subtle text-success border-0 rounded-pill extra-small">+8%</span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3 p-3 rounded-4 border border-light-subtle transition-all hover-translate fast-item" style="background: #ffffff;">
                                <div class="bg-light-subtle rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <img src="{{ asset('assets/images/food-icon/pic11.png') }}" alt="" class="img-fluid" style="max-height: 30px;">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="text-dark-emphasis fw-bold mb-1 fs-13">Tokyo Cement</h6>
                                    <p class="text-muted extra-small mb-0">Heavy Construction</p>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-warning-subtle text-warning border-0 rounded-pill extra-small">Fast</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-5 pb-5"></div>
@endsection

@section('scripts')
     <!-- Dashboard Js -->
     <script src="{{ asset('assets/js/pages/dashboard.js') }}"></script>
@endsection
