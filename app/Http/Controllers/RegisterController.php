<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OtpCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Str;

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

        Session::put('pending_user_' . $request->phone_number, $pendingUser);

        // Generate OTP
        $otp = sprintf("%06d", rand(0, 999999));

        // Store OTP
        OtpCode::where('phone_number', $request->phone_number)->delete();
        OtpCode::create([
            'phone_number' => $request->phone_number,
            'code'         => $otp,
            'expires_at'   => now()->addMinutes(3),
        ]);

        // SEND OTP via BkassouaSMS
        $this->sendOtp($request->phone_number, $otp);

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
                // Handle registration
                $sessionKey = 'pending_user_' . $request->phone_number;
                $pendingUser = Session::get($sessionKey);

                if (!$pendingUser) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Aucune inscription en attente pour ce numéro. Veuillez vous inscrire à nouveau.',
                    ], 404);
                }

                // Check session expiration
                if (Carbon::now()->gt(Carbon::parse($pendingUser['expires_at']))) {
                    Session::forget($sessionKey);
                    $otpRecord->delete();
                    return response()->json([
                        'success' => false,
                        'message' => 'Votre session d’inscription a expiré. Veuillez recommencer l’inscription.',
                    ], 422);
                }

                // Check for duplicates
                $existingUser = User::where('phone_number', $pendingUser['phone_number'])
                    ->orWhere('email', $pendingUser['email'] ?? null)
                    ->first();

                if ($existingUser) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ce numéro ou cet email est déjà utilisé.',
                    ], 409);
                }

                // Create user
                $user = User::create([
                    'name' => $pendingUser['name'],
                    'email' => $pendingUser['email'],
                    'phone_number' => $pendingUser['phone_number'],
                    'address' => $pendingUser['address'],
                    'password' => $pendingUser['password'], // Pre-hashed
                    'is_verified' => true,
                ]);

                // Clean up
                Session::forget($sessionKey);
                $otpRecord->delete();

                // Generate token and login
                $token = $user->createToken('bkassoua_back')->plainTextToken;
                Auth::login($user);
                return redirect(route('home'));
            } elseif ($request->type === 'password_reset') {
                // Handle password reset
                $user = User::where('phone_number', $request->phone_number)->first();

                if (!$user) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Utilisateur non trouvé.',
                    ], 404);
                }

                // Clean up OTP
                $otpRecord->delete();

                // Generate temporary token for password reset
                $resetToken = Str::random(60);
                Cache::put('password_reset_token_' . $user->id, $resetToken, now()->addMinutes(30));
                return redirect(route('loginForm'));
                // return response()->json([
                //     'success' => true,
                //     'message' => 'OTP vérifié. Vous pouvez réinitialiser votre mot de passe.',
                //     'data' => [
                //         'reset_token' => $resetToken,
                //         'phone_number' => $user->phone_number,
                //     ],
                // ], 200);
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
