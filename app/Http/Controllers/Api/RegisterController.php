<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OtpCode;
use App\Models\PendingRegistration;
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
// use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
// use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Http;



class RegisterController extends Controller
{
    /**
     * Register a new user and send OTP (Step 1)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
     
     
//   
  public function register(Request $request)
{
    try {
        $phoneNumber = preg_replace('/^\+?227/', '', $request->phone_number);
        $request->merge(['phone_number' => $phoneNumber]);
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'nullable|email|max:255|unique:users,email',
            'phone_number' => 'required|string|regex:/^[0-9]{8}$/|unique:users,phone_number',
            'address'      => 'required|string|max:255',
            'password'     => 'required|string|min:8|confirmed',
        ]);

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

        // Créer OTP
        OtpCode::create([
            'phone_number' => $request->phone_number,
            'code'         => $otp,
            'expires_at'   => now()->addMinutes(10),
        ]);

        // Envoi SMS + Email
        $smsSuccess = false;
        $emailSuccess = false;

        try {
            $response = Http::withBasicAuth(env('SMS_API_USERNAME'), env('SMS_API_PASSWORD'))
                ->post(env('SMS_API_URL'), [
                    'to'      => '227' . $request->phone_number,
                    'from'    => env('SMS_SENDER'),
                    'content' => "Votre code OTP est : $otp. Valable 3 minutes.",
                ]);
            $smsSuccess = $response->successful();
        } catch (\Exception $e) {
            Log::error('SMS failed', ['phone' => $request->phone_number]);
        }

        if ($request->filled('email')) {
            try {
                Mail::to($request->email)->send(new OtpMail($otp, $request->name, $request->phone_number));
                $emailSuccess = true;
            } catch (\Exception $e) {
                Log::error('Email failed', ['email' => $request->email]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => $smsSuccess && $emailSuccess 
                ? 'Code OTP envoyé par SMS et email.' 
                : 'Code OTP envoyé avec succès.',
            'phone_number' => $request->phone_number
        ]);

    } catch (\Exception $e) {
        Log::error('Register error', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => 'Erreur lors de l\'inscription.'], 500);
    }
}
    
/**
 * Vérification de l'OTP - Version Finale avec PendingRegistration (DB)
 */
public function verifyOtp(Request $request)
{
    try {
        // Nettoyage du numéro
        $phoneNumber = preg_replace('/^\+?227/', '', $request->phone_number);
        $request->merge(['phone_number' => $phoneNumber]);

        $request->validate([
            'phone_number' => 'required|string|regex:/^[0-9]{8}$/',
            'otp'          => 'required|numeric|digits:6',
            'type'         => 'required|string|in:register,password_reset,login',
        ]);

        Log::info('=== VÉRIFICATION OTP DÉBUT ===', [
            'phone_number' => $request->phone_number,
            'otp'          => $request->otp,
            'type'         => $request->type,
        ]);

        // Récupérer l'OTP
        $otpRecord = OtpCode::where('phone_number', $request->phone_number)->first();

        if (!$otpRecord || $request->otp !== $otpRecord->code) {
            Log::warning('OTP invalide', ['phone_number' => $request->phone_number, 'otp_submitted' => $request->otp]);
            return response()->json([
                'success' => false,
                'message' => 'Code OTP invalide.',
            ], 422);
        }

        if (Carbon::now()->gt($otpRecord->expires_at)) {
            $otpRecord->delete();
            return response()->json([
                'success' => false,
                'message' => 'Le code OTP a expiré. Veuillez demander un nouvel OTP.',
            ], 422);
        }

        // ====================== TYPE REGISTER ======================
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

        return response()->json([
            'success' => false,
            'message' => 'Type de vérification non supporté.',
        ], 400);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur de validation.',
            'errors'  => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        Log::error('Erreur critique dans verifyOtp', [
            'phone_number' => $request->phone_number ?? null,
            'error'        => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Une erreur est survenue lors de la vérification.',
        ], 500);
    }
}
    // public function resendOtp(Request $request)
    // {
    //     try {
    //         $phoneNumber = preg_replace('/^\+?227/', '', $request->phone_number);
    //         $request->merge(['phone_number' => $phoneNumber]);

    //         $request->validate([
    //             'phone_number' => 'required|string|regex:/^[0-9]{8}$/',
    //             'type' => 'required|string|in:register,password_reset,login',
    //         ]);

