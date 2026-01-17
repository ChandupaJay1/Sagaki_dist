<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from foxpixel.vercel.app/metor/auth-signup.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 16 Jan 2026 18:58:13 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->

<head>
     <!-- Title Meta -->
     <meta charset="utf-8" />
     <title>Sign Up | Metor - Responsive Admin Dashboard Template</title>
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

<body class="authentication-bg">

     <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
          <div class="container">
               <div class="row justify-content-center">
                    <div class="col-xl-5">
                         <div class="card auth-card">
                              <div class="card-body">
                                   <div class="p-3">
                                        <div class="mx-auto mb-5 auth-logo text-center">
                                             <a href="{{ route('dashboard') }}" class="logo-dark">
                                                  <img src="{{ asset('assets/images/logo-dark.png') }}" height="30"
                                                       alt="logo dark">
                                             </a>

                                             <a href="{{ route('dashboard') }}" class="logo-light">
                                                  <img src="{{ asset('assets/images/logo-white.png') }}" height="30"
                                                       alt="logo light">
                                             </a>
                                        </div>
                                        <div class="text-center">
                                             <h3 class="fw-bold text-dark fs-20">Hi , Sign Up 👋 </h3>
                                             <p class="text-muted mt-1 mb-4">New to our platform? Sign up now! It only
                                                  takes a minute.</p>
                                        </div>
                                        <div class="p-3">
                                             <form action="{{ route('dashboard') }}" class="authentication-form">
                                                  <div class="mb-3">
                                                       <label class="form-label" for="example-name">Name</label>
                                                       <input type="name" id="example-name" name="example-name"
                                                            class="form-control" placeholder="Enter your name">
                                                  </div>
                                                  <div class="mb-3">
                                                       <label class="form-label" for="example-email">Email</label>
                                                       <input type="email" id="example-email" name="example-email"
                                                            class="form-control" placeholder="Enter your email">
                                                  </div>
                                                  <div class="mb-3">
                                                       <label class="form-label" for="example-password">Password</label>
                                                       <input type="text" id="example-password" class="form-control"
                                                            placeholder="Enter your password">
                                                  </div>
                                                  <div class="mb-3">
                                                       <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                 id="checkbox-signin">
                                                            <label class="form-check-label" for="checkbox-signin">I
                                                                 accept Terms and Condition</label>
                                                       </div>
                                                  </div>

                                                  <div class="mb-1 text-center d-grid">
                                                       <button class="btn btn-primary" type="submit">Sign Up</button>
                                                  </div>
                                             </form>
                                             <p class="mt-3 fw-semibold no-span">Or Sign In with</p>

                                             <div class="row align-items-center justify-content-center g-3 text-center">
                                                  <div class="col-lg-3">
                                                       <a href="javascript:void(0);"
                                                            class="btn btn-outline-danger shadow w-100 d-flex align-items-center justify-content-center gap-1 fw-medium"><i
                                                                 class='bx bxl-google fs-20'></i> Google</a>
                                                  </div>
                                                  <div class="col-lg-3">
                                                       <a href="javascript:void(0);"
                                                            class="btn btn-outline-primary shadow w-100 d-flex align-items-center justify-content-center gap-1 fw-medium"><i
                                                                 class='bx bxl-facebook fs-20'></i>Facebook</a>
                                                  </div>
                                                  <div class="col-lg-3">
                                                       <a href="javascript:void(0);"
                                                            class="btn btn-outline-dark shadow w-100 d-flex align-items-center justify-content-center gap-1 fw-medium"><i
                                                                 class='bx bxl-github fs-20'></i>Github</a>
                                                  </div>
                                             </div>
                                        </div>

                                        <p class="text-muted text-center mt-4 mb-0">I already have an account <a
                                                  href="{{ route('login') }}" class="text-reset fw-bold ms-1">Sign
                                                  In</a></p>
                                   </div> <!-- end col -->
                              </div> <!-- end card-body -->
                         </div> <!-- end card -->
                    </div> <!-- end col -->
               </div> <!-- end row -->
          </div>
     </div>

     <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
     <script src="{{ asset('assets/js/app.min.js') }}"></script>


</body>


<!-- Mirrored from foxpixel.vercel.app/metor/auth-signup.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 16 Jan 2026 18:58:13 GMT -->

</html>