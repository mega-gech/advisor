<?php


class AuthController extends BaseController {
    private AuthService $auth;

    public function __construct() {
        parent::__construct();
        $this->auth = new AuthService($this->db);
    }

    public function register(): void {
        if (!$this->requirePost()) {
            return;
        }

        $result = $this->auth->registerStudent([
            'name' => $this->post('name'),
            'email' => $this->post('email'),
            'password' => $this->post('password'),
            'student_number' => $this->post('student_number'),
        ]);

        if ($result['ok']) {
            flash_success($result['message']);
            redirect('login');
        }

        $_SESSION['register_old'] = [
            'name' => trim($this->post('name')),
            'email' => trim($this->post('email')),
            'student_number' => trim($this->post('student_number')),
        ];
        flash_error($result['error']);
        redirect('register');
    }

    public function login(): void {
        if (!$this->requirePost()) {
            return;
        }

        $result = $this->auth->login(
            $this->post('email'),
            $this->post('password'),
            !empty($this->post('remember'))
        );

        if (!$result['ok']) {
            flash_error($result['error']);
            redirect('login');
        }

        redirect(AuthService::dashboardActionForRole($result['role']));
    }

    public function logout(): void {
        AuthService::logout();
    }
}
