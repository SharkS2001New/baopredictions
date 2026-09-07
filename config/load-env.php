<?php
/**
 * Load project-root .env into putenv / $_ENV (once per request).
 *
 * Local/dev: reads `.env` for keys not already in the process environment.
 * Docker/k8s: set BAO_LOAD_DOTENV=0 (image default) and inject secrets via
 * Deployment envFrom / extraEnv — never bake `.env` into the image.
 */
$root = dirname(__DIR__);
$envFile = $root . '/.env';

$loadDotenv = true;
$flag = $_ENV['BAO_LOAD_DOTENV'] ?? getenv('BAO_LOAD_DOTENV');
if ($flag !== false && $flag !== null && $flag !== '') {
    $loadDotenv = !in_array(strtolower((string) $flag), ['0', 'false', 'no', 'off'], true);
}

if ($loadDotenv && empty($GLOBALS['__bao_env_loaded']) && is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\"'");
        // Never override real process env (k8s / docker -e).
        if ($key !== '' && getenv($key) === false) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
    $GLOBALS['__bao_env_loaded'] = true;
} else {
    $GLOBALS['__bao_env_loaded'] = true;
}

if (!function_exists('bao_env')) {
    function bao_env(string $key, mixed $default = null): mixed
    {
        $v = $_ENV[$key] ?? getenv($key);
        return ($v === false || $v === null || $v === '') ? $default : $v;
    }
}
