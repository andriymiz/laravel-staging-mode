<?php

namespace StagingMode\Http;

use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Cookie;

class StagingModeBypassCookie
{
    /**
     * Create a new staging mode bypass cookie.
     */
    public static function create(string $key): Cookie
    {
        $cookieName = config('staging-mode.cookie_name');
        $cookieExpiration = config('staging-mode.cookie_expiration');
        $expiresAt = Carbon::now()->addHours($cookieExpiration);

        return new Cookie($cookieName, base64_encode(json_encode([
            'expires_at' => $expiresAt->getTimestamp(),
            'mac' => hash_hmac('sha256', $expiresAt->getTimestamp(), $key),
        ])), $expiresAt, config('session.path'), config('session.domain'));
    }

    /**
     * Determine if the given staging mode bypass cookie is valid.
     */
    public static function isValid(string $cookie, string $key): bool
    {
        $payload = json_decode(base64_decode($cookie), true);

        return is_array($payload) &&
            is_numeric($payload['expires_at'] ?? null) &&
            isset($payload['mac']) &&
            hash_equals(hash_hmac('sha256', $payload['expires_at'], $key), $payload['mac']) &&
            (int) $payload['expires_at'] >= Carbon::now()->getTimestamp();
    }
}
