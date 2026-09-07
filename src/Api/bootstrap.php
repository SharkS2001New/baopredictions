<?php
/**
 * Shared API bootstrapping — lightweight; services load on demand via Composer PSR-4.
 */

$root = dirname(__DIR__, 2);
$autoload = $root . '/vendor/autoload.php';
if (is_file($autoload)) {
    require_once $autoload;
}

require_once dirname(__DIR__) . '/Support/log.php';
require_once __DIR__ . '/helpers.php';
require_once dirname(__DIR__) . '/Database.php';
require_once dirname(__DIR__) . '/Support/DateTimeHelper.php';
require_once dirname(__DIR__) . '/Support/Cache.php';

// Surface fatals / uncaught exceptions to container stderr.
set_exception_handler(static function (Throwable $e): void {
    bao_log_exception($e, 'Uncaught exception');
    if (!headers_sent()) {
        http_response_code(500);
        $isApi = str_starts_with((string) (parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: ''), '/api/');
        if ($isApi) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['ok' => false, 'error' => 'Internal server error'], JSON_UNESCAPED_SLASHES);
            return;
        }
        header('Content-Type: text/plain; charset=utf-8');
    }
    echo 'Internal server error';
});

set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
    // Let @-suppressed errors alone
    if (!(error_reporting() & $severity)) {
        return false;
    }
    $map = [
        E_ERROR => 'error',
        E_WARNING => 'warning',
        E_PARSE => 'critical',
        E_NOTICE => 'debug',
        E_CORE_ERROR => 'critical',
        E_CORE_WARNING => 'warning',
        E_COMPILE_ERROR => 'critical',
        E_COMPILE_WARNING => 'warning',
        E_USER_ERROR => 'error',
        E_USER_WARNING => 'warning',
        E_USER_NOTICE => 'debug',
        E_RECOVERABLE_ERROR => 'error',
        E_DEPRECATED => 'debug',
        E_USER_DEPRECATED => 'debug',
    ];
    $level = $map[$severity] ?? 'warning';
    bao_log($level, $message, ['file' => $file . ':' . $line, 'errno' => $severity]);
    // false → also run PHP's internal handler (still respects log_errors)
    return false;
});
