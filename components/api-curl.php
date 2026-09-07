<?php
/**
 * Server-side page data helpers.
 * Caching mirrors pitchpredictionsbackend FixtureApiHelpers:
 *   past → 12h | today → 10m | tomorrow → 1h | weekend → 5h | jackpot → 10m
 *   live → no cache (scores change; set CACHE_TTL_LIVE=60 to match pitch's 1m)
 */
require_once __DIR__ . '/match-cards.php';
require_once dirname(__DIR__) . '/src/Api/bootstrap.php';

use App\Support\Cache;
use App\Support\DateTimeHelper;

/**
 * Pitch-aligned cache key + TTL for an API page path (no /api/ prefix).
 *
 * @return array{key:string,ttl:int} ttl 0 = do not read/write cache
 */
function bao_api_cache_meta(string $path): array
{
    $today = DateTimeHelper::siteToday();
    $cacheKey = 'bao_api_' . str_replace('-', '_', $path) . '_' . $today;

    if ($path === 'stats') {
        return ['key' => $cacheKey, 'ttl' => Cache::ttlStats()];
    }

    $pages = require dirname(__DIR__) . '/config/api-pages.php';
    $def = is_array($pages[$path] ?? null) ? $pages[$path] : [];

    // Live board — never cache by default (pitch uses ~1m; we skip unless CACHE_TTL_LIVE>0).
    if (!empty($def['live_only']) || str_contains($path, 'live')) {
        return ['key' => $cacheKey, 'ttl' => Cache::ttlLive()];
    }

    if (($def['range'] ?? '') === 'weekend') {
        return ['key' => $cacheKey, 'ttl' => Cache::ttlWeekend()];
    }

    $source = strtolower((string) ($def['source'] ?? 'fixtures'));
    if ($source === 'selections' || $source === 'jackpot_hub' || str_contains($path, 'jackpot')) {
        return ['key' => $cacheKey, 'ttl' => Cache::ttlJackpot()];
    }

    // Resolve the page's calendar day (not always "today") — pitch fixtureCacheTtlForDate.
    $dayMod = strtolower((string) ($def['day'] ?? 'today'));
    if ($dayMod === '' || isset($def['date'])) {
        $fixtureDate = isset($def['date']) && is_string($def['date']) && $def['date'] !== ''
            ? $def['date']
            : $today;
    } else {
        $fixtureDate = DateTimeHelper::siteDate($dayMod);
    }

    return ['key' => $cacheKey, 'ttl' => Cache::ttlForSiteDate($fixtureDate)];
}

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

    $meta = bao_api_cache_meta($path);
    $ttl = (int) $meta['ttl'];
    $cacheKey = $meta['key'];

    try {
        if ($ttl > 0) {
            $cached = Cache::getCachedJsonPayload($cacheKey);
            if (is_array($cached) && ($cached['ok'] ?? false) === true) {
                return $cached;
            }
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

        if ($ttl > 0 && is_array($payload) && ($payload['ok'] ?? true)) {
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
