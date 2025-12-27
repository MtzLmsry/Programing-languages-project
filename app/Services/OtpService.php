<?php
namespace App\Services;
use App\Models\OtpCode;
use App\Models\User;

class OtpService
{
    public static function send(User $user, string $type): void
    {
        $code = rand(100000, 999999);

        OtpCode::where('user_id', $user->id)
            ->where('type', $type)
            ->where('used', false)
            ->update(['used' => true]);

        OtpCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'type' => $type,
            'expires_at' => now()->addMinutes(5),
        ]);

        sendWhatsAppMessage(
            $user->phone,
            "your verify code is: $code"
        );
    }

    public static function verify(User $user, string $code, string $type): bool
    {
        $otp = OtpCode::where([
                'user_id' => $user->id,
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