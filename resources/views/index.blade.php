@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')
     <div class="row">
          <div class="col-12 col-xl-8">
               <div class="row">

                    <div class="col-12 col-md-6 col-lg-6">
                         <div class="card pb-0">
                              <div class="card-body">
                                   <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                             <p class="mb-3 card-title">Total Revenue</p>
                                             <h4 class="fw-bold text-primary d-flex align-items-center gap-2 mb-0">
                                                  $35,428.09</h4>
                                        </div>
                                        <div>
                                             <div class="py-2 px-3 rounded-circle bg-primary">
                                                  <i class="ri-money-dollar-circle-line fs-25 text-white"></i>
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
                                             <p class="mb-3 card-title">Registered Customers</p>
                                             <h4 class="fw-bold d-flex align-items-center gap-2 mb-0">
                                                  {{ number_format($customerCount) }}
                                             </h4>
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
                                             <p class="mb-3 card-title text-muted">Registered Vendors</p>
                                             <h4 class="fw-bold text-primary d-flex align-items-center gap-2 mb-0">
                                                  {{ number_format($vendorCount) }}
                                             </h4>
                                        </div>
                                        <div>
                                             <div class="py-2 px-3 rounded-circle bg-primary">
                                                  <i class="ri-user-settings-line fs-25 text-white"></i>
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
                                        <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light rounded"
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
                                        <a href="#" class="dropdown-toggle btn btn-sm btn-link text-uppercase fw-semibold"
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
                                        <a href="#" class="dropdown-toggle btn btn-sm btn-link text-uppercase fw-semibold"
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
                                   <div id="datalabels-column2" class="apex-charts" data-colors="#604ae3"></div>
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
                                                       <td><span class="badge badge-soft-success">Success</span>
                                                       </td>
                                                       <td>210.00</td>
                                                  </tr>
                                                  <tr>
                                                       <td>2025-08-05</td>
                                                       <td>UPI</td>
                                                       <td><span class="badge badge-soft-warning">Pending</span>
                                                       </td>
                                                       <td>135.50</td>
                                                  </tr>
                                                  <tr>
                                                       <td>2025-08-04</td>
                                                       <td>PayPal</td>
                                                       <td><span class="badge badge-soft-danger">Failed</span>
                                                       </td>
                                                       <td>320.75</td>
                                                  </tr>
                                                  <tr>
                                                       <td>2025-08-03</td>
                                                       <td>Debit Card</td>
                                                       <td><span class="badge badge-soft-success">Success</span>
                                                       </td>
                                                       <td>89.99</td>
                                                  </tr>
                                                  <tr>
                                                       <td>2025-08-02</td>
                                                       <td>Bank Transfer</td>
                                                       <td><span class="badge badge-soft-success">Success</span>
                                                       </td>
                                                       <td>150.45</td>
                                                  </tr>
                                                  <tr>
                                                       <td>2025-08-01</td>
                                                       <td>Credit Card</td>
                                                       <td><span class="badge badge-soft-danger">Failed</span>
                                                       </td>
                                                       <td>400.20</td>
                                                  </tr>
                                                  <tr>
                                                       <td>2025-07-31</td>
                                                       <td>Cash</td>
                                                       <td><span class="badge badge-soft-warning">Pending</span>
                                                       </td>
                                                       <td>95.00</td>
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

          <div class="col-12 col-xl-4">
               <div class="row">
                    <div class="col-12">
                         <div class="card">
                              <div class="card-header d-flex align-items-center justify-content-between">
                                   <div>
                                        <h4 class="card-title mb-0">Other Outlets</h4>
                                   </div>
                                   <div class="dropdown">
                                        <a href="#" class="dropdown-toggle rounded arrow-none" data-bs-toggle="dropdown"
                                             aria-expanded="true">
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
                                        <span class="fw-medium fs-18"><i class="ri-map-pin-range-line"></i></span>
                                        <span class="fw-medium ms-1">88 Bloor St W, Toronto, ON M5S
                                             1M5</span>
                                        <div class="mt-1">
                                             <span class="fw-medium fs-18"><i class="ri-phone-line"></i></span>
                                             <a href="#!" class="fw-medium link-primary ms-1">+1
                                                  416-555-1122</a>
                                        </div>
                                   </div>

                                   <div class="border rounded-2 p-3 my-3 mx-4">
                                        <h6 class="text-uppercase fw-bold">Berlin - Germany <span
                                                  class="ms-auto fw-medium float-end"><i
                                                       class="ri-star-fill text-warning"></i> 4.6</span>
                                        </h6>
                                        <span class="fw-medium fs-18"><i class="ri-map-pin-range-line"></i></span>
                                        <span class="fw-medium ms-1">Kurfürstendamm 21, 10719
                                             Berlin</span>
                                        <div class="mt-1">
                                             <span class="fw-medium fs-18"><i class="ri-phone-line"></i></span>
                                             <a href="#!" class="fw-medium link-primary ms-1">+49 30 5555
                                                  3333</a>
                                        </div>
                                   </div>

                                   <div class="border rounded-2 p-3 my-3 mx-4">
                                        <h6 class="text-uppercase fw-bold">Dubai - UAE <span
                                                  class="ms-auto fw-medium float-end"><i
                                                       class="ri-star-fill text-warning"></i> 4.8</span>
                                        </h6>
                                        <span class="fw-medium fs-18"><i class="ri-map-pin-range-line"></i></span>
                                        <span class="fw-medium ms-1">Burj Khalifa, Dubai</span>
                                        <div class="mt-1">
                                             <span class="fw-medium fs-18"><i class="ri-phone-line"></i></span>
                                             <a href="#!" class="fw-medium link-primary ms-1">+971 4
                                                  8888 8888</a>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="col-12">
                         <div class="card">
                              <div class="card-header d-flex align-items-center justify-content-between">
                                   <div>
                                        <h4 class="card-title mb-0">Trending Items</h4>
                                   </div>
                                   <div>
                                        <a href="#!" class="btn btn-sm btn-soft-primary">View All</a>
                                   </div>
                              </div>

                              <div style="height: 333px;" data-simplebar>
                                   <div class="d-flex flex-wrap align-items-center gap-2 border rounded-2 p-3 mb-3 mx-4">
                                        <div>
                                             <img src="assets/images/food-icon/pic15.png" alt="" class="avatar-lg">
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

                                   <div class="d-flex flex-wrap align-items-center gap-2 border rounded-2 p-3 mb-3 mx-4">
                                        <div>
                                             <img src="assets/images/food-icon/pic16.png" alt="" class="avatar-lg">
                                        </div>
                                        <div>
                                             <a href="#!" class="text-dark fs-15 fw-medium">Pizza</a>
                                             <p class="mb-2">30+ Options</p>
                                             <p class="mb-0 fw-semibold"><i
                                                       class="ri-star-fill text-warning me-1 fs-15"></i>4.5/5
                                             </p>
                                        </div>
                                   </div>

                                   <div class="d-flex flex-wrap align-items-center gap-2 border rounded-2 p-3 mb-3 mx-4">
                                        <div>
                                             <img src="assets/images/food-icon/pic17.png" alt="" class="avatar-lg">
                                        </div>
                                        <div>
                                             <a href="#!" class="text-dark fs-15 fw-medium">Sushi</a>
                                             <p class="mb-2">20+ Options</p>
                                             <p class="mb-0 fw-semibold"><i
                                                       class="ri-star-fill text-warning me-1 fs-15"></i>4.7/5
                                             </p>
                                        </div>
                                   </div>

                                   <div class="d-flex flex-wrap align-items-center gap-2 border rounded-2 p-3 mb-3 mx-4">
                                        <div>
                                             <img src="assets/images/food-icon/pic19.png" alt="" class="avatar-lg">
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
@endsection

@section('scripts')
     <!-- Dashboard Js -->
     <script src="{{ asset('assets/js/pages/dashboard.js') }}"></script>
@endsection