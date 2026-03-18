<?php

namespace App\Http\Controllers\Api;


use App\Models\Category;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // 'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone_number' => ['required', 'string', 'max:255'],
            'password' => ['required', Rules\Password::defaults()],

        ]);

        $userData = [
            'name' => $request->name,
            // 'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password)
        ];

        $user = User::create($userData);
        $token = $user->createToken('bkassoua_back')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ], 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'phone_number' => ['required', 'string', 'max:255'],
            'password' => ['required', 'min:8'],
        ]);
        $user = User::where('phone_number', $request->phone_number)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Identifiants invalides'
            ], 422);
        }

        $token = $user->createToken('bkassoua')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ], 200);
    }

    function categories()
    {
        $categories = Category::all();
        $token = $categories->createToken('bkassoua')->plainTextToken;

        return response([
            'categories' => $categories,
            'token' => $token
        ], 200);
    }


     public function redirectToGoogle(Request $request)
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            // dd($googleUser);
            // Cherche par google_id d'abord
            $existingUser = User::where('google_id', $googleUser->id)->stateless()->first();
            // dd($existingUser);
            // Sinon par email
            if (!$existingUser) {
                $existingUser = User::where('email', $googleUser->email)->stateless()->first();
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
