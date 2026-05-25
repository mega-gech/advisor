<?php


class RegistrarController extends BaseController {
    protected ?string $requiredRole = 'registrar';

    private Assignment $assignment;
    private User $userModel;
    private Message $messageModel;

    public function __construct() {
        parent::__construct();
        $this->assignment = new Assignment($this->db);
        $this->userModel = new User($this->db);
        $this->messageModel = new Message($this->db);
    }

    public function dashboard(): void {
        $data = $this->getDashboardData();
        $pendingCount = (int) $data['metrics']['pending_approvals'];
        $data['portalBadges'] = [
            'approvals' => $pendingCount,
            'notifications' => count($data['notifications']),
        ];
        $this->dashboardView('registrar', $data);
    }

    public function assignStudent(): void {
        if (!$this->requirePost()) {
            return;
        }

        $this->assignment->student_id = (int) $this->post('student_id');
        $this->assignment->advisor_id = (int) $this->post('advisor_id');
        $this->assignment->assigned_by = (int) $_SESSION['user_id'];

        if ($this->assignment->student_id && $this->assignment->advisor_id && $this->assignment->assignStudent()) {
            flash_success('Student successfully assigned to advisor.');
        } else {
            flash_error('Failed to assign student. Select both student and advisor.');
        }
        redirect('registrar_dashboard', ['section' => 'assignments']);
    }

    public function deleteUser(): void {
        if (!$this->requirePost()) {
            return;
        }

        $userId = (int) $this->post('user_id');
        if ($userId === (int) $_SESSION['user_id']) {
            flash_error('You cannot delete your own account.');
            redirect('registrar_dashboard', ['section' => 'users']);
        }

        if ($this->userModel->deleteUser($userId)) {
            flash_success('User successfully deleted.');
        } else {
            flash_error('Failed to delete user.');
        }
        redirect('registrar_dashboard', ['section' => 'users']);
    }

    public function approveStudent(): void {
        if (!$this->requirePost()) {
            return;
        }

        $studentId = (int) $this->post('student_id');
        $student = $this->userModel->getUserById($studentId);

        if ($student && $this->userModel->approveStudent($studentId)) {
            @mail($student['email'], 'AdvisorHub Account Approved', 'Your AdvisorHub student account has been approved. You may now log in.');
            flash_success('Student approved successfully.');
        } else {
            flash_error('Failed to approve student.');
        }
        redirect('registrar_dashboard', ['section' => 'approvals']);
    }

    public function rejectStudent(): void {
        if (!$this->requirePost()) {
            return;
        }

        $studentId = (int) $this->post('student_id');
        $student = $this->userModel->getUserById($studentId);

        if ($student && $this->userModel->rejectStudent($studentId)) {
            @mail($student['email'], 'AdvisorHub Account Rejected', 'Your AdvisorHub registration was not approved. Please contact the registrar.');
            flash_success('Student rejected successfully.');
        } else {
            flash_error('Failed to reject student.');
        }
        redirect('registrar_dashboard', ['section' => 'approvals']);
    }

    public function createAdvisor(): void {
        if (!$this->requirePost()) {
            return;
        }

        $email = strtolower(trim($this->post('email')));
        if (!str_ends_with($email, '@aau.edu.et')) {
            flash_error('Advisor email must be an @aau.edu.et address.');
            redirect('registrar_dashboard', ['section' => 'dashboard']);
        }

        $this->userModel->name = trim($this->post('name'));
        $this->userModel->email = $email;
        $this->userModel->password = $this->post('password');

        if (strlen($this->userModel->password) < 8) {
            flash_error('Password must be at least 8 characters.');
            redirect('registrar_dashboard', ['section' => 'dashboard']);
        }

        if ($this->userModel->emailExists()) {
            flash_error('Email already exists for advisor.');
        } elseif ($this->userModel->createAdvisor()) {
            @mail($this->userModel->email, 'AdvisorHub Account Created', 'Your AdvisorHub advisor account has been created. Please log in with the temporary password and change it.');
            flash_success('Advisor account created successfully.');
        } else {
            flash_error('Failed to create advisor.');
        }
        redirect('registrar_dashboard', ['section' => 'dashboard']);
    }

