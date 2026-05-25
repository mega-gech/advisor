<?php

abstract class BaseController {
    protected PDO $db;
    protected ?string $requiredRole = null;

    public function __construct() {
        $this->db = Database::getInstance()->connect();
        if ($this->requiredRole !== null) {
            require_role($this->requiredRole);
        }
    }

    protected function requirePost(): bool {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function post(string $key, $default = '') {
        return $_POST[$key] ?? $default;
    }

    protected function render(string $viewPath, array $data = []): void {
        extract($data, EXTR_SKIP);
        include $viewPath;
    }

    protected function dashboardView(string $role, array $data): void {
        $view = VIEWS_PATH . '/dashboards/' . $role . '.php';
        if (!is_file($view)) {
            flash_error('Dashboard not found.');
            redirect('login');
        }
        $this->render($view, $data);
    }
}
