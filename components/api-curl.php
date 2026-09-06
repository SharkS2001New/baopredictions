<?php
/**
 * Server-side page data helpers.
 * HTML pages load tips in-process via PageApiService / StatsService (not browser JS).
 * Public JSON routes under /api/* remain available separately.
 */
require_once __DIR__ . '/match-cards.php';
require_once dirname(__DIR__) . '/src/Api/bootstrap.php';

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

    try {
        if ($path === 'stats') {
            $stats = new \App\Services\StatsService();
            return $stats->payload();
        }

        $api = new \App\Services\PageApiService();
        if (!$api->hasPage($path)) {
            return null;
        }
        return $api->payload($path);
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
 * Cached stats for the current request (hero, track strip, sidebar).
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
