<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap/app.php';

$action = $_GET['action'] ?? 'home';
$router = new Router();

$router->post('login', 'AuthController', 'login');
$router->post('register', 'AuthController', 'register');
$router->post('logout', 'AuthController', 'logout');
$router->post('assign_student', 'RegistrarController', 'assignStudent', 'registrar');
$router->post('delete_user', 'RegistrarController', 'deleteUser', 'registrar');
$router->post('approve_student', 'RegistrarController', 'approveStudent', 'registrar');
$router->post('reject_student', 'RegistrarController', 'rejectStudent', 'registrar');
$router->post('create_advisor', 'RegistrarController', 'createAdvisor', 'registrar');
$router->post('send_broadcast', 'RegistrarController', 'sendBroadcast', 'registrar');
$router->post('advisor_send_message', 'AdvisorController', 'sendMessage', 'advisor');
$router->post('advisor_update_appointment', 'AdvisorController', 'updateAppointment', 'advisor');
$router->post('student_send_message', 'StudentController', 'sendMessage', 'student');
$router->post('student_request_appointment', 'StudentController', 'requestAppointment', 'student');

$router->get('login', fn() => include VIEWS_PATH . '/auth/login.php');
$router->get('register', fn() => include VIEWS_PATH . '/auth/register.php');
$router->get('home', fn() => (new HomeController())->index());

$router->get('student_dashboard', fn() => (new StudentController())->dashboard(), 'student');
$router->get('advisor_dashboard', fn() => (new AdvisorController())->dashboard(), 'advisor');
$router->get('registrar_dashboard', fn() => (new RegistrarController())->dashboard(), 'registrar');

$router->get('dashboard', function () {
    if (!isset($_SESSION['user_id'])) {
        redirect('login');
    }
    redirect(AuthService::dashboardActionForRole($_SESSION['user_role']));
});

$router->dispatch($action);