    public function sendBroadcast(): void {
        if (!$this->requirePost()) {
            return;
        }

        $title = trim($this->post('title'));
        $body = trim($this->post('message'));
        if ($title === '' || $body === '') {
            flash_error('Title and message are required.');
            redirect('registrar_dashboard', ['section' => 'settings']);
        }

        $this->messageModel->sender_id = (int) $_SESSION['user_id'];
        $this->messageModel->title = $title;
        $this->messageModel->message = $body;
        $this->messageModel->message_type = 'broadcast';
        $this->messageModel->audience_type = 'advisor';
        $this->messageModel->receiver_id = null;

        if ($this->messageModel->sendMessage()) {
            flash_success('Broadcast sent to all advisors.');
        } else {
            flash_error('Failed to send broadcast.');
        }
        redirect('registrar_dashboard', ['section' => 'settings']);
    }

    public function getDashboardData(): array {
        $search = trim($_GET['search'] ?? '');
        $roleFilter = $_GET['role'] ?? '';

        $pending = $this->userModel->getPendingStudents();
        $assignments = $this->assignment->getAllAssignments()->fetchAll(PDO::FETCH_ASSOC);

        $totalStudents = $this->userModel->countByRole('student');
        $totalAdvisors = $this->userModel->countByRole('advisor');
        $totalRegistrars = $this->userModel->countByRole('registrar');

        $approvedStudentCount = count($this->userModel->getApprovedStudents());
        $rejectedCount = $this->userModel->countByRoleAndStatus('student', 'rejected');

        return [
            'students' => $this->userModel->getApprovedStudents(),
            'pending_students' => $pending,
            'advisors' => $this->userModel->getAdvisorList(),
            'assignments' => $assignments,
            'users' => $this->userModel->getAllUsers($search, $roleFilter)->fetchAll(PDO::FETCH_ASSOC),
            'metrics' => [
                'total_students' => $totalStudents,
                'total_advisors' => $totalAdvisors,
                'pending_approvals' => count($pending),
                'approved_students' => $approvedStudentCount,
                'rejected_students' => $rejectedCount,
                'total_assignments' => count($assignments),
                'total_users' => $totalStudents + $totalAdvisors + $totalRegistrars,
                'total_registrars' => $totalRegistrars,
            ],
            'notifications' => $this->buildNotifications($pending, $assignments),
            'search' => $search,
            'role_filter' => $roleFilter,
            'section' => ($s = trim($_GET['section'] ?? '')) !== '' ? $s : 'dashboard',
            'registrar_profile' => [
                'name' => $_SESSION['user_name'] ?? '',
                'email' => $_SESSION['user_email'] ?? '',
            ],
        ];
    }

    private function buildNotifications(array $pending, array $assignments): array {
        $items = [];

        foreach ($pending as $student) {
            $items[] = [
                'type' => 'approval',
                'title' => 'Pending student approval',
                'body' => $student['name'] . ' (' . $student['email'] . ') is awaiting review.',
                'time' => $student['created_at'] ?? date('Y-m-d H:i:s'),
                'link_section' => 'approvals',
            ];
        }

        foreach (array_slice($assignments, 0, 8) as $assignment) {
            $items[] = [
                'type' => 'assignment',
                'title' => 'Advisor assignment',
                'body' => $assignment['student_name'] . ' assigned to ' . $assignment['advisor_name'],
                'time' => $assignment['assigned_at'],
                'link_section' => 'assignments',
            ];
        }

        usort($items, static fn ($a, $b) => strtotime($b['time']) <=> strtotime($a['time']));
        return $items;
    }
}
