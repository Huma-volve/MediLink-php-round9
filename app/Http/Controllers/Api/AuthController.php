<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    // ============================================
    // EXISTING EMAIL/PASSWORD AUTH METHODS
    // ============================================

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'token' => $token,
                'user'  => $user
            ]
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'phone';

        $user = User::where($loginType, $request->login)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        if (! $user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Account is disabled'
            ], 403);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
                'user'  => $user
            ]
        ]);
    }

    // ============================================
    // NEW GOOGLE OAUTH METHODS
    // ============================================

    /**
     * Get Google OAuth URL for frontend
     */
    public function googleAuthUrl()
    {
        try {
            $url = Socialite::driver('google')
                ->stateless()
                ->redirect()
                ->getTargetUrl();

            return response()->json([
                'success' => true,
                'message' => 'Google OAuth URL generated',
                'data' => ['url' => $url]
            ]);
        } catch (\Exception $e) {
            Log::error('Google OAuth URL Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate Google OAuth URL',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle Google OAuth callback with authorization code
     * Supports both GET (from Google redirect) and POST (from frontend API)
     */
    public function googleCallback(Request $request)
    {
        // Get code from either GET query parameter or POST body
        $code = $request->get('code') ?: $request->input('code');

        // Validate the code
        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Authorization code is required'
            ], 400);
        }

        if (strlen($code) < 10) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid authorization code'
            ], 400);
        }

        try {
            // Exchange code for access token
            $accessToken = $this->exchangeGoogleCodeForToken($code);

            // Get user data from Google
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->userFromToken($accessToken);

            // Find or create user
            $user = $this->findOrCreateUserFromGoogle($googleUser);

            // Create API token
            $token = $user->createToken('google_oauth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Google login successful',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'role' => $user->role,
                        'avatar' => $user->avatar,
                        'provider' => $user->provider,
                        'email_verified_at' => $user->email_verified_at,
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Google authentication failed',
                'error' => $e->getMessage()
            ], 401);
        }
    }

    /**
     * Exchange Google authorization code for access token
     */
    private function exchangeGoogleCodeForToken($code)
    {
        try {
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'code' => $code,
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'redirect_uri' => config('services.google.redirect'),
                'grant_type' => 'authorization_code'
            ]);

            if (!$response->successful()) {
                $error = $response->json();
                throw new \Exception($error['error_description'] ?? 'Token exchange failed');
            }

            $data = $response->json();

            if (!isset($data['access_token'])) {
                throw new \Exception('Access token not received from Google');
            }

            return $data['access_token'];
        } catch (\Exception $e) {
            throw new \Exception('Google OAuth token exchange failed: ' . $e->getMessage());
        }
    }

    /**
     * Find or create user from Google data
     * Auto-links with existing email if found
     */
    private function findOrCreateUserFromGoogle($googleUser)
    {
        // First, try to find user by Google ID
        $user = User::where('google_id', $googleUser->getId())->first();

        if ($user) {
            // Update avatar if needed
            if (!$user->avatar && $googleUser->getAvatar()) {
                $user->update(['avatar' => $googleUser->getAvatar()]);
            }
            return $user;
        }

        // Check if user exists with same email
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Link existing user with Google account
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'provider' => 'google',
            ]);
            return $user;
        }

        // Create new user from Google data
        // Note: Generate random phone number or leave null based on your requirements
        $phone = 'google_' . Str::random(10); // Temporary phone for Google users

        return User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'phone' => $phone, // You might want to make this nullable
            'password' => Hash::make(Str::random(24)), // Random password for Google users
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
            'provider' => 'google',
            'role' => 'patient', // Default role for Google users
            //'is_active' => true,
            'email_verified_at' => now(), // Google emails are verified
        ]);
    }

    /**
     * Link existing account with Google
     * (Optional - for users who want to link Google after regular registration)
     */
    public function linkGoogleAccount(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        try {
            $user = $request->user();

            // Prevent linking if already has Google account
            if ($user->google_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Account already linked with Google'
                ], 400);
            }

            // Exchange code for token
            $accessToken = $this->exchangeGoogleCodeForToken($request->code);

            // Get Google user data
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->userFromToken($accessToken);

            // Check if Google account is already linked to another user
            $existingGoogleUser = User::where('google_id', $googleUser->getId())->first();
            if ($existingGoogleUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'This Google account is already linked to another user'
                ], 400);
            }

            // Update user with Google info
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar() ?: $user->avatar,
                'provider' => 'google',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Google account linked successfully',
                'data' => ['user' => $user]
            ]);
        } catch (\Exception $e) {
            Log::error('Link Google Account Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to link Google account',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Unlink Google account from user
     */
    public function unlinkGoogleAccount(Request $request)
    {
        $user = $request->user();

        if (!$user->google_id) {
            return response()->json([
                'success' => false,
                'message' => 'Account is not linked with Google'
            ], 400);
        }

        $user->update([
            'google_id' => null,
            'provider' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Google account unlinked successfully',
            'data' => ['user' => $user]
        ]);
    }

    // ============================================
    // SHARED AUTH METHODS
    // ============================================

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out from all devices'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $request->user()
            ]
        ]);
    }

    /**
     * Check if user has Google linked
     */
    public function checkGoogleLinked(Request $request)
    {
        $hasGoogle = !empty($request->user()->google_id);

        return response()->json([
            'success' => true,
            'data' => [
                'has_google_linked' => $hasGoogle,
                'provider' => $request->user()->provider
            ]
        ]);
    }
}
