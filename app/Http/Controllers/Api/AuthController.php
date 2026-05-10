<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Route;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Login user and create token
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:1',
            ]);

            // Find user by email
            $user = User::where('email', $validated['email'])->first();

            // Check if user exists
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email or password'
                ], 401);
            }

            // Check password
            if (!Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email or password'
                ], 401);
            }

            // Check if user is active
            if (!$user->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is inactive. Please contact administrator.'
                ], 403);
            }

            // Check if user is admin (Admins cannot login through mobile app)
            if ($user->role === 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin users cannot login through the mobile app. Please use the web portal.'
                ], 403);
            }

            // Create new token
            $token = $user->createToken('auth_token')->plainTextToken;

            // Get route information
            $routeInfo = null;
            try {
                if ($user->route_id) {
                    $route = Route::with(['areaRef', 'territory'])->find($user->route_id);
                    if ($route) {
                        $routeInfo = [
                            'id' => $route->id,
                            'name' => $route->name,
                            'code' => $route->code,
                            'area' => $route->areaRef ? $route->areaRef->name : null,
                            'area_id' => $route->area_id,
                            'territory' => $route->territory ? $route->territory->name : null,
                            'territory_id' => $route->territory_id,
                            'description' => $route->description,
                        ];
                    }
                }
            } catch (\Throwable $e) {
                // Silently fail route info if there's an issue, don't block login
                Log::error('Error fetching route info in login: ' . $e->getMessage());
            }

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile_number' => $user->mobile_number,
                    'role' => $user->role,
                    'route_id' => $user->route_id,
                    'route' => $routeInfo,
                    'serial_number' => $user->serial_number,
                    'serial_expires_at' => $user->serial_expires_at,
                    'is_active' => $user->is_active,
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during login',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Register new user
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'mobile_number' => 'nullable|string|unique:users',
                'password' => 'required|string|min:8' . ($request->has('password_confirmation') ? '|confirmed' : ''),
                'role' => 'nullable|string|in:ref', // Only 'ref' can register through API
                'route_id' => 'nullable|exists:routes,id',
            ]);

            // Generate serial number
            $serialNumber = User::generateSerialNumber();
            
            // Set serial expiry (e.g., 1 year from now)
            $serialExpiresAt = Carbon::now()->addYear();

            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'mobile_number' => $validated['mobile_number'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'] ?? 'ref',
                'route_id' => $validated['route_id'] ?? null,
                'serial_number' => $serialNumber,
                'serial_expires_at' => $serialExpiresAt,
                'is_active' => true, // Auto-activate for now, or set to false if admin approval needed
            ]);

            // Create token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile_number' => $user->mobile_number,
                    'role' => $user->role,
                    'serial_number' => $user->serial_number,
                    'serial_expires_at' => $user->serial_expires_at,
                    'is_active' => $user->is_active,
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during registration',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get authenticated user information
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        try {
            $user = $request->user();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile_number' => $user->mobile_number,
                    'role' => $user->role,
                    'serial_number' => $user->serial_number,
                    'serial_expires_at' => $user->serial_expires_at,
                    'is_active' => $user->is_active,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout user (Revoke token)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            // Revoke current token
            $request->user()->currentAccessToken()->delete();

            // Or revoke all tokens
            // $request->user()->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify serial number
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifySerial(Request $request)
    {
        try {
            $validated = $request->validate([
                'serial_number' => 'required|string',
            ]);

            $user = User::where('serial_number', $validated['serial_number'])->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid serial number'
                ], 404);
            }

            $isExpired = $user->serial_expires_at && Carbon::parse($user->serial_expires_at)->isPast();

            return response()->json([
                'success' => true,
                'data' => [
                    'serial_number' => $user->serial_number,
                    'expires_at' => $user->serial_expires_at,
                    'is_expired' => $isExpired,
                    'is_active' => $user->is_active,
                    'user_name' => $user->name,
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Renew serial number (Admin only)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function renewSerial(Request $request)
    {
        try {
            $currentUser = $request->user();

            // Check if user is admin
            if ($currentUser->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Admin access required.'
                ], 403);
            }

            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'duration_months' => 'required|integer|min:1|max:24',
            ]);

            $targetUser = User::findOrFail($validated['user_id']);
            
            // Extend or set expiry date
            $currentExpiry = $targetUser->serial_expires_at 
                ? Carbon::parse($targetUser->serial_expires_at) 
                : Carbon::now();
                
            $newExpiry = $currentExpiry->addMonths($validated['duration_months']);
            
            $targetUser->serial_expires_at = $newExpiry;
            $targetUser->is_active = true;
            $targetUser->save();

            return response()->json([
                'success' => true,
                'message' => 'Serial number renewed successfully',
                'data' => [
                    'user_id' => $targetUser->id,
                    'user_name' => $targetUser->name,
                    'serial_number' => $targetUser->serial_number,
                    'new_expiry_date' => $newExpiry,
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Change password
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            $user = $request->user();

            // Check current password
            if (!Hash::check($validated['current_password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ], 401);
            }

            // Update password
            $user->password = Hash::make($validated['new_password']);
            $user->save();

            // Optionally revoke all tokens to force re-login
            // $user->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully'
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user profile
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = $request->user();

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
                'mobile_number' => 'sometimes|required|string|unique:users,mobile_number,' . $user->id,
                'route_id' => 'sometimes|nullable|exists:routes,id',
            ]);

            $user->update($validated);

            // Get updated route info
            $routeInfo = null;
            if ($user->route_id) {
                $route = \App\Models\Route::with(['areaRef', 'territory'])->find($user->route_id);
                if ($route) {
                    $routeInfo = [
                        'id' => $route->id,
                        'name' => $route->name,
                        'code' => $route->code,
                        'area' => $route->areaRef ? $route->areaRef->name : null,
                        'area_id' => $route->area_id,
                        'territory' => $route->territory ? $route->territory->name : null,
                        'territory_id' => $route->territory_id,
                        'description' => $route->description,
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'mobile_number' => $user->mobile_number,
                        'role' => $user->role,
                        'route_id' => $user->route_id,
                        'route' => $routeInfo,
                    ]
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Forgot password - Send reset link
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
            ]);

            $user = User::where('email', $validated['email'])->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User with this email does not exist'
                ], 404);
            }

            // Generate reset token
            $resetToken = \Str::random(60);
            
            // Store token in database (you need to create password_resets table)
            \DB::table('password_resets')->updateOrInsert(
                ['email' => $user->email],
                [
                    'email' => $user->email,
                    'token' => Hash::make($resetToken),
                    'created_at' => Carbon::now()
                ]
            );

            // Send email with reset link (implement mail sending)
            // Mail::to($user->email)->send(new ResetPasswordMail($resetToken));

            return response()->json([
                'success' => true,
                'message' => 'Password reset link has been sent to your email',
                // For testing only - remove in production
                'reset_token' => $resetToken
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset password
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'token' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $resetRecord = \DB::table('password_resets')
                ->where('email', $validated['email'])
                ->first();

            if (!$resetRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid reset token'
                ], 400);
            }

            // Check if token matches
            if (!Hash::check($validated['token'], $resetRecord->token)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid reset token'
                ], 400);
            }

            // Check if token is expired (24 hours)
            if (Carbon::parse($resetRecord->created_at)->addHours(24)->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reset token has expired'
                ], 400);
            }

            // Update password
            $user = User::where('email', $validated['email'])->first();
            $user->password = Hash::make($validated['password']);
            $user->save();

            // Delete reset token
            \DB::table('password_resets')->where('email', $validated['email'])->delete();

            // Revoke all tokens
            $user->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Password has been reset successfully'
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle user active status (Admin only)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleUserStatus(Request $request)
    {
        try {
            $currentUser = $request->user();

            // Check if user is admin
            if ($currentUser->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Admin access required.'
                ], 403);
            }

            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);

            $targetUser = User::findOrFail($validated['user_id']);
            
            // Prevent admin from deactivating themselves
            if ($targetUser->id === $currentUser->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot deactivate your own account'
                ], 400);
            }

            // Toggle status
            $targetUser->is_active = !$targetUser->is_active;
            $targetUser->save();

            // If deactivating, revoke all tokens
            if (!$targetUser->is_active) {
                $targetUser->tokens()->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'User status updated successfully',
                'data' => [
                    'user_id' => $targetUser->id,
                    'user_name' => $targetUser->name,
                    'is_active' => $targetUser->is_active,
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all users (Admin only)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUsers(Request $request)
    {
        try {
            $currentUser = $request->user();

            // Check if user is admin
            if ($currentUser->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Admin access required.'
                ], 403);
            }

            $users = User::select([
                'id', 
                'name', 
                'email', 
                'mobile_number', 
                'role', 
                'serial_number', 
                'serial_expires_at', 
                'is_active',
                'created_at'
            ])->get();

            return response()->json([
                'success' => true,
                'data' => $users
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}