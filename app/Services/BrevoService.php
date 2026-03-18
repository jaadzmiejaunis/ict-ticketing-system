<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;

class BrevoService
{
    /**
     * Send an email using the Brevo API.
     */
    public static function sendEmail($toEmail, $subject, $content)
    {
        /** @var string $apiKey */
        $apiKey = env('BREVO_API_KEY');

        $url = "https://api.brevo.com/v3/smtp/email";

        try {
            /** @var Response $response */
            $response = Http::withHeaders([
                'api-key' => $apiKey,
                'Content-Type' => 'application/json',
            ])
            ->timeout(10)
            ->post($url, [
                'sender' => [
                    'name'  => env('MAIL_FROM_NAME'),
                    'email' => env('MAIL_FROM_ADDRESS')
                ],
                'to' => [
                    ['email' => $toEmail]
                ],
                'subject'     => $subject,
                'htmlContent' => $content, // Brevo uses 'htmlContent' specifically
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('Brevo API Failure: ' . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error('Brevo Connection Error: ' . $e->getMessage());
            return false;
        }
    }
}
