<?php

class Router {
    private array $getRoutes = [];
    private array $postRoutes = [];

    public function get(string $action, callable $handler, ?string $role = null): void {
        $this->getRoutes[$action] = ['handler' => $handler, 'role' => $role];
    }

    public function post(string $action, string $controller, string $method, ?string $role = null): void {
        $this->postRoutes[$action] = ['controller' => $controller, 'method' => $method, 'role' => $role];
    }

    public function dispatch(string $action): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($this->postRoutes[$action])) {
            $this->runPost($this->postRoutes[$action]);
            return;
        }

        if ($action === 'logout') {
            $this->runController('AuthController', 'logout');
            return;
        }

        if (!isset($this->getRoutes[$action])) {
            redirect('home');
            return;
        }

        $route = $this->getRoutes[$action];
        if ($route['role'] !== null) {
            if (!isset($_SESSION['user_id'])) {
                redirect('login');
            }
            if (($_SESSION['user_role'] ?? '') !== $route['role']) {
                redirect('login');
            }
        }

        if ($route['role'] === null && in_array($action, ['login', 'register'], true) && isset($_SESSION['user_id'])) {
            redirect('dashboard');
        }

        ($route['handler'])();
    }

    private function runPost(array $route): void {
        if ($route['role'] !== null) {
            require_role($route['role']);
        }
        $this->runController($route['controller'], $route['method']);
    }

    private function runController(string $class, string $method): void {
        if (!class_exists($class)) {
            flash_error('Invalid action.');
            redirect('home');
        }
        $controller = new $class();
        if (!method_exists($controller, $method)) {
            flash_error('Invalid action.');
            redirect('home');
        }
        $controller->$method();
    }
}
