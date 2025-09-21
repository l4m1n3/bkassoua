<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OtpCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;


class RegisterController extends Controller
{
    /**
     * Register a new user and send OTP (Step 1)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users',
            'phone_number' => 'required|string|regex:/^[0-9]{8}$/|unique:users,phone_number',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $pendingUser = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'expires_at' => now()->addMinutes(30),
        ];

        Session::put('pending_user_' . $request->phone_number, $pendingUser);

        $code = sprintf("%06d", rand(0, 999999));

        OtpCode::create([
            'phone_number' => $request->phone_number,
            'code' => $code,
            'expires_at' => now()->addMinutes(3),
        ]);

        // Envoi de l'OTP via l'API BkassouaSMS
        try {
            $response = Http::withBasicAuth(env('SMS_API_USERNAME'), env('SMS_API_PASSWORD'))
                ->post(env('SMS_API_URL'), [
                    'to' => '227' . $request->phone_number,
                    'from' => env('SMS_SENDER'),
                    'content' => "Votre code OTP est : $code",
                    'dlr' => 'yes',
                    'dlr-level' => 3,
                    'dlr-method' => 'GET',
                    'dlr-url' => env('SMS_DLR_URL'),
                ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Code OTP envoyé.']);
            } else {
                // Gérer l'échec de l'envoi du SMS
                return response()->json(['success' => false, 'message' => 'Erreur lors de l\'envoi de l\'OTP.'], 500);
            }
        } catch (\Exception $e) {
            // Gérer les erreurs de connexion ou autres exceptions
            return response()->json(['success' => false, 'message' => 'Erreur lors de l\'envoi de l\'OTP : ' . $e->getMessage()], 500);
        }
    }

    /**
     * Verify OTP and complete registration (Step 2)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOtp(Request $request)
    {
        try {
            $phoneNumber = preg_replace('/^\+?227/', '', $request->phone_number);
            $request->merge(['phone_number' => $phoneNumber]);
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

                return response()->json([
                    'success' => true,
                    'message' => 'Inscription réussie !',
                    'data' => [
                        'user' => $user,
                        'token' => $token,
                    ],
                ], 201);
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

                return response()->json([
                    'success' => true,
                    'message' => 'OTP vérifié. Vous pouvez réinitialiser votre mot de passe.',
                    'data' => [
                        'reset_token' => $resetToken,
                        'phone_number' => $user->phone_number,
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
     * Resend OTP
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendOtp(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'phone_number' => 'required|string|regex:/^[0-9]{8}$/',
            ]);

            // Vérifier si les données existent dans la session
            $sessionKey = 'pending_user_' . $request->phone_number;
            $pendingUser = Session::get($sessionKey);

            if (!$pendingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune inscription en attente pour ce numéro. Veuillez vous inscrire à nouveau.',
                ], 404);
            }

            // Vérifier l'expiration de la session
            if (Carbon::now()->gt(Carbon::parse($pendingUser['expires_at']))) {
                Session::forget($sessionKey);
                return response()->json([
                    'success' => false,
                    'message' => 'Votre session d’inscription a expiré. Veuillez recommencer l’inscription.',
                ], 422);
            }

            // Supprime les anciens OTP
            OtpCode::where('phone_number', $request->phone_number)->delete();

            // Génère un nouvel OTP
            $otp = random_int(100000, 999999);

            // Mettre à jour l'expiration dans la session
            $pendingUser['expires_at'] = Carbon::now()->addMinutes(15)->toDateTimeString();
            Session::put($sessionKey, $pendingUser);

            // Envoie l'OTP
            $this->sendOTP($request->phone_number, $otp);

            return response()->json([
                'success' => true,
                'message' => 'Nouvel OTP envoyé à ' . $request->phone_number,
                'data' => [
                    'phone_number' => $request->phone_number,
                    'expires_at' => $pendingUser['expires_at'],
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erreur renvoi OTP pour phone_number: ' . $request->phone_number . ' - ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Échec du renvoi OTP: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send OTP via Twilio
     *
     * @param string $phoneNumber
     * @param string $otp
     * @return void
     * @throws \Exception
     */
    protected function sendOTP($phoneNumber, $otp)
    {
        try {
            $sid = config('services.twilio.sid');
            $token = config('services.twilio.token');
            $from = config('services.twilio.phone_number');

            $twilio = new Client($sid, $token);

            $twilio->messages->create(
                $this->formatPhoneNumber($phoneNumber),
                [
                    'from' => $from,
                    'body' => "Votre code de vérification B Kassoua est: $otp"
                ]
            );

            // Stocker l'OTP après envoi réussi
            $expiresAt = Carbon::now()->addMinutes(15);
            OtpCode::updateOrCreate(
                ['phone_number' => $phoneNumber],
                ['code' => $otp, 'expires_at' => $expiresAt]
            );
        } catch (\Exception $e) {
            Log::error('Erreur Twilio pour phone_number: ' . $phoneNumber . ' - ' . $e->getMessage());
            throw new \Exception('Échec de l\'envoi SMS: ' . $e->getMessage());
        }
    }

    /**
     * Format phone number for Niger (+227)
     *
     * @param string $phoneNumber
     * @return string
     * @throws \Exception
     */
    protected function formatPhoneNumber($phoneNumber)
    {
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Si le numéro commence par + ou est déjà au format international
        if (str_starts_with($phoneNumber, '+') || strlen($cleaned) > 8) {
            return '+' . $cleaned;
        }

        // Par défaut, assume Niger (+227) pour les numéros de 8 chiffres
        if (strlen($cleaned) === 8) {
            return '+227' . $cleaned;
        }

        throw new \Exception('Format de numéro de téléphone invalide.');
    }

    /**
     * Check if a pending registration exists
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPending(Request $request)
    {
        try {
            $request->validate([
                'phone_number' => 'required|string|regex:/^[0-9]{8}$/',
            ]);

            $sessionKey = 'pending_user_' . $request->phone_number;
            $pendingUser = Session::get($sessionKey);

            if (!$pendingUser || Carbon::now()->gt(Carbon::parse($pendingUser['expires_at']))) {
                Session::forget($sessionKey);
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune inscription en attente pour ce numéro. Veuillez vous inscrire à nouveau.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Inscription en attente trouvée.',
                'data' => [
                    'phone_number' => $request->phone_number,
                    'expires_at' => $pendingUser['expires_at'],
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erreur vérification inscription en attente pour phone_number: ' . $request->phone_number . ' - ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Échec de la vérification: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|regex:/^[0-9]{8}$/',
        ]);

        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé.'], 404);
        }

        $code = sprintf("%06d", rand(0, 999999));
        OtpCode::create([
            'phone_number' => $request->phone_number,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP
        // $user->notify(new OtpNotification($code));

        return response()->json(['success' => true, 'message' => 'Code OTP envoyé.']);
    }
}
