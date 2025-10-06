<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Twilio\Rest\Client;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class RegisterController extends Controller
{
    public function step1(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Enregistrer les données en session
        Session::put('register_data', $validated);

        // Générer et envoyer OTP
        $otp = rand(100000, 999999);
        // $this->sendOTP($validated['phone_number'], $otp);
// dd($otp);
        Log::info("OTP for {$validated['phone_number']}: $otp");
        // Stocker OTP en session
        Session::put('register_otp', $otp);
        Session::put('register_phone', $validated['phone_number']);
        Session::put('otp_expires_at', now()->addMinutes(10));

        return redirect()->route('verify.otp.form');
    }

    public function showVerifyOtpForm()
    {
        if (!Session::has('register_data')) {
            return redirect()->route('register')->withErrors(['error' => 'Session expirée. Veuillez recommencer.']);
        }

        return view('otp.verify-otp');
    }

    public function step2(Request $request)
    {
        try {
            $request->validate(['otp' => 'required|numeric|digits:6']);

            if (!Session::has('register_otp') || !Session::has('register_data')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session expirée. Veuillez recommencer.'
                ], 422);
            }

            if (Session::get('register_otp') != $request->otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Code OTP invalide.'
                ], 422);
            }

            if (now()->gt(Session::get('otp_expires_at'))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le code OTP a expiré.'
                ], 422);
            }

            // Créer l'utilisateur
            $userData = Session::get('register_data');
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'phone_number' => $userData['phone_number'],
                'address' => $userData['address'],
                'password' => Hash::make($userData['password']),
            ]);

            // Nettoyer la session
            Session::forget(['register_data', 'register_otp', 'register_phone', 'otp_expires_at']);

            // Connecter l'utilisateur
            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Inscription réussie !',
                'redirect' => route('home')
            ]);
        } catch (\Exception $e) {
            \Log::error('Step2 error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue. Veuillez réessayer.'
            ], 500);
        }
    }

    public function resendOtp(Request $request)
    {
        try {
            if (!Session::has('register_phone')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session expirée. Veuillez recommencer.'
                ], 400);
            }

            $otp = rand(100000, 999999);
            $this->sendOTP(Session::get('register_phone'), $otp);

            Session::put('register_otp', $otp);
            Session::put('otp_expires_at', now()->addMinutes(10));

            return response()->json([
                'success' => true,
                'message' => 'Nouveau code envoyé.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Resend OTP error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Échec de l\'envoi du code. Veuillez réessayer.'
            ], 500);
        }
    }

    // protected function sendOTP($phoneNumber, $otp)
    // {
    //     $sid = config('services.twilio.sid');
    //     $token = config('services.twilio.token');
    //     $from = config('services.twilio.phone_number');

    //     $twilio = new Client($sid, $token);

    //     $message = $twilio->messages->create(
    //         $this->formatPhoneNumber($phoneNumber),
    //         [
    //             'from' => $from,
    //             'body' => "Votre code de vérification B Kassoua est: $otp"
    //         ]
    //     );
    // }

    protected function formatPhoneNumber($phoneNumber)
    {
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Format pour Niger (+227)
        if (strlen($cleaned) === 8) {
            return '+227' . $cleaned;
        }

        return '+' . $cleaned;
    }
}