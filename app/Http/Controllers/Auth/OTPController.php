<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class OTPController extends Controller
{
    public function generateOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:10'
        ]);

        try {
            $otp = rand(100000, 999999);
            $phone = $request->phone;

            // Enregistrer en session
            session([
                'otp' => $otp,
                'phone' => $phone,
                'otp_expires_at' => now()->addMinutes(10)
            ]);

            // Envoyer le SMS
            $this->sendOTP($phone, $otp);

            return response()->json([
                'success' => true,
                'message' => 'OTP envoyé avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error("Erreur OTP: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi du code',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    protected function sendOTP($phoneNumber, $otp)
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from = config('services.twilio.phone_number');

        if (empty($sid) || empty($token) || empty($from)) {
            throw new \Exception('Configuration Twilio incomplète');
        }

        $twilio = new Client($sid, $token);

        $message = $twilio->messages->create(
            $this->formatPhoneNumber($phoneNumber),
            [
                'from' => $from,
                'body' => "Votre code de vérification est: $otp"
            ]
        );

        Log::info("SMS envoyé à $phoneNumber, SID: {$message->sid}");
    }

    protected function formatPhoneNumber($phoneNumber)
    {
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Format pour Maroc (ajuste selon tes besoins)
        if (strlen($cleaned) === 10 && $cleaned[0] === '0') {
            return '+212' . substr($cleaned, 1);
        }

        return '+' . $cleaned;
    }
    public function verifyOTP(Request $request)
    {
        $request->validate([
            // 'phone_number' => 'required|string',
            'otp' => 'required|digits:6'
        ]);

        if (
            session('otp') == $request->otp &&
           
            now()->lt(session('otp_expires_at'))
        ) {
            // Succès : OTP validé
            session()->forget(['otp', 'otp_expires_at', 'phone']);
            return response()->json(['success' => true, 'message' => 'OTP validé']);
        }

        return response()->json(['success' => false, 'message' => 'OTP invalide ou expiré'], 401);
    }
}
