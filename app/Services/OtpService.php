<?php
// app/Services/OtpService.php
namespace App\Services;

use App\Models\OtpCode;
class OtpService
{
    public static function send(string $phone, string $type): void
    {
        $code = rand(100000, 999999);

        OtpCode::where('phone', $phone)
            ->where('type', $type)
            ->where('used', false)
            ->update(['used' => true]);

        OtpCode::create([
            'phone' => $phone,
            'code' => $code,
            'type' => $type,
            'expires_at' => now()->addMinutes(5),
        ]);

        sendWhatsAppMessage(
            $phone,
            "رمز التحقق الخاص بك هو: $code"
        );
    }

    public static function verify(string $phone, string $code, string $type): bool
    {
        $otp = OtpCode::where([
            'phone' => $phone,
            'code' => $code,
            'type' => $type,
            'used' => false,
        ])
        ->where('expires_at', '>', now())
        ->first();

        if (!$otp) return false;

        $otp->update(['used' => true]);
        return true;
    }
}