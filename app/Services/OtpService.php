<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OtpService
{
    public static function send($phone, $otp)
    {
        try {
            $token = config('services.fonnte.token');
            
            if (!$token) {
                Log::warning('Fonnte token not configured');
                return false;
            }
            
            $response = Http::withHeaders([
                'Authorization' => $token
            ])->post(
                'https://api.fonnte.com/send',
                [
                    'target' => $phone,
                    'message' => "Kode OTP Anda: $otp"
                ]
            );
            
            if ($response->failed()) {
                Log::error('Fonnte API Error', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('OTP Send Error: ' . $e->getMessage());
            return false;
        }
    }
}