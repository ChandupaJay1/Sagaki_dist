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
                                             <!-- <a href="{{ route('dashboard') }}" class="logo-dark">
                                                  <img src="{{ asset('assets/images/logo-dark.png') }}" height="30"
                                                       alt="logo dark">
                                             </a> -->

                                             <a href="{{ route('dashboard') }}" class="logo-light">
                                                  <img src="{{ asset('assets/images/logo-white.png') }}" height="30"
                                                       alt="logo light">
                                             </a>
                                        </div>
                                        <div class="text-center">
                                             <h3 class="fw-bold text-dark fs-20">Hi , Sign Up Here </h3>
                                             <p class="text-muted mt-1 mb-4">New to our platform? Sign up now! It only
                                                  takes a minute.</p>
                                        </div>
                                        <div class="p-3">
                                             <form action="{{ route('register') }}" method="POST"
                                                  class="authentication-form">
                                                  @csrf
                                                  <div class="mb-3">
                                                       <label class="form-label" for="name">Name</label>
                                                       <input type="text" id="name" name="name"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            placeholder="Enter your name" value="{{ old('name') }}"
                                                            required>
                                                       @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                       @enderror
                                                  </div>
                                                  <div class="mb-3">
                                                       <label class="form-label" for="email">Email</label>
                                                       <input type="email" id="email" name="email"
                                                            class="form-control @error('email') is-invalid @enderror"
                                                            placeholder="Enter your email" value="{{ old('email') }}"
                                                            required>
                                                       @error('email')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                       @enderror
                                                  </div>
                                                  <div class="mb-3">
                                                       <label class="form-label" for="password">Password</label>
                                                       <input type="password" id="password" name="password"
                                                            class="form-control @error('password') is-invalid @enderror"
                                                            placeholder="Enter your password" required>
                                                       @error('password')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                       @enderror
                                                  </div>
                                                  <input type="hidden" name="role" value="admin">
                                                  <div class="mb-3">
                                                       <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                 id="checkbox-signin" required>
                                                            <label class="form-check-label" for="checkbox-signin">I
                                                                 accept Terms and Condition</label>
                                                       </div>
                                                  </div>

                                                  <div class="mb-1 text-center d-grid">
                                                       <button class="btn btn-primary" type="submit">Sign Up</button>
                                                  </div>
                                             </form>
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