<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OtpCode;
use App\Models\PendingRegistration;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class RegisterController extends Controller
{
    /**
     * -------------------------------
     * STEP 1 : Register (create pending user + send OTP)
     * -------------------------------
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'nullable|email|max:255|unique:users',
            'phone_number' => 'required|string|regex:/^[0-9]{8}$/|unique:users,phone_number',
            'address'      => 'required|string|max:255',
            'password'     => 'required|string|min:8|confirmed',
            'type' => 'required|string|in:register,password_reset',
        ]);

        // Stock pending user in session
        $pendingUser = [
            'name'       => $request->name,
            'email'      => $request->email,
            'phone_number' => $request->phone_number,
            'address'    => $request->address,
            'password'   => Hash::make($request->password),
            'expires_at' => now()->addMinutes(30),
            'type'       => $request->type,
        ];

        // Create pending registration

        // Generate OTP
        $otp = sprintf("%06d", rand(0, 999999));

        // Supprimer ancien pending + OTP
        PendingRegistration::where('phone_number', $request->phone_number)->delete();
        OtpCode::where('phone_number', $request->phone_number)->delete();

        // Créer l'enregistrement pending
        PendingRegistration::create([
            'phone_number' => $request->phone_number,
            'name'         => $request->name,
            'email'        => $request->email,
            'address'      => $request->address,
            'password'     => Hash::make($request->password),
            'expires_at'   => now()->addMinutes(30),
        ]);

        OtpCode::create([
            'phone_number' => $request->phone_number,
            'code'         => $otp,
            'expires_at'   => now()->addMinutes(10),
        ]);
        // Envoi SMS + Email
        $smsSuccess = false;
        $emailSuccess = false;
        // SEND OTP via BkassouaSMS
        try {
            $this->sendOtp($request->phone_number, $otp);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP SMS', [
                'phone_number' => $request->phone_number,
                'error' => $e->getMessage(),
            ]);
        }
        if ($request->filled('email')) {
            try {
                Mail::to($request->email)->send(new OtpMail($otp, $request->name, $request->phone_number));
                $emailSuccess = true;
            } catch (\Exception $e) {
                Log::error('Email failed', ['email' => $request->email]);
            }
        }
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Code OTP envoyé.',
        // ]);
        // Après avoir envoyé le code OTP avec succès
        return redirect()->route('verify.otp.form', ['phone_number' => $request->phone_number, 'type' => $request->type])
            ->with('success', 'Code OTP envoyé sur votre téléphone !');
    }

    /**
     * -------------------------------
     * STEP 2 : Verify OTP (register or reset password)
     * -------------------------------
     */
    public function verifyOtp(Request $request)
    {
        try {
            // Validate request
            $phoneNumber = preg_replace('/^\+?227/', '', $request->phone_number);
            $request->merge(['phone_number' => $phoneNumber]);
            $request->validate([
                'phone_number' => 'required|string|regex:/^[0-9]{8}$/',
                'otp' => 'required|numeric|digits:6',
                'type' => 'required|string|in:register,password_reset',
            ]);

            // Log for debugging
            Log::info('Verifying OTP', [
                'phone_number' => $request->phone_number,
                'type' => $request->type,
                'session_exists' => Session::has('pending_user_' . $request->phone_number),
            ]);

            // Find OTP record
            $otpRecord = OtpCode::where('phone_number', $request->phone_number)
                ->where('code', $request->otp)
                ->first();

            if (!$otpRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Code OTP invalide.',
                ], 422);
            }

            // Check if OTP is expired
            if (Carbon::now()->gt($otpRecord->expires_at)) {
                $otpRecord->delete();
                return response()->json([
                    'success' => false,
                    'message' => 'Le code OTP a expiré. Veuillez demander un nouvel OTP.',
                ], 422);
            }

            if ($request->type === 'register') {

                $pending = PendingRegistration::where('phone_number', $request->phone_number)->first();

                Log::info('Recherche PendingRegistration', [
                    'phone_number' => $request->phone_number,
                    'found'        => $pending ? true : false,
                    'expires_at'   => $pending ? $pending->expires_at : null,
                ]);
                Log::info('CHECK PENDING', [
                    'phone' => $request->phone_number,
                    'exists' => PendingRegistration::where('phone_number', $request->phone_number)->exists()
                ]);
                if (!$pending) {
                    $otpRecord->delete();
                    Log::warning('Aucune PendingRegistration trouvée', ['phone' => $request->phone_number]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Aucune donnée d\'inscription trouvée. Veuillez recommencer l\'inscription.',
                    ], 404);
                }

                // Vérifier expiration
                if ($pending->isExpired()) {
                    $pending->delete();
                    $otpRecord->delete();
                    return response()->json([
                        'success' => false,
                        'message' => 'Les données d\'inscription ont expiré. Veuillez recommencer l\'inscription.',
                    ], 422);
                }

                // Créer l'utilisateur
                $user = User::create([
                    'name'         => $pending->name,
                    'email'        => $pending->email,
                    'phone_number' => $pending->phone_number,
                    'address'      => $pending->address,
                    'password'     => $pending->password,
                ]);

                // Nettoyage complet
                $pending->delete();
                $otpRecord->delete();

                Log::info('Inscription réussie via OTP', ['user_id' => $user->id]);

                $token = $user->createToken('bkassoua_back')->plainTextToken;

                return response()->json([
                    'success' => true,
                    'message' => 'Inscription réussie !',
                    'data' => [
                        'user'  => $user,
                        'token' => $token,
                    ],
                ], 201);
            }


            // ====================== TYPE PASSWORD_RESET ======================
            elseif ($request->type === 'password_reset') {
                $user = User::where('phone_number', $request->phone_number)->first();
                if (!$user) {
                    $otpRecord->delete();
                    return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé.'], 404);
                }

                $otpRecord->delete();
                $resetToken = Str::random(60);
                Cache::put('reset_token_' . $user->phone_number, $resetToken, now()->addMinutes(30));

                return response()->json([
                    'success' => true,
                    'message' => 'OTP vérifié avec succès.',
                    'data' => [
                        'reset_token'  => $resetToken,
                        'phone_number' => $user->phone_number,
                    ],
                ], 200);
            }

            // ====================== TYPE LOGIN ======================
            elseif ($request->type === 'login') {
                $user = User::where('phone_number', $request->phone_number)->first();
                if (!$user) {
                    $otpRecord->delete();
                    return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé.'], 404);
                }

                $otpRecord->delete();
                $token = $user->createToken('bkassoua_back')->plainTextToken;

                return response()->json([
                    'success' => true,
                    'message' => 'Connexion réussie !',
                    'data' => [
                        'user'  => $user,
                        'token' => $token,
                    ],
                ], 200);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation error during OTP verification', [
                'phone_number' => $request->phone_number,
                'errors' => $e->errors(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('OTP verification failed', [
                'phone_number' => $request->phone_number,
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la vérification de l’OTP.',
            ], 500);
        }
    }
    /**
     * -------------------------------
     * Resend OTP
     * -------------------------------
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|regex:/^[0-9]{8}$/',
        ]);

        $sessionKey = 'pending_user_' . $request->phone_number;

        if (!Session::has($sessionKey)) {
            return response()->json(['success' => false, 'message' => 'Aucune inscription en attente.'], 404);
        }

        $pendingUser = Session::get($sessionKey);

        if (Carbon::now()->gt(Carbon::parse($pendingUser['expires_at']))) {
            Session::forget($sessionKey);
            return response()->json(['success' => false, 'message' => 'Session expirée.'], 422);
        }

        OtpCode::where('phone_number', $request->phone_number)->delete();

        $otp = sprintf("%06d", rand(0, 999999));

        OtpCode::create([
            'phone_number' => $request->phone_number,
            'code'         => $otp,
            'expires_at'   => now()->addMinutes(3),
        ]);

        $this->sendOtp($request->phone_number, $otp);

        return response()->json(['success' => true, 'message' => 'OTP renvoyé.'], 200);
    }

    /**
     * -------------------------------
     * Send OTP via API BkassouaSMS
     * -------------------------------
     */
    private function sendOtp($phone, $otp)
    {
        $response = Http::withBasicAuth(env('SMS_API_USERNAME'), env('SMS_API_PASSWORD'))
            ->post(env('SMS_API_URL'), [
                'to'      => '227' . $phone,
                'from'    => env('SMS_SENDER'),
                'content' => "Votre code OTP est : $otp",
            ]);

        if (!$response->successful()) {
            throw new \Exception("Erreur d’envoi SMS.");
        }
    }

    public function showVerifyOtpForm(Request $request)
    {
        $phoneNumber = $request->query('phone_number'); // passé via GET après inscription
        $type = $request->query('type'); // passé via GET après inscription
        // dd($phoneNumber);
        return view('auth.verify-otp', compact('phoneNumber', 'type'));
    }
}
