<?php
namespace App\Facades;

class Router {
    private $routes = [];

    public function get($path, $callback) {
        $this->addRoute('GET', $path, $callback);
    }

    public function post($path, $callback) {
        $this->addRoute('POST', $path, $callback);
    }

    public function put($path, $callback) {
        $this->addRoute('PUT', $path, $callback);
    }

    private function addRoute($method, $path, $callback) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback,
        ];
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Crawlers often send HEAD; treat it like GET for read routes.
        $matchMethod = ($method === 'HEAD') ? 'GET' : $method;

        foreach ($this->routes as $route) {
            if ($matchMethod === $route['method'] && $this->matchPath($route['path'], $requestUri, $params)) {
                try {
                    if ($method === 'HEAD') {
                        // Run handler only if needed for headers; prefer empty body.
                        ob_start();
                        call_user_func_array($route['callback'], $params);
                        ob_end_clean();
                        return;
                    }
                    call_user_func_array($route['callback'], $params);
                } catch (\Throwable $e) {
                    if (function_exists('bao_log_exception')) {
                        bao_log_exception($e, 'Route handler failed', [
                            'route' => $route['path'],
                            'method' => $method,
                        ]);
                    } else {
                        error_log('[bao] Route handler failed: ' . $e->getMessage());
                    }
                    if (!headers_sent()) {
                        http_response_code(500);
                        $isApi = str_starts_with((string) $requestUri, '/api/');
                        if ($isApi) {
                            header('Content-Type: application/json; charset=utf-8');
                            echo json_encode(['ok' => false, 'error' => 'Internal server error']);
                            return;
                        }
                    }
                    echo 'Internal server error';
                }
                return;
            }
        }

        http_response_code(404);
        if ($method === 'HEAD') {
            return;
        }
        $this->show404Page();
    }

    private function matchPath($routePath, $requestUri, &$params) {
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $routePath);
        $pattern = "@^" . $pattern . "/?$@";

        if (preg_match($pattern, $requestUri, $matches)) {
            array_shift($matches);
            $params = $matches;
            return true;
        }

        return false;
    }

    private function show404Page() {
        $errorPagePath = __DIR__ . '/../../pages/404.php';
        if (file_exists($errorPagePath)) {
            include $errorPagePath;
        } else {
            echo '404 Page Not Found';
        }
    }
}
