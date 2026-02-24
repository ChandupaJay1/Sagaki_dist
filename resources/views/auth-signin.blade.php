<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Sign In | Sagaki Distribution</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Sign in to Sagaki Distribution Management System" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            overflow: hidden;
            background: #0a0a1a;
        }

        /* ── Animated Background ── */
        .auth-wrapper {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* ── Left Panel ── */
        .left-panel {
            flex: 1;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
        }

        .left-panel::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99,102,241,0.35) 0%, transparent 70%);
            top: -100px;
            left: -100px;
            animation: pulse 6s ease-in-out infinite;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(139,92,246,0.25) 0%, transparent 70%);
            bottom: -80px;
            right: -80px;
            animation: pulse 8s ease-in-out infinite reverse;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.7; }
            50% { transform: scale(1.15); opacity: 1; }
        }

        .left-panel-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 40px;
            color: white;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 48px;
        }

        .brand-icon {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 32px rgba(99,102,241,0.4);
        }

        .brand-icon i {
            font-size: 28px;
            color: white;
        }

        .brand-name {
            font-size: 26px;
            font-weight: 800;
            color: white;
            letter-spacing: -0.5px;
        }

        .brand-name span {
            color: #a78bfa;
        }

        .hero-illustration {
            width: 320px;
            height: 280px;
            margin: 0 auto 40px;
            position: relative;
        }

        /* Floating cards illustration */
        .float-card {
            position: absolute;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 16px;
            padding: 16px 20px;
            animation: floatCard 4s ease-in-out infinite;
        }

        .float-card-1 {
            top: 10px; left: 10px;
            animation-delay: 0s;
        }
        .float-card-2 {
            top: 40px; right: 0px;
            animation-delay: -1.5s;
        }
        .float-card-3 {
            bottom: 10px; left: 40px;
            animation-delay: -3s;
        }

        @keyframes floatCard {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }

        .float-card .card-label {
            font-size: 11px;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }

        .float-card .card-value {
            font-size: 20px;
            font-weight: 700;
            color: white;
        }

        .float-card .card-sub {
            font-size: 11px;
            color: #a78bfa;
            margin-top: 2px;
        }

        .stat-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }

        .hero-text h2 {
            font-size: 32px;
            font-weight: 800;
            color: white;
            line-height: 1.3;
            margin-bottom: 16px;
        }

        .hero-text h2 span {
            background: linear-gradient(135deg, #a78bfa, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-text p {
            color: rgba(255,255,255,0.55);
            font-size: 15px;
            line-height: 1.7;
            max-width: 340px;
            margin: 0 auto;
        }

        .features-grid {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 40px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,0.65);
            font-size: 13px;
        }

        .feature-item i {
            color: #a78bfa;
            font-size: 18px;
        }

        /* ── Right Panel ── */
        .right-panel {
            width: 480px;
            min-width: 480px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 56px;
            position: relative;
            overflow-y: auto;
        }

        .right-panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, #6366f1, #8b5cf6, #60a5fa);
        }

        .form-container {
            width: 100%;
            max-width: 360px;
        }

        .form-header {
            margin-bottom: 36px;
        }

        .form-header .welcome-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0effe;
            color: #6366f1;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 100px;
            margin-bottom: 16px;
            letter-spacing: 0.3px;
        }

        .form-header h1 {
            font-size: 28px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .form-header p {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
        }

        /* Alert messages */
        .alert-success-custom {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            border: 1px solid #6ee7b7;
            border-radius: 12px;
            padding: 12px 16px;
            color: #065f46;
            font-size: 13.5px;
            font-weight: 500;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-error-custom {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border: 1px solid #fca5a5;
            border-radius: 12px;
            padding: 12px 16px;
            color: #991b1b;
            font-size: 13.5px;
            font-weight: 500;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Form fields */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label-custom {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            letter-spacing: 0.1px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 18px;
            z-index: 2;
            transition: color 0.2s;
        }

        .form-input {
            width: 100%;
            height: 50px;
            padding: 0 44px 0 44px;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: #111827;
            background: #fafafa;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-input:focus {
            border-color: #6366f1;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
        }

        .form-input.is-invalid {
            border-color: #ef4444;
            background: #fff5f5;
        }

        .form-input.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(239,68,68,0.1);
        }

        .input-wrapper:focus-within .input-icon {
            color: #6366f1;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 18px;
            padding: 0;
            z-index: 2;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: #6366f1;
        }

        .invalid-feedback-custom {
            color: #ef4444;
            font-size: 12px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Forgot password */
        .forgot-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .remember-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-check input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #6366f1;
            cursor: pointer;
        }

        .remember-check label {
            font-size: 13px;
            color: #6b7280;
            cursor: pointer;
        }

        .forgot-link {
            font-size: 13px;
            color: #6366f1;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #4f46e5;
        }

        /* Submit button */
        .btn-submit {
            width: 100%;
            height: 50px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 20px rgba(99,102,241,0.35);
            letter-spacing: 0.2px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(99,102,241,0.5);
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }

        .btn-submit:active {
            transform: translateY(0px);
        }

        .btn-submit i {
            font-size: 18px;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
        }

        .divider hr {
            flex: 1;
            border: none;
            height: 1px;
            background: #e5e7eb;
        }

        .divider span {
            font-size: 12px;
            color: #9ca3af;
            font-weight: 500;
        }

        /* Footer link */
        .form-footer {
            text-align: center;
            margin-top: 28px;
        }

        .form-footer p {
            font-size: 14px;
            color: #6b7280;
        }

        .form-footer a {
            color: #6366f1;
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s;
        }

        .form-footer a:hover {
            color: #4f46e5;
        }

        /* Animate in */
        .form-container {
            animation: slideIn 0.5s cubic-bezier(0.23,1,0.32,1) both;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(30px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* Responsive */
        @media (max-width: 900px) {
            .left-panel { display: none; }
            .right-panel {
                width: 100%;
                min-width: unset;
                padding: 40px 24px;
            }
        }
    </style>
</head>

<body>
<div class="auth-wrapper">

    <!-- ── Left Branding Panel ── -->
    <div class="left-panel">
        <div class="left-panel-content">

            <div class="brand-logo">
                <div class="brand-icon">
                    <i class='bx bx-package'></i>
                </div>
                <div class="brand-name">Sagaki <span>Dist.</span></div>
            </div>

            <!-- Floating Stats Illustration -->
            <div class="hero-illustration">
                <div class="float-card float-card-1">
                    <div class="card-label">Today's Orders</div>
                    <div class="card-value">1,248</div>
                    <div class="card-sub"><span class="stat-dot" style="background:#10b981"></span>+12.5% this week</div>
                </div>
                <div class="float-card float-card-2">
                    <div class="card-label">Active Reps</div>
                    <div class="card-value">84</div>
                    <div class="card-sub"><span class="stat-dot" style="background:#a78bfa"></span>Across 9 regions</div>
                </div>
                <div class="float-card float-card-3">
                    <div class="card-label">Revenue</div>
                    <div class="card-value">Rs. 2.8M</div>
                    <div class="card-sub"><span class="stat-dot" style="background:#60a5fa"></span>This month</div>
                </div>
            </div>

            <div class="hero-text">
                <h2>Manage Your Distribution <span>Smarter</span></h2>
                <p>The all-in-one platform for Kirindiwela's leading distribution network. Track orders, manage reps, and grow your business.</p>
            </div>

            <div class="features-grid">
                <div class="feature-item">
                    <i class='bx bxs-shield-check'></i>
                    <span>Secure</span>
                </div>
                <div class="feature-item">
                    <i class='bx bx-trending-up'></i>
                    <span>Real-time</span>
                </div>
                <div class="feature-item">
                    <i class='bx bx-devices'></i>
                    <span>Responsive</span>
                </div>
            </div>

        </div>
    </div>

    <!-- ── Right Form Panel ── -->
    <div class="right-panel">
        <div class="form-container">

            <div class="form-header">
                <div class="welcome-badge">
                    <i class='bx bx-wave'></i>
                    Welcome back!
                </div>
                <h1>Sign in to your account</h1>
                <p>Enter your credentials below to access the Sagaki Distribution dashboard.</p>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert-success-custom">
                    <i class='bx bx-check-circle' style="font-size:18px"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Error Message --}}
            @if($errors->any())
                <div class="alert-error-custom">
                    <i class='bx bx-error-circle' style="font-size:18px"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label-custom" for="email">Email Address</label>
                    <div class="input-wrapper">
                        <i class='bx bx-envelope input-icon'></i>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-input @error('email') is-invalid @enderror"
                            placeholder="you@example.com"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required>
                    </div>
                    @error('email')
                        <div class="invalid-feedback-custom">
                            <i class='bx bx-error-circle'></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label-custom" for="password">Password</label>
                    <div class="input-wrapper">
                        <i class='bx bx-lock-alt input-icon'></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input @error('password') is-invalid @enderror"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required>
                        <button type="button" class="toggle-password" onclick="togglePwd('password', this)">
                            <i class='bx bx-hide'></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback-custom">
                            <i class='bx bx-error-circle'></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember Me + Forgot -->
                <div class="forgot-row">
                    <div class="remember-check">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-submit">
                    <i class='bx bx-log-in'></i>
                    Sign In
                </button>

            </form>

            <div class="form-footer">
                <p>Don't have an account? <a href="{{ route('register') }}">Create one &rarr;</a></p>
                <div class="mt-4 pt-3 border-top text-center">
                    <p class="text-muted extra-small mb-0">
                        <script>document.write(new Date().getFullYear())</script> &copy; NerdTech Labs. All rights reserved.
                    </p>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
    function togglePwd(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bx bx-show';
        } else {
            input.type = 'password';
            icon.className = 'bx bx-hide';
        }
    }
</script>

</body>
</html>