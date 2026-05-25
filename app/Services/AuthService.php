<?php


class AuthService {
    private User $user;

    public function __construct(PDO $db) {
        $this->user = new User($db);
    }

    public function registerStudent(array $input): array {
        if (strlen($input['password'] ?? '') < 8) {
            return ['ok' => false, 'error' => 'Password must be at least 8 characters.'];
        }

        $email = strtolower(trim($input['email'] ?? ''));
        if (!str_ends_with($email, '@aau.edu.et')) {
            return ['ok' => false, 'error' => 'Only official university email addresses (@aau.edu.et) are allowed.'];
        }

        $this->user->name = trim($input['name'] ?? '');
        $this->user->email = $email;
        $this->user->password = $input['password'];
        $this->user->student_number = trim($input['student_number'] ?? '');
        $this->user->role = 'student';
        $this->user->status = 'pending';

        if ($this->user->emailExists()) {
            return ['ok' => false, 'error' => 'Email already exists.'];
        }

        if (!$this->user->register()) {
            return ['ok' => false, 'error' => 'Something went wrong during registration.'];
        }

        return ['ok' => true, 'message' => 'Registration submitted. Sign in after the registrar approves your account.'];
    }

    public function login(string $email, string $password, bool $remember): array {
        $this->user->email = trim($email);
        $this->user->password = $password;

        if (!$this->user->login()) {
            return ['ok' => false, 'error' => 'Invalid email or password.'];
        }

        if ($this->user->role === 'student') {
            if ($this->user->status === 'pending') {
                return ['ok' => false, 'error' => 'Your account is pending approval by the registrar.'];
            }
            if ($this->user->status === 'rejected') {
                return ['ok' => false, 'error' => 'Your registration was rejected. Please contact the registrar.'];
            }
        }

        $_SESSION['user_id'] = $this->user->id;
        $_SESSION['user_name'] = $this->user->name;
        $_SESSION['user_role'] = $this->user->role;
        $_SESSION['user_email'] = $this->user->email;

        if ($remember) {
            setcookie('advisorhub_email', $this->user->email, time() + 60 * 60 * 24 * 30, '/');
        } else {
            setcookie('advisorhub_email', '', time() - 3600, '/');
        }

        return ['ok' => true, 'role' => $this->user->role];
    }

    public static function logout(): void {
        session_unset();
        session_destroy();
        redirect('login');
    }

    public static function dashboardActionForRole(string $role): string {
        return match ($role) {
            'student' => 'student_dashboard',
            'advisor' => 'advisor_dashboard',
            'registrar' => 'registrar_dashboard',
            default => 'home',
        };
    }
}
