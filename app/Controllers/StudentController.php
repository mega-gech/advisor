<?php


class StudentController extends BaseController {
    protected ?string $requiredRole = 'student';

    private Assignment $assignment;
    private Message $messageModel;
    private Appointment $appointmentModel;

    public function __construct() {
        parent::__construct();
        $this->assignment = new Assignment($this->db);
        $this->messageModel = new Message($this->db);
        $this->appointmentModel = new Appointment($this->db);
    }

    public function dashboard(): void {
        $data = $this->getDashboardData();
        $data['portalBadges'] = [
            'messages' => (int) $data['unread_messages'],
            'notifications' => count($data['notifications']),
        ];
        $this->dashboardView('student', $data);
    }

    public function getDashboardData(): array {
        $studentId = (int) $_SESSION['user_id'];
        $section = portal_section();
        if (in_array($section, ['messages', 'notifications'], true)) {
            $this->messageModel->markAllAsReadForUser($studentId);
        }
        $search = trim($_GET['search'] ?? '');
        $messages = $this->messageModel->getMessagesForUser($studentId, 'student')->fetchAll(PDO::FETCH_ASSOC);
        $allAppointments = $this->appointmentModel->getAllForStudent($studentId);

        return [
            'advisor' => $this->assignment->getAssignedAdvisor($studentId) ?: null,
            'messages' => filter_messages_by_search($messages, $search),
            'appointments' => $this->appointmentModel->getUpcomingForStudent($studentId, 3),
            'all_appointments' => $allAppointments,
            'unread_messages' => $this->messageModel->countUnreadForUser($studentId, 'student'),
            'upcoming_appointments' => $this->appointmentModel->countUpcomingForStudent($studentId),
            'notifications' => build_portal_notifications($messages, $allAppointments, 'student'),
            'profile' => [
                'name' => $_SESSION['user_name'] ?? '',
                'email' => $_SESSION['user_email'] ?? '',
                'role' => 'student',
            ],
            'search' => $search,
            'section' => portal_section(),
        ];
    }

    public function sendMessage(): void {
        if (!$this->requirePost()) {
            return;
        }

        $advisor = $this->assignment->getAssignedAdvisor((int) $_SESSION['user_id']);
        if (!$advisor) {
            flash_error('You do not have an assigned advisor yet.');
            redirect('student_dashboard');
        }

        $title = trim($this->post('title'));
        $body = trim($this->post('message'));

        if ($title === '' || $body === '') {
            flash_error('Please enter a subject and message.');
            redirect('student_dashboard', ['section' => 'messages']);
        }

        if ($this->messageModel->sendDirectMessage((int) $_SESSION['user_id'], (int) $advisor['advisor_id'], $title, $body)) {
            flash_success('Message sent to your advisor.');
        } else {
            flash_error('Failed to send message.');
        }
        redirect('student_dashboard', ['section' => 'messages']);
    }

    public function requestAppointment(): void {
        if (!$this->requirePost()) {
            return;
        }

        $advisor = $this->assignment->getAssignedAdvisor((int) $_SESSION['user_id']);
        if (!$advisor) {
            flash_error('You need an assigned advisor before booking an appointment.');
            redirect('student_dashboard');
        }

        $date = $this->post('appointment_date');
        if ($date === '') {
            flash_error('Please choose a date and time.');
            redirect('student_dashboard', ['section' => 'appointments']);
        }

        $this->appointmentModel->student_id = (int) $_SESSION['user_id'];
        $this->appointmentModel->advisor_id = (int) $advisor['advisor_id'];
        $this->appointmentModel->appointment_date = Appointment::formatDateTimeInput($date);
        $this->appointmentModel->notes = trim($this->post('notes'));

        if ($this->appointmentModel->create()) {
            flash_success('Appointment request submitted. Your advisor will confirm soon.');
        } else {
            flash_error('Could not submit appointment request.');
        }
        redirect('student_dashboard', ['section' => 'appointments']);
    }
}
