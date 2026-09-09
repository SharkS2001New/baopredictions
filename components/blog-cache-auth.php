<?php
/**
 * Shared blog / footer clear-key auth (must match admin BLOG_CACHE_CLEAR_KEY).
 */

const BAO_BLOG_CACHE_CLEAR_KEY_LENGTH = 24;

function bao_blog_cache_clear_key(): ?string
{
    $candidates = [
        bao_env('BLOG_CACHE_CLEAR_KEY'),
        bao_env('CACHE_CLEAR_KEY'),
    ];
    foreach ($candidates as $candidate) {
        $secret = trim((string) $candidate);
        if (strlen($secret) === BAO_BLOG_CACHE_CLEAR_KEY_LENGTH) {
            return $secret;
        }
    }

    return null;
}

function bao_blog_cache_clear_authorized(): bool
{
    $secret = bao_blog_cache_clear_key();
    if ($secret === null) {
        return false;
    }

    $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
    $token = '';
    if (is_string($auth) && preg_match('/^Bearer\s+(.+)$/i', trim($auth), $m)) {
        $token = trim($m[1]);
    }
    if ($token === '') {
        $token = trim((string) ($_SERVER['HTTP_X_FOOTER_CLEAR_KEY'] ?? ''));
    }
    if ($token === '') {
        $token = trim((string) ($_SERVER['HTTP_X_BLOG_CACHE_CLEAR_KEY'] ?? ''));
    }
    if ($token === '') {
        $token = trim((string) ($_GET['key'] ?? ''));
    }

    return $token !== '' && hash_equals($secret, $token);
}
