<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $appUrl = rtrim(config('app.url', '/'), '/');
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect($appUrl . '/')->with('error', 'Unable to login with Google: ' . $e->getMessage());
        }

        if (!$googleUser) {
            \Log::error('No Google user data received');
            return redirect($appUrl . '/')->with('error', 'No user data received from Google.');
        }

        \Log::info('Google user received: ' . $googleUser->getEmail());

        try {
            $user = User::updateOrCreate(
                [ 'email' => $googleUser->getEmail() ],
                [
                    'name' => $googleUser->getName(),
                    'password' => bcrypt(\Illuminate\Support\Str::random(32)),
                    'google_id' => $googleUser->getId(),
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                ]
            );

            Auth::login($user);
            \Log::info('User logged in successfully: ' . $user->email);
            // Redirect to dashboard using APP_URL
            return redirect($appUrl . '/dashboard');
        } catch (\Exception $e) {
            \Log::error('User creation error: ' . $e->getMessage());
            return redirect($appUrl . '/')->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }
} 