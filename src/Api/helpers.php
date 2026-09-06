<?php
/**
 * JSON API helpers for Bao Predictions.
 */

function bao_api_json(array $payload, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function bao_api_error(string $message, int $status = 500, array $extra = []): void
{
    bao_api_json(array_merge([
        'ok' => false,
        'error' => $message,
    ], $extra), $status);
}
