<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
// use Illuminate\Container\Attributes\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class GoogleController extends Controller
{
    public function redirectToGoogle(Request $request)
    {
        return Socialite::driver('google')->redirect();
    }

    // public function handleGoogleCallback()
    // {
    //     try {
    //         $user = Socialite::driver('google')->user();

    //         $existingUser = User::where('google_id', $user->id)->first();

    //         // if (!$existingUser) {
    //         //     $existingUser = User::where('email', $user->email)->first();
    //         // }
    //         if ($existingUser) {
    //             // Log the user in
    //             Auth::login($existingUser, true); // true pour "remember me"
    //         } else {
    //             // Create a new user
    //             $newUser = User::create([
    //                 'name' => $user->name,
    //                 'email' => $user->email,
    //                 'password' => Hash::make(Str::random(16)), // mot de passe aléatoire
    //                 'google_id' => $user->id,
    //                 'is_verified' => true,

    //             ]);
    //             // Auth::login($newUser);
    //             Auth::login($newUser, true); // true pour "remember me"
    //         }

    //         return redirect('/profile');
    //     } catch (\Exception $e) {
    //         Log::error('Google login error: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
    //         return redirect('/login')->withErrors($e->getMessage()); // ← affiche l'erreur vraie
    //     }
    // }
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            // dd($googleUser);
            // Cherche par google_id d'abord
            $existingUser = User::where('google_id', $googleUser->id)->first();
            // dd($existingUser);
            // Sinon par email
            if (!$existingUser) {
                $existingUser = User::where('email', $googleUser->email)->first();
                // dd($existingUser);
                if ($existingUser) {
                    // Lie le google_id au compte existant
                    $existingUser->update(['google_id' => $googleUser->id]);
                }
            }

            if ($existingUser) {
                $auth =  Auth::login($existingUser, true);
            } else {
                $newUser = User::create([
                    'name'        => $googleUser->getName(),
                    'email'       => $googleUser->getEmail(),
                    'password'    => Hash::make(Str::random(24)),
                    'google_id'   => $googleUser->getId(),
                    'is_verified' => true,
                ]);
                // dd($newUser);
                Auth::login($newUser, true);
            }

            session()->regenerate();

            return redirect()->intended('/profile');
        } catch (\Exception $e) {
            Log::error('Google login error: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
            return redirect('/login')->withErrors(['google' => $e->getMessage()]);
        }
    }
}
