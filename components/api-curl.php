<?php
/**
 * Server-side page data helpers.
 * Caching mirrors pitchpredictionsbackend: Cache::get/put/remember via Predis
 * (CACHE_DRIVER=redis, REDIS_CACHE_DB) with file fallback — same k3s Redis service.
 */
require_once __DIR__ . '/match-cards.php';
require_once dirname(__DIR__) . '/src/Api/bootstrap.php';

use App\Support\Cache;
use App\Support\DateTimeHelper;

/**
 * Load a page API payload in-process, e.g. /api/1x2-predictions or homepage.
 *
 * @return array<string,mixed>|null
 */
function bao_curl_api(string $apiPath): ?array
{
    $path = '/' . ltrim(parse_url($apiPath, PHP_URL_PATH) ?: $apiPath, '/');
    $path = preg_replace('#^/api/#', '', $path) ?? '';
    $path = trim((string) $path, '/');

    if ($path === '' || $path === 'health' || $path === 'pages' || $path === 'games') {
        return null;
    }

    $day = DateTimeHelper::siteToday();
    // Laravel-style versioned JSON keys + date-based TTL (FixtureApiHelpers pattern).
    $cacheKey = 'bao_api_' . str_replace('-', '_', $path) . '_' . $day;
    if ($path === 'stats') {
        $ttl = Cache::ttlStats();
    } elseif (str_contains($path, 'live')) {
        $ttl = Cache::ttlLive();
    } else {
        $ttl = Cache::ttlForSiteDate($day);
    }

    try {
        $cached = Cache::getCachedJsonPayload($cacheKey);
        if (is_array($cached) && ($cached['ok'] ?? false) === true) {
            return $cached;
        }

        if ($path === 'stats') {
            $payload = (new \App\Services\StatsService())->payload();
        } else {
            $api = new \App\Services\PageApiService();
            if (!$api->hasPage($path)) {
                return null;
            }
            $payload = $api->payload($path);
        }

        if (is_array($payload) && ($payload['ok'] ?? true)) {
            Cache::putCachedJsonPayload($cacheKey, $payload, $ttl);
        }

        return $payload;
    } catch (Throwable $e) {
        return null;
    }
}

function bao_api_empty_msg(string $label = 'fixtures'): string
{
    return '<p class="lede">No ' . htmlspecialchars($label) . ' available right now. Check back soon.</p>';
}

function bao_api_fail_msg(): string
{
    return '<p class="lede">Predictions temporarily unavailable.</p>';
}

/**
 * Request-local + Redis/file cached stats (hero, track strip, sidebar).
 *
 * @return array<string,mixed>|null
 */
function bao_api_stats(): ?array
{
    static $cached = false;
    static $payload = null;
    if ($cached) {
        return $payload;
    }
    $cached = true;
    $payload = bao_curl_api('/api/stats');
    return $payload;
}

function bao_fmt_pct(?float $value, int $decimals = 1): string
{
    if ($value === null) {
        return '—';
    }
    return number_format($value, $decimals) . '%';
}

function bao_fmt_roi(?float $value, int $decimals = 1): string
{
    if ($value === null) {
        return '—';
    }
    $sign = $value > 0 ? '+' : '';
    return $sign . number_format($value, $decimals) . '%';
}

function bao_fmt_units(?float $value, int $decimals = 1): string
{
    if ($value === null) {
        return '—';
    }
    $sign = $value > 0 ? '+' : '';
    return $sign . number_format($value, $decimals);
}
