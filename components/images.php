<?php
/**
 * Image URL helpers — optional Cloudflare Image Resizing for crests.
 *
 * Enable in env once the zone has Image Resizing (or Cloudflare Images):
 *   BAO_CF_IMAGE_RESIZE=1
 *   BAO_CF_IMAGE_ZONE=https://www.baopredictions.com
 *
 * Crests stay at their source URL until that flag is on; then they are
 * rewritten through /cdn-cgi/image/… so CF serves a small WebP/AVIF.
 */

if (!function_exists('bao_img_url')) {
    /**
     * @param string $url Absolute or site-relative image URL
     * @param int $width Target CSS pixel width (2× for retina is applied in opts)
     * @param array{quality?: int, fit?: string} $opts
     */
    function bao_img_url(string $url, int $width = 64, array $opts = []): string
    {
        $url = trim($url);
        if ($url === '') {
            return '';
        }
        if (str_contains($url, '/cdn-cgi/image/')) {
            return $url;
        }

        $enabled = false;
        if (function_exists('bao_env')) {
            $flag = bao_env('BAO_CF_IMAGE_RESIZE', '');
            $enabled = $flag === true || $flag === 1 || $flag === '1' || strtolower((string) $flag) === 'true';
        }
        if (!$enabled) {
            return $url;
        }

        // Absolute-ize relative site assets.
        if (str_starts_with($url, '/')) {
            $app = 'https://www.baopredictions.com';
            if (function_exists('bao_env')) {
                $app = rtrim((string) bao_env('APP_URL', $app), '/');
            }
            $url = $app . $url;
        }

        $zone = 'https://www.baopredictions.com';
        if (function_exists('bao_env')) {
            $zone = rtrim((string) bao_env('BAO_CF_IMAGE_ZONE', bao_env('APP_URL', $zone)), '/');
        }
        $quality = max(40, min(90, (int) ($opts['quality'] ?? 72)));
        $fit = (string) ($opts['fit'] ?? 'scale-down');
        $w = max(16, min(1200, $width));

        return $zone . '/cdn-cgi/image/width=' . $w
            . ',quality=' . $quality
            . ',fit=' . rawurlencode($fit)
            . ',format=auto/'
            . $url;
    }
}
