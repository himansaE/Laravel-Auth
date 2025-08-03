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
        return Socialite::driver('google')
            ->scopes([
                'https://www.googleapis.com/auth/calendar.readonly',
                'https://www.googleapis.com/auth/tasks.readonly',
                'https://www.googleapis.com/auth/gmail.readonly'
            ])
            ->with([
                'access_type' => 'offline',
                'prompt' => 'consent'
            ])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Unable to login with Google: ' . $e->getMessage());
        }

        if (!$googleUser) {
            \Log::error('No Google user data received');
            return redirect('/')->with('error', 'No user data received from Google.');
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
                    'google_avatar' => $googleUser->getAvatar(),
                ]
            );

            Auth::login($user);
            \Log::info('User logged in successfully: ' . $user->email);
            // Redirect to dashboard using route helper
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            \Log::error('User creation error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }
} 