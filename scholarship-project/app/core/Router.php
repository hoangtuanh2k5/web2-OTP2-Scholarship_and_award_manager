<?php
/**
 * Simple front-controller Router.
 * URL format: index.php?url=controller/method/param1/param2
 */
class Router
{
    public function dispatch(): void
    {
        $url = $_GET['url'] ?? 'auth/login';
        $url = trim($url, '/');
        $parts = explode('/', $url);

        $controllerName = ucfirst(strtolower($parts[0] ?? 'auth')) . 'Controller';
        $method         = strtolower($parts[1] ?? 'index');
        $params         = array_slice($parts, 2);

        $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';

        if (!file_exists($controllerFile)) {
            $this->notFound();
            return;
        }

        require_once $controllerFile;

        if (!class_exists($controllerName)) {
            $this->notFound();
            return;
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $method)) {
            $this->notFound();
            return;
        }

        call_user_func_array([$controller, $method], $params);
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo '<h1>404 – Trang không tồn tại</h1>';
        echo '<a href="' . base_url() . '">Về trang chủ</a>';
    }
}
