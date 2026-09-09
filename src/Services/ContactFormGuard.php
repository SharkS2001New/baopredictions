<?php

namespace App\Services;

use App\Support\Cache;

/**
 * Contact form anti-spam helpers (honeypot + simple IP rate limit).
 */
final class ContactFormGuard
{
    public function isHoneypotTripped(array $post): bool
    {
        $honey = trim((string) ($post['company_website'] ?? ''));

        return $honey !== '';
    }

    /**
     * Reject forms submitted too quickly after page load (bots).
     */
    public function isTooFast(array $post): bool
    {
        $started = (int) ($post['form_started_at'] ?? 0);
        if ($started <= 0) {
            return false;
        }

        return (time() - $started) < 2;
    }

    public function allowRequest(?string $ip = null): bool
    {
        $ip = $ip ?: $this->clientIp();
        $key = 'bao_contact_rate_' . md5($ip);
        $hits = (int) Cache::get($key, 0);
        if ($hits >= 5) {
            return false;
        }
        Cache::put($key, $hits + 1, 15 * 60);

        return true;
    }

    public function clientIp(): string
    {
        $ip = (string) ($_SERVER['HTTP_CF_CONNECTING_IP']
            ?? $_SERVER['HTTP_X_FORWARDED_FOR']
            ?? $_SERVER['REMOTE_ADDR']
            ?? 'unknown');
        if (str_contains($ip, ',')) {
            $ip = trim(explode(',', $ip)[0]);
        }

        return substr(preg_replace('/[^a-zA-Z0-9\.:_-]/', '', $ip) ?? 'unknown', 0, 64);
    }
}
