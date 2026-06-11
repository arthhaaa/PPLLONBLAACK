<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class MailtrapEmailService
{
    public function isConfigured(): bool
    {
        return filled(config('services.mailtrap.api_token'));
    }

    /**
     * @throws RequestException
     */
    public function send(array $message): array
    {
        $payload = [
            'from' => [
                'email' => config('services.mailtrap.from_email'),
                'name' => config('services.mailtrap.from_name'),
            ],
            'to' => $message['to'],
            'subject' => $message['subject'],
            'text' => $message['text'] ?? null,
            'html' => $message['html'] ?? null,
        ];

        return Http::withToken(config('services.mailtrap.api_token'))
            ->acceptJson()
            ->asJson()
            ->post(rtrim(config('services.mailtrap.base_url'), '/') . '/api/send', array_filter($payload))
            ->throw()
            ->json();
    }
}
