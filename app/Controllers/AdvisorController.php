<?php


class AdvisorController extends BaseController {
    protected ?string $requiredRole = 'advisor';

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
            'appointments' => (int) $data['pending_appointments'],
            'notifications' => count($data['notifications']),
        ];
        $this->dashboardView('advisor', $data);
    }

    public function getDashboardData(): array {
        $advisorId = (int) $_SESSION['user_id'];
        $search = trim($_GET['search'] ?? '');
        $students = $this->assignment->getAssignedStudents($advisorId);
        $messages = $this->messageModel->getMessagesForUser($advisorId, 'advisor')->fetchAll(PDO::FETCH_ASSOC);
        $allAppointments = $this->appointmentModel->getAllForAdvisor($advisorId);

        return [
            'students' => filter_students_by_search($students, $search),
            'messages' => filter_messages_by_search($messages, $search),
            'appointments' => $this->appointmentModel->getUpcomingForAdvisor($advisorId, 10),
            'all_appointments' => $allAppointments,
            'broadcasts' => array_values(array_filter($messages, static fn ($m) => ($m['message_type'] ?? '') === 'broadcast')),
            'unread_messages' => $this->messageModel->countUnreadForUser($advisorId, 'advisor'),
            'pending_appointments' => $this->appointmentModel->countPendingForAdvisor($advisorId),
            'student_count' => count($students),
            'notifications' => build_portal_notifications($messages, $allAppointments, 'advisor'),
            'profile' => [
                'name' => $_SESSION['user_name'] ?? '',
                'email' => $_SESSION['user_email'] ?? '',
                'role' => 'advisor',
            ],
            'search' => $search,
            'section' => portal_section(),
        ];
    }

    public function sendMessage(): void {
        if (!$this->requirePost()) {
            return;
        }

        $studentId = (int) $this->post('student_id');
        $title = trim($this->post('title'));
        $body = trim($this->post('message'));

        if (!$studentId || $title === '' || $body === '') {
            flash_error('Please fill in all message fields.');
            redirect('advisor_dashboard', ['section' => 'messages']);
        }

        if (!$this->assignment->isStudentAssignedToAdvisor($studentId, (int) $_SESSION['user_id'])) {
            flash_error('You can only message your assigned students.');
            redirect('advisor_dashboard', ['section' => 'messages']);
        }

        if ($this->messageModel->sendDirectMessage((int) $_SESSION['user_id'], $studentId, $title, $body)) {
            flash_success('Message sent to student.');
        } else {
            flash_error('Failed to send message.');
        }
        redirect('advisor_dashboard', ['section' => 'messages']);
    }

    public function updateAppointment(): void {
        if (!$this->requirePost()) {
            return;
        }

        $id = (int) $this->post('appointment_id');
        $status = $this->post('status');

        if (!in_array($status, ['approved', 'rejected'], true)) {
            flash_error('Invalid appointment status.');
            redirect('advisor_dashboard', ['section' => 'appointments']);
        }

        if ($this->appointmentModel->updateStatus($id, $status, (int) $_SESSION['user_id'])) {
            flash_success('Appointment ' . $status . '.');
        } else {
            flash_error('Could not update appointment.');
        }
        redirect('advisor_dashboard', ['section' => 'appointments']);
    }
}