    //         Log::info('Resending OTP', [
    //             'phone_number' => $request->phone_number,
    //             'type' => $request->type,
    //         ]);

    //         if ($request->type === 'password_reset') {
    //             $user = User::where('phone_number', $request->phone_number)->first();
    //             if (!$user) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Utilisateur non trouvé.',
    //                 ], 404);
    //             }
    //         }

    //         // Supprimer l'ancien OTP
    //         OtpCode::where('phone_number', $request->phone_number)->delete();

    //         // Générer un nouvel OTP
    //         $code = sprintf("%06d", rand(0, 999999));

    //         OtpCode::create([
    //             'phone_number' => $request->phone_number,
    //             'code' => $code, // En clair pour correspondre aux logs
    //             'expires_at' => now()->addMinutes(3),
    //         ]);

    //         // Envoyer l'OTP via BkassouaSMS
    //         try {
    //             $response = Http::withBasicAuth(env('SMS_API_USERNAME'), env('SMS_API_PASSWORD'))
    //                 ->post(env('SMS_API_URL'), [
    //                     'to' => '227' . $request->phone_number,
    //                     'from' => env('SMS_SENDER'),
    //                     'content' => "Votre code OTP est : $code",
    //                     'dlr' => 'yes',
    //                     'dlr-level' => 3,
    //                     'dlr-method' => 'GET',
    //                     'dlr-url' => env('SMS_DLR_URL'),
    //                 ]);

    //             Log::info('BkassouaSMS Response', [
    //                 'phone_number' => $request->phone_number,
    //                 'otp' => $code,
    //                 'response_status' => $response->status(),
    //                 'response_body' => $response->body(),
    //             ]);

