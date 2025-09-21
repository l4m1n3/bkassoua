<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use Illuminate\Validation\Rules;

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
}
