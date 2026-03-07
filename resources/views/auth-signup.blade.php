<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Sign Up | Sagaki Distribution</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Create a new account on Sagaki Distribution Management System" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #f7fafc;
            overflow-y: auto;
            position: relative;
        }
        body::before{
            content:"";
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
              radial-gradient(700px 700px at var(--sx1,18%) var(--sy1,12%), rgba(16,185,129,0.12), transparent 45%),
              radial-gradient(800px 800px at var(--sx2,82%) var(--sy2,20%), rgba(96,165,250,0.12), transparent 46%),
              radial-gradient(600px 600px at var(--sx3,50%) var(--sy3,85%), rgba(99,102,241,0.10), transparent 50%),
              linear-gradient(180deg,#f8fafc 0%, #ffffff 60%);
            animation: sbgmove 24s ease-in-out infinite;
            pointer-events: none;
        }
        @keyframes sbgmove{
            0%   { --sx1:18%; --sy1:12%; --sx2:82%; --sy2:20%; --sx3:50%; --sy3:85%; }
            50%  { --sx1:22%; --sy1:16%; --sx2:78%; --sy2:24%; --sx3:46%; --sy3:82%; }
            100% { --sx1:18%; --sy1:12%; --sx2:82%; --sy2:20%; --sx3:50%; --sy3:85%; }
        }

        /* ── Wrapper ── */
        .auth-wrapper {
            display: flex;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        /* ── Left Panel ── */
        .left-panel {
            flex: 1;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
        }

        .left-panel::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(16,185,129,0.25) 0%, transparent 70%);
            top: -100px; left: -100px;
            animation: glow 7s ease-in-out infinite;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            width: 420px; height: 420px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99,102,241,0.2) 0%, transparent 70%);
            bottom: -80px; right: -60px;
            animation: glow 9s ease-in-out infinite reverse;
        }

        @keyframes glow {
            0%, 100% { transform: scale(1); opacity: 0.6; }
            50%       { transform: scale(1.2); opacity: 1; }
        }

        .left-panel-content {
            position: relative; z-index: 2;
            text-align: center;
            padding: 40px;
            color: white;
        }

        .brand-logo {
            display: flex; align-items: center;
            justify-content: center; gap: 12px;
            margin-bottom: 48px;
        }

        .brand-icon {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 8px 32px rgba(16,185,129,0.4);
        }

        .brand-icon i { font-size: 28px; color: white; }

        .brand-name {
            font-size: 26px; font-weight: 800;
            color: white; letter-spacing: -0.5px;
        }

        .brand-name span { color: #6ee7b7; }

        /* Steps visual */
        .steps-visual {
            margin: 0 auto 40px;
            max-width: 340px;
        }

        .steps-title {
            font-size: 13px;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 24px;
        }

        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 22px;
            text-align: left;
        }

        .step-num {
            width: 36px; height: 36px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; color: #6ee7b7;
            flex-shrink: 0;
        }

        .step-num.active {
            background: linear-gradient(135deg, #10b981, #059669);
            border-color: transparent;
            color: white;
            box-shadow: 0 4px 16px rgba(16,185,129,0.4);
        }

        .step-info h4 {
            font-size: 14px; font-weight: 600;
            color: white; margin-bottom: 3px;
        }

        .step-info p {
            font-size: 12px;
            color: rgba(255,255,255,0.45);
            line-height: 1.5;
        }

        .hero-text { margin-top: 40px; }

        .hero-text h2 {
            font-size: 30px; font-weight: 800;
            color: white; line-height: 1.3;
            margin-bottom: 14px;
        }

        .hero-text h2 span {
            background: linear-gradient(135deg, #6ee7b7, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-text p {
            color: rgba(255,255,255,0.5);
            font-size: 14.5px; line-height: 1.7;
        }

        /* ── Right Panel ── */
        .right-panel {
            width: 500px; min-width: 500px;
            background: transparent;
            display: flex; align-items: center; justify-content: center;
            padding: 36px 56px;
            position: relative;
            overflow-y: auto;
        }

        .right-panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 4px;
            background: linear-gradient(90deg, #10b981, #6366f1, #60a5fa);
        }

        .form-container {
            width: 100%; max-width: 380px;
            animation: slideIn 0.5s cubic-bezier(0.23,1,0.32,1) both;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            box-shadow: 0 12px 32px rgba(0,0,0,0.08);
            padding: 18px 18px 22px;
        }

        .ambient {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }
        .ambient .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: .28;
        }
        .ambient .b1 {
            width: 420px; height: 420px;
            background: radial-gradient(circle at 30% 30%, rgba(16,185,129,.6), rgba(16,185,129,.1) 60%, transparent 70%);
            top: -120px; left: -120px;
            animation: orb1 16s ease-in-out infinite;
        }
        .ambient .b2 {
            width: 360px; height: 360px;
            background: radial-gradient(circle at 60% 40%, rgba(96,165,250,.55), rgba(96,165,250,.1) 60%, transparent 70%);
            bottom: -100px; right: -80px;
            animation: orb2 18s ease-in-out infinite reverse;
        }
        .ambient .b3 {
            width: 300px; height: 300px;
            background: radial-gradient(circle at 50% 50%, rgba(99,102,241,.5), rgba(99,102,241,.08) 60%, transparent 70%);
            top: 20%; right: 20%;
            animation: orb3 22s ease-in-out infinite;
        }
        @keyframes orb1 { 0%,100%{ transform: translate(0,0) scale(1) } 50%{ transform: translate(40px,30px) scale(1.08) } }
        @keyframes orb2 { 0%,100%{ transform: translate(0,0) scale(1) } 50%{ transform: translate(-30px,20px) scale(1.05) } }
        @keyframes orb3 { 0%,100%{ transform: translate(0,0) scale(1) } 50%{ transform: translate(10px,-20px) scale(1.04) } }
        @media (max-width: 900px) {
            .ambient .b1 { width: 340px; height: 340px; top: -140px; left: -140px; filter: blur(48px); }
            .ambient .b2 { width: 300px; height: 300px; bottom: -120px; right: -100px; filter: blur(48px); }
            .ambient .b3 { width: 260px; height: 260px; right: 10%; top: 25%; filter: blur(44px); }
        }
        @media (max-width: 500px) {
            .ambient .b1 { width: 260px; height: 260px; }
            .ambient .b2 { width: 220px; height: 220px; }
            .ambient .b3 { width: 200px; height: 200px; }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(30px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* Form header */
        .form-header { margin-bottom: 28px; }

        .form-header .welcome-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: #d1fae5; color: #059669;
            font-size: 12px; font-weight: 600;
            padding: 6px 14px; border-radius: 100px;
            margin-bottom: 14px; letter-spacing: 0.3px;
        }

        .form-header h1 {
            font-size: 26px; font-weight: 800;
            color: #111827; margin-bottom: 6px; letter-spacing: -0.5px;
        }

        .form-header p {
            color: #6b7280; font-size: 13.5px; line-height: 1.6;
        }

        /* Alerts */
        .alert-error-custom {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border: 1px solid #fca5a5;
            border-radius: 12px; padding: 12px 16px;
            color: #991b1b; font-size: 13.5px; font-weight: 500;
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
        }

        /* Form group */
        .form-group { margin-bottom: 16px; }

        .form-label-custom {
            display: block; font-size: 13px; font-weight: 600;
            color: #374151; margin-bottom: 7px; letter-spacing: 0.1px;
        }

        .input-wrapper { position: relative; }

        .input-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%); color: #9ca3af;
            font-size: 18px; z-index: 2; transition: color 0.2s;
        }

        .form-input {
            width: 100%; height: 48px;
            padding: 0 44px 0 44px;
            border: 1.5px solid #e5e7eb; border-radius: 12px;
            font-size: 13.5px; font-family: 'Inter', sans-serif;
            color: #111827; background: #fafafa;
            transition: all 0.2s ease; outline: none;
        }

        .form-input:focus {
            border-color: #10b981; background: #ffffff;
            box-shadow: 0 0 0 4px rgba(16,185,129,0.1);
        }

        .form-input.is-invalid { border-color: #ef4444; background: #fff5f5; }

        .form-input.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(239,68,68,0.1);
        }

        .input-wrapper:focus-within .input-icon { color: #10b981; }

        /* Select */
        .form-select-custom {
            width: 100%; height: 48px;
            padding: 0 16px 0 44px;
            border: 1.5px solid #e5e7eb; border-radius: 12px;
            font-size: 13.5px; font-family: 'Inter', sans-serif;
            color: #111827; background: #fafafa;
            cursor: pointer; outline: none;
            transition: all 0.2s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%236b7280' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 12px;
            background-color: #fafafa;
        }

        .form-select-custom:focus {
            border-color: #10b981; background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(16,185,129,0.1);
        }

        /* Toggle password */
        .toggle-password {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%); background: none; border: none;
            color: #9ca3af; cursor: pointer; font-size: 18px;
            padding: 0; z-index: 2; transition: color 0.2s;
        }

        .toggle-password:hover { color: #10b981; }

        /* Inline info box */
        .rep-info-box {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border: 1px solid #bfdbfe;
            border-radius: 12px; padding: 14px 16px;
            display: flex; align-items: flex-start; gap: 10px;
            margin-bottom: 16px;
        }

        .rep-info-box i { color: #3b82f6; font-size: 20px; flex-shrink: 0; margin-top: 2px; }

        .rep-info-box p {
            color: #1e40af; font-size: 13px; line-height: 1.5; margin: 0;
        }

        .rep-info-box strong { font-weight: 700; }

        /* Invalid feedback */
        .invalid-feedback-custom {
            color: #ef4444; font-size: 12px; margin-top: 5px;
            display: flex; align-items: center; gap: 4px;
        }

        /* Terms row */
        .terms-row {
            display: flex; align-items: flex-start;
            gap: 10px; margin-bottom: 20px; margin-top: 4px;
        }

        .terms-row input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: #10b981; cursor: pointer;
            flex-shrink: 0; margin-top: 2px;
        }

        .terms-row label {
            font-size: 12.5px; color: #6b7280;
            line-height: 1.5; cursor: pointer;
        }

        .terms-row a {
            color: #10b981; font-weight: 600; text-decoration: none;
        }

        /* Submit button */
        .btn-submit {
            width: 100%; height: 50px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white; border: none; border-radius: 12px;
            font-size: 15px; font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer; transition: all 0.25s ease;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            box-shadow: 0 4px 20px rgba(16,185,129,0.35);
            letter-spacing: 0.2px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(16,185,129,0.5);
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }

        .btn-submit:active { transform: translateY(0px); }

        .btn-submit i { font-size: 18px; }

        /* Two-column row */
        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        /* Footer */
        .form-footer {
            text-align: center; margin-top: 24px;
        }

        .form-footer p { font-size: 14px; color: #6b7280; }

        .form-footer a {
            color: #10b981; font-weight: 700;
            text-decoration: none; transition: color 0.2s;
        }

        .form-footer a:hover { color: #059669; }

        /* Responsive */
        .brand-inline {
            display: none;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }
        .brand-inline .brand-chip {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 10px; border-radius: 10px;
            background: #f1f5f9; border: 1px solid #cbd5e1; color: #1e293b; font-weight: 800;
            text-decoration: none;
        }
        .brand-inline .brand-chip i { color: #10b981; }
        @media (max-width: 900px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; min-width: unset; padding: 24px 16px; }
            .brand-inline { display: flex; }
        }

        @media (max-width: 500px) {
            .form-row-2 { grid-template-columns: 1fr; }
            .form-container { max-width: 100%; padding: 16px 14px 18px; border-radius: 14px; }
            .form-header h1 { font-size: 22px; }
            .form-input, .form-select-custom { height: 50px; }
        }
    </style>
</head>

<body>
<div class="ambient"><span class="blob b1"></span><span class="blob b2"></span><span class="blob b3"></span></div>
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

            <!-- Onboarding Steps -->
            <div class="steps-visual">
                <div class="steps-title">How it works</div>

                <div class="step-item">
                    <div class="step-num active">1</div>
                    <div class="step-info">
                        <h4>Create Your Account</h4>
                        <p>Fill in your details and choose your role to get started.</p>
                    </div>
                </div>

                <div class="step-item">
                    <div class="step-num">2</div>
                    <div class="step-info">
                        <h4>Admin Verification</h4>
                        <p>Your account will be verified by the system admin.</p>
                    </div>
                </div>

                <div class="step-item">
                    <div class="step-num">3</div>
                    <div class="step-info">
                        <h4>Access Dashboard</h4>
                        <p>Log in and start managing your distribution network.</p>
                    </div>
                </div>
            </div>

            <div class="hero-text">
                <h2>Join the <span>Sagaki</span> Network Today</h2>
                <p>Streamline your sales operations, track performance, and scale your business with ease.</p>
            </div>

        </div>
    </div>

    <!-- ── Right Form Panel ── -->
    <div class="right-panel">
        <div class="form-container">

            <div class="brand-inline">
                <a href="{{ route('dashboard') }}" class="brand-chip">
                    <i class='bx bx-package'></i>
                    <span>Sagaki Distribution</span>
                </a>
            </div>

            <div class="form-header">
                <div class="welcome-badge">
                    <i class='bx bx-user-plus'></i>
                    New Account
                </div>
                <h1>Create your account</h1>
                <p>Join the Sagaki distribution platform and start managing your sales operations.</p>
            </div>

            {{-- Error Messages --}}
            @if($errors->any())
                <div class="alert-error-custom">
                    <i class='bx bx-error-circle' style="font-size:18px"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <!-- Name + Email row -->
                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label-custom" for="name">Full Name</label>
                        <div class="input-wrapper">
                            <i class='bx bx-user input-icon'></i>
                            <input type="text" id="name" name="name"
                                class="form-input @error('name') is-invalid @enderror"
                                placeholder="Your full name"
                                value="{{ old('name') }}" required>
                        </div>
                        @error('name')
                            <div class="invalid-feedback-custom">
                                <i class='bx bx-error-circle'></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label-custom" for="mobile_number">Mobile No.</label>
                        <div class="input-wrapper">
                            <i class='bx bx-phone input-icon'></i>
                            <input type="text" id="mobile_number" name="mobile_number"
                                class="form-input @error('mobile_number') is-invalid @enderror"
                                placeholder="07XXXXXXXX"
                                value="{{ old('mobile_number') }}" required>
                        </div>
                        @error('mobile_number')
                            <div class="invalid-feedback-custom">
                                <i class='bx bx-error-circle'></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label-custom" for="email">Email Address</label>
                    <div class="input-wrapper">
                        <i class='bx bx-envelope input-icon'></i>
                        <input type="email" id="email" name="email"
                            class="form-input @error('email') is-invalid @enderror"
                            placeholder="you@example.com"
                            value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <div class="invalid-feedback-custom">
                            <i class='bx bx-error-circle'></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Role -->
                <div class="form-group">
                    <label class="form-label-custom" for="role">Account Role</label>
                    <div class="input-wrapper">
                        <i class='bx bx-shield-quarter input-icon'></i>
                        <select id="role" name="role"
                            class="form-select-custom @error('role') is-invalid @enderror"
                            required onchange="handleRoleChange(this.value)">
                            <option value="" selected disabled>Select your role</option>
                            <option value="ref" {{ old('role') == 'ref' ? 'selected' : '' }}>Rep Agent</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    @error('role')
                        <div class="invalid-feedback-custom">
                            <i class='bx bx-error-circle'></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password field (hidden for rep) -->
                <div id="password-field" class="form-group">
                    <label class="form-label-custom" for="password">Password</label>
                    <div class="input-wrapper">
                        <i class='bx bx-lock-alt input-icon'></i>
                        <input type="password" id="password" name="password"
                            class="form-input @error('password') is-invalid @enderror"
                            placeholder="Set a strong password">
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

                <!-- Rep Agent info box -->
                <div id="ref-note" style="display: none;">
                    <div class="rep-info-box">
                        <i class='bx bx-info-circle'></i>
                        <p>Your <strong>Serial Number</strong> will be automatically generated and used as your initial password. You can change it after logging in.</p>
                    </div>
                </div>

                <!-- Terms -->
                <div class="terms-row">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">
                        I agree to the <a href="#">Terms of Service</a> and
                        <a href="#">Privacy Policy</a> of Sagaki Distribution
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-submit">
                    <i class='bx bx-user-check'></i>
                    Create Account
                </button>

            </form>

            <div class="form-footer">
                <p>Already have an account? <a href="{{ route('login') }}">&larr; Sign In</a></p>
                <div class="mt-4 pt-2 border-top text-center">
                    <p class="text-muted extra-small mb-0">
                        <script>document.write(new Date().getFullYear())</script> &copy; NerdTech Labs. All rights reserved.
                    </p>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
    function handleRoleChange(role) {
        const passField = document.getElementById('password-field');
        const refNote   = document.getElementById('ref-note');
        const passInput = document.getElementById('password');

        if (role === 'ref') {
            passField.style.display = 'none';
            refNote.style.display   = 'block';
            passInput.required      = false;
            passInput.value         = '';
        } else {
            passField.style.display = 'block';
            refNote.style.display   = 'none';
            passInput.required      = true;
        }
    }

    function togglePwd(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type    = 'text';
            icon.className = 'bx bx-show';
        } else {
            input.type    = 'password';
            icon.className = 'bx bx-hide';
        }
    }

    // Restore state from old() on page reload
    window.addEventListener('DOMContentLoaded', () => {
        const role = document.getElementById('role').value;
        if (role) handleRoleChange(role);
    });
</script>

</body>
</html>
