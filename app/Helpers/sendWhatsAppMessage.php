<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('sendWhatsAppMessage')) {

    function sendWhatsAppMessage(string $to, string $message): bool
    {
        $params = [
            'token' => env('ULTRAMSG_TOKEN'),
            'to'    => $to,
            'body'  => $message,
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => env('ULTRAMSG_API_URL') . "/" . env('ULTRAMSG_INSTANCE_ID') . "/messages/chat",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_HTTPHEADER => [
                "content-type: application/x-www-form-urlencoded"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            Log::error("UltraMsg Error: $err");
            return false;
        }

        Log::info("UltraMsg Response: $response");
        return true;
    }
}