    //             if ($response->successful()) {
    //                 return response()->json([
    //                     'success' => true,
    //                     'message' => 'Nouveau code OTP envoyé.',
    //                 ], 200);
    //             } else {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Erreur lors de l\'envoi de l\'OTP.',
    //                 ], 500);
    //             }
    //         } catch (\Exception $e) {
    //             Log::error('SMS sending failed', [
    //                 'phone_number' => $request->phone_number,
    //                 'error' => $e->getMessage(),
    //             ]);
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Erreur lors de l\'envoi de l\'OTP : ' . $e->getMessage(),
    //             ], 500);
    //         }
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         Log::warning('Validation error during OTP resend', [
    //             'phone_number' => $request->phone_number,
    //             'errors' => $e->errors(),
    //         ]);
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Erreur de validation.',
    //             'errors' => $e->errors(),
    //         ], 422);
    //     } catch (\Exception $e) {
    //         Log::error('OTP resend failed', [
    //             'phone_number' => $request->phone_number,
    //             'error' => $e->getMessage(),
    //         ]);
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Une erreur est survenue lors du renvoi de l’OTP.',
    //         ], 500);
    //     }
    // }
    /**
 * Renvoi d'OTP (SMS + Email)
 */
public function resendOtp(Request $request)
{
    try {
        // Nettoyage du numéro de téléphone
        $phoneNumber = preg_replace('/^\+?227/', '', $request->phone_number);
        $request->merge(['phone_number' => $phoneNumber]);

        $request->validate([
            'phone_number' => 'required|string|regex:/^[0-9]{8}$/',
            'type'         => 'required|string|in:register,password_reset,login',
        ]);

        Log::info('Tentative de renvoi OTP', [
            'phone_number' => $request->phone_number,
            'type'         => $request->type,
        ]);

        // Vérification utilisateur pour password_reset
        if ($request->type === 'password_reset') {
            $user = User::where('phone_number', $request->phone_number)->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non trouvé.',
                ], 404);
            }
        }

        // Récupérer les données de session (pour l'email si disponible)
        $sessionKey = 'pending_user_' . $request->phone_number;
        // $pendingUser = Session::get($sessionKey);
        $pendingUser = Cache::get('pending_user_' . $request->phone_number);

        // Supprimer l'ancien OTP
        OtpCode::where('phone_number', $request->phone_number)->delete();

        // Générer nouveau OTP
        $otp = sprintf("%06d", rand(0, 999999));

        OtpCode::create([
            'phone_number' => $request->phone_number,
            'code'         => $otp,
            'expires_at'   => now()->addMinutes(3),
        ]);

        $smsSuccess = false;
        $emailSuccess = false;

        // ====================== ENVOI SMS ======================
        try {
            $response = Http::withBasicAuth(env('SMS_API_USERNAME'), env('SMS_API_PASSWORD'))
                ->post(env('SMS_API_URL'), [
                    'to'          => '227' . $request->phone_number,
                    'from'        => env('SMS_SENDER'),
                    'content'     => "Votre code OTP est : $otp. Valable pendant 3 minutes.",
                    'dlr'         => 'yes',
                    'dlr-level'   => 3,
                    'dlr-method'  => 'GET',
                    'dlr-url'     => env('SMS_DLR_URL'),
                ]);

            Log::info('Resend SMS OTP', [
                'phone_number' => $request->phone_number,
                'otp'          => $otp,
                'status'       => $response->status(),
                'body'         => $response->body(),
            ]);

            $smsSuccess = $response->successful();

        } catch (\Exception $e) {
            Log::error('Échec renvoi SMS OTP', [
                'phone_number' => $request->phone_number,
                'error'        => $e->getMessage()
            ]);
        }

        // ====================== ENVOI EMAIL ======================
        $email = $pendingUser['email'] ?? null;

        if ($email) {
            try {
                Mail::to($email)->send(new OtpMail(
                    $otp, 
                    $pendingUser['name'] ?? 'Utilisateur', 
                    $request->phone_number
                ));

                Log::info('Resend Email OTP', [
                    'email' => $email,
                    'otp'   => $otp
                ]);

                $emailSuccess = true;

            } catch (\Exception $e) {
                Log::error('Échec renvoi Email OTP', [
                    'email' => $email,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // ====================== RÉPONSE FINALE ======================
        if ($smsSuccess || $emailSuccess) {
            $message = 'Nouveau code OTP envoyé avec succès.';
            
            if ($smsSuccess && $emailSuccess) {
                $message = 'Nouveau code OTP envoyé par SMS et par email.';
            } elseif ($smsSuccess) {
                $message = 'Nouveau code OTP envoyé par SMS.';
            } elseif ($emailSuccess) {
                $message = 'Nouveau code OTP envoyé par email.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
            ], 200);
        }

        // Aucun envoi n'a réussi
        return response()->json([
            'success' => false,
            'message' => 'Impossible d\'envoyer le nouveau code OTP. Veuillez réessayer.',
        ], 500);

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::warning('Erreur de validation lors du renvoi OTP', [
            'phone_number' => $request->phone_number ?? null,
            'errors'       => $e->errors(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Données invalides.',
            'errors'  => $e->errors(),
        ], 422);

    } catch (\Exception $e) {
        Log::error('Erreur générale lors du renvoi OTP', [
            'phone_number' => $request->phone_number ?? null,
            'error'        => $e->getMessage(),
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
    $request->validate([
        'phone_number' => 'required|string|regex:/^[0-9]{8}$/',
    ]);

    $user = User::where('phone_number', $request->phone_number)->first();

    if (!$user) {
        return response()->json([
            'success' => false, 
            'message' => 'Aucun compte trouvé avec ce numéro.'
        ], 404);
    }

    // Générer OTP
    $code = rand(100000, 999999);

    // Supprimer ancien OTP
    OtpCode::where('phone_number', $request->phone_number)->delete();

    OtpCode::create([
        'phone_number' => $request->phone_number,
        'code' => $code,
        'expires_at' => now()->addMinutes(10),   // 10 minutes pour reset
        'type' => 'password_reset'   // important de distinguer
    ]);

    // Envoyer SMS
    try {
        $response = Http::withBasicAuth(env('SMS_API_USERNAME'), env('SMS_API_PASSWORD'))
            ->post(env('SMS_API_URL'), [
                'to' => '227' . $request->phone_number,
                'from' => env('SMS_SENDER'),
                'content' => "Votre code de réinitialisation est : $code (valable 10 min)",
            ]);

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'message' => 'Un code OTP a été envoyé sur votre numéro pour réinitialiser votre mot de passe.'
            ]);
        }
    } catch (\Exception $e) {
        Log::error('SMS reset password failed', ['error' => $e->getMessage()]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Erreur lors de l\'envoi du code.'
    ], 500);
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


       $phone = preg_replace('/^\+?227/', '', $request->phone_number);
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
