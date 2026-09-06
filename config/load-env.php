<?php
/**
 * Load project-root .env into putenv / $_ENV (once per request).
 */
$root = dirname(__DIR__);
$envFile = $root . '/.env';

if (empty($GLOBALS['__bao_env_loaded']) && is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\"'");
        if ($key !== '' && getenv($key) === false) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
    $GLOBALS['__bao_env_loaded'] = true;
}

if (!function_exists('bao_env')) {
    function bao_env(string $key, mixed $default = null): mixed
    {
        $v = $_ENV[$key] ?? getenv($key);
        return ($v === false || $v === null || $v === '') ? $default : $v;
    }
}
