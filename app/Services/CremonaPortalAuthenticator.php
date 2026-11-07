<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;

final class CremonaPortalAuthenticator
{
    /** @return array<string, mixed>|null */
    public function authenticate(User $user): ?array
    {
        $clientId = (string) config('cremona-portal.client_id');
        $secret = (string) config('cremona-portal.shared_secret');
        $email = strtolower(trim($user->email));
        $subject = (string) $user->getAuthIdentifier();
        $timestamp = time();

        if (! config('cremona-portal.enabled') || blank($clientId) || blank($secret)) {
            return ['navigation' => []];
        }

        $response = Http::acceptJson()->timeout(10)->post(config('cremona-portal.issuer').'/api/v1/site-portal/authenticate', [
            'client_id' => $clientId,
            'subject' => $subject,
            'email' => $email,
            'timestamp' => $timestamp,
            'signature' => hash_hmac('sha256', $clientId.'|'.$subject.'|'.$email.'|'.$timestamp, $secret),
        ]);

        $identity = $response->successful() ? $response->json() : null;

        return is_array($identity) && (string) ($identity['email'] ?? '') === $email && is_array($identity['navigation'] ?? null) ? $identity : null;
    }

    public function sessionLink(User $user, string $item): ?string
    {
        $clientId = (string) config('cremona-portal.client_id');
        $secret = (string) config('cremona-portal.shared_secret');
        $email = strtolower(trim($user->email));
        $subject = (string) $user->getAuthIdentifier();
        $timestamp = time();

        if (! config('cremona-portal.enabled') || blank($clientId) || blank($secret)) {
            return null;
        }

        $response = Http::acceptJson()->timeout(10)->post(config('cremona-portal.issuer').'/api/v1/site-portal/session-link', [
            'client_id' => $clientId, 'subject' => $subject, 'email' => $email, 'item' => $item, 'timestamp' => $timestamp,
            'signature' => hash_hmac('sha256', implode('|', [$clientId, $subject, $email, $item, $timestamp]), $secret),
        ]);

        return $response->successful() && is_string($response->json('entry_url')) ? $response->json('entry_url') : null;
    }
}
