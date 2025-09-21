<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;


class CustomLoginController extends Controller
{
    public function showLoginForm()
    { 
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $role = 'admin';
        // Valider les champs
        $request->validate([
            'phone_number' => 'required',
            'password' => 'required',
        ]);

        // Tentative de connexion avec le numéro de téléphone
        if (Auth::attempt(['phone_number' => $request->phone_number, 'password' => $request->password],)) {

            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('home');
            }
            // dd('Connexion réussie');
        } else {
            // dd($request->password);
            return back()->withErrors([
                'phone_number' => 'The provided credentials do not match our records.',
            ]);
        }
    } 
}
