<?php

class HomeController extends BaseController {
    public function index(): void {
        $userModel = new User($this->db);
        $messageModel = new Message($this->db);
        $appointmentModel = new Appointment($this->db);

        $stats = [
            'students' => $userModel->countByRoleAndStatus('student', 'approved'),
            'advisors' => $userModel->countByRole('advisor'),
            'messages' => $messageModel->countAll(),
            'appointments' => $appointmentModel->countAll(),
        ];

        $this->render(VIEWS_PATH . '/landing.php', ['stats' => $stats]);
    }
}
