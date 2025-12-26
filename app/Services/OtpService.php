<?php

namespace App\Services;

use App\Models\OtpCode;
use Twilio\Rest\Client;
use Carbon\Carbon;

class OtpService
{
    /**
     * Generate & Send OTP via WhatsApp
     */
    public static function generateAndSend($user, $type = 'register')
    {
        // حذف أي OTP قديم
        OtpCode::where('user_id', $user->id)
            ->where('type', $type)
            ->delete();

        // توليد كود OTP رقمي 6 أرقام
        $code = random_int(100000, 999999);

        // حفظه بالقاعدة
        OtpCode::create([
            'user_id'    => $user->id,
            'code'       => $code,
            'type'       => $type,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        // إرسال عبر واتساب (Twilio)
        self::sendWhatsApp($user->phone, $code);

        return true;
    }

    /**
     * Verify OTP
     */
    public static function verify($userId, $code, $type)
    {
        return OtpCode::where('user_id', $userId)
            ->where('code', $code)
            ->where('type', $type)
            ->where('expires_at', '>', now())
            ->exists();
    }

    /**
     * Send WhatsApp Message
     */
    private static function sendWhatsApp($phone, $code)
    {
        $sid   = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from  = 'whatsapp:' . config('services.twilio.whatsapp_from');

        $client = new Client($sid, $token);

        $client->messages->create(
            'whatsapp:' . $phone,
            [
                'from' => $from,
                'body' => "Your verification code is: {$code}\nValid for 5 minutes."
            ]
        );
    }
}