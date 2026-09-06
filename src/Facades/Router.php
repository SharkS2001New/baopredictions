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

        foreach ($this->routes as $route) {
            if ($method === $route['method'] && $this->matchPath($route['path'], $requestUri, $params)) {
                call_user_func_array($route['callback'], $params);
                return;
            }
        }

        http_response_code(404);
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
