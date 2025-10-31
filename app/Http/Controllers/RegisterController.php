<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Validator;



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
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone_number' => 'required|string|regex:/^[0-9]{8}$/|unique:users,phone_number',
                'address' => 'required|string|max:255',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $code = sprintf("%06d", rand(0, 999999));

            OtpCode::create([
                'phone_number' => $request->phone_number,
                'code' => $code, // En clair pour correspondre aux logs
                'expires_at' => now()->addMinutes(3),
            ]);

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

                Log::info('BkassouaSMS Response', [
                    'phone_number' => $request->phone_number,
                    'otp' => $code,
                    'response_status' => $response->status(),
                    'response_body' => $response->body(),
                ]);

                if ($response->successful()) {
                    return response()->json(['success' => true, 'message' => 'Code OTP envoyé.']);
                } else {
                    return response()->json(['success' => false, 'message' => 'Erreur lors de l\'envoi de l\'OTP.'], 500);
                }
            } catch (\Exception $e) {
                Log::error('SMS sending failed', [
                    'phone_number' => $request->phone_number,
                    'error' => $e->getMessage(),
                ]);
                return response()->json(['success' => false, 'message' => 'Erreur lors de l\'envoi de l\'OTP : ' . $e->getMessage()], 500);
            }
        } catch (\Exception $e) {
            Log::error('Registration failed', [
                'phone_number' => $request->phone_number,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['success' => false, 'message' => 'Erreur lors de l\'inscription : ' . $e->getMessage()], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $phoneNumber = preg_replace('/^\+?227/', '', $request->phone_number);
            $request->merge(['phone_number' => $phoneNumber]);

            $request->validate([
                'phone_number' => 'required|string|regex:/^[0-9]{8}$/',
                'otp' => 'required|numeric|digits:6',
                'type' => 'required|string|in:register,password_reset',
                'user_data' => 'required_if:type,register|array',
                'user_data.name' => 'required_if:type,register|string|max:255',
                'user_data.email' => 'nullable|email|max:255',
                'user_data.phone_number' => 'required_if:type,register|string|regex:/^[0-9]{8}$/',
                'user_data.address' => 'required_if:type,register|string|max:255',
                'user_data.password' => 'required_if:type,register|string|min:8',
            ]);

            Log::info('Verifying OTP', [
                'phone_number' => $request->phone_number,
                'otp' => $request->otp,
                'type' => $request->type,
            ]);

            $otpRecord = OtpCode::where('phone_number', $request->phone_number)->first();
            Log::info('OTP Record Check', [
                'phone_number' => $request->phone_number,
                'otp_submitted' => $request->otp,
                'otp_record' => $otpRecord ? $otpRecord->toArray() : null,
            ]);

            if (!$otpRecord || $request->otp !== $otpRecord->code) {
                return response()->json([
                    'success' => false,
                    'message' => 'Code OTP invalide ou non trouvé.',
                ], 422);
            }

            if (Carbon::now()->gt($otpRecord->expires_at)) {
                $otpRecord->delete();
                return response()->json([
                    'success' => false,
                    'message' => 'Le code OTP a expiré. Veuillez demander un nouvel OTP.',
                ], 422);
            }

            if ($request->type === 'register') {
                $userData = $request->user_data;

                // Vérifier que le phone_number correspond
                if ($userData['phone_number'] !== $request->phone_number) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Le numéro de téléphone ne correspond pas.',
                    ], 422);
                }

                $existingUser = User::where('phone_number', $userData['phone_number'])
                    ->orWhere('email', $userData['email'] ?? null)
                    ->first();

                if ($existingUser) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ce numéro ou cet email est déjà utilisé.',
                    ], 409);
                }

                $user = User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'phone_number' => $userData['phone_number'],
                    'address' => $userData['address'],
                    'password' => Hash::make($userData['password']),
                    'is_verified' => true,
                ]);

                $otpRecord->delete();

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
                $user = User::where('phone_number', $request->phone_number)->first();
                if (!$user) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Utilisateur non trouvé.',
                    ], 404);
                }

                $otpRecord->delete();

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

    public function resendOtp(Request $request)
    {
        try {
            $phoneNumber = preg_replace('/^\+?227/', '', $request->phone_number);
            $request->merge(['phone_number' => $phoneNumber]);

            $request->validate([
                'phone_number' => 'required|string|regex:/^[0-9]{8}$/',
                'type' => 'required|string|in:register,password_reset',
            ]);

            Log::info('Resending OTP', [
                'phone_number' => $request->phone_number,
                'type' => $request->type,
            ]);

            if ($request->type === 'password_reset') {
                $user = User::where('phone_number', $request->phone_number)->first();
                if (!$user) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Utilisateur non trouvé.',
                    ], 404);
                }
            }

            // Supprimer l'ancien OTP
            OtpCode::where('phone_number', $request->phone_number)->delete();

            // Générer un nouvel OTP
            $code = sprintf("%06d", rand(0, 999999));

            OtpCode::create([
                'phone_number' => $request->phone_number,
                'code' => $code, // En clair pour correspondre aux logs
                'expires_at' => now()->addMinutes(3),
            ]);

            // Envoyer l'OTP via BkassouaSMS
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

                Log::info('BkassouaSMS Response', [
                    'phone_number' => $request->phone_number,
                    'otp' => $code,
                    'response_status' => $response->status(),
                    'response_body' => $response->body(),
                ]);

                if ($response->successful()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Nouveau code OTP envoyé.',
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur lors de l\'envoi de l\'OTP.',
                    ], 500);
                }
            } catch (\Exception $e) {
                Log::error('SMS sending failed', [
                    'phone_number' => $request->phone_number,
                    'error' => $e->getMessage(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'envoi de l\'OTP : ' . $e->getMessage(),
                ], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation error during OTP resend', [
                'phone_number' => $request->phone_number,
                'errors' => $e->errors(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('OTP resend failed', [
                'phone_number' => $request->phone_number,
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du renvoi de l’OTP.',
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
    // Envoie un OTP au téléphone
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|exists:users,phone_number',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Numéro invalide ou non enregistré',
                'errors' => $validator->errors(),
            ], 422);
        }

        $phone = $request->phone_number;
       
            $code = sprintf("%06d", rand(0, 999999));

            OtpCode::create([
                'phone_number' => $request->phone_number,
                'code' => $code, // En clair pour correspondre aux logs
                'expires_at' => now()->addMinutes(3),
            ]);

        // Stocker OTP en cache pendant 10 minutes
        Cache::put('otp_' . $phone, $code, now()->addMinutes(10));

        // TODO: appeler service SMS ici avec $otp et $phone
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

                Log::info('BkassouaSMS Response', [
                    'phone_number' => $request->phone_number,
                    'otp' => $code,
                    'response_status' => $response->status(),
                    'response_body' => $response->body(),
                ]);

                if ($response->successful()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Nouveau code OTP envoyé.',
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur lors de l\'envoi de l\'OTP.',
                    ], 500);
                }
            } catch (\Exception $e) {
                Log::error('SMS sending failed', [
                    'phone_number' => $request->phone_number,
                    'error' => $e->getMessage(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'envoi de l\'OTP : ' . $e->getMessage(),
                ], 500);
            }        // Pour tests, on renvoie l'otp (à enlever en prod)
        return response()->json([
            'success' => true,
            'message' => "Code OTP envoyé au $phone",
            'otp' => $code,
        ]);
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
    public function forgotPassword(Request $request)
    {
        
    }
     // Réinitialise le mot de passe avec le reset_token
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|exists:users,phone_number',
            'password' => 'required|string|min:8|confirmed',
            'reset_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation échouée',
                'errors' => $validator->errors(),
            ], 422);
        }

        $phone = $request->phone_number;
        $token = $request->reset_token;

        $cachedToken = Cache::get('reset_token_' . $phone);

        if (!$cachedToken || $cachedToken !== $token) {
            return response()->json([
                'success' => false,
                'message' => 'Token de réinitialisation invalide ou expiré',
            ], 400);
        }

        $user = User::where('phone_number', $phone)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé',
            ], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Supprimer token après usage
        Cache::forget('reset_token_' . $phone);

        return response()->json([
            'success' => true,
            'message' => 'Mot de passe réinitialisé avec succès',
        ]);
    }
}
