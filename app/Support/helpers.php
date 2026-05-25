<?php

function base_path(): string {
    static $base = null;
    if ($base === null) {
        $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
        $base = rtrim(dirname($script), '/');
        if ($base === '/' || $base === '.') {
            $base = '';
        }
    }
    return $base;
}

function asset(string $path): string {
    $path = ltrim($path, '/');
    if (str_starts_with($path, 'public/')) {
        $path = substr($path, 7);
    }
    if (!str_starts_with($path, 'assets/')) {
        $path = 'assets/' . $path;
    }
    return base_path() . '/' . $path;
}

function asset_version(string $path): string {
    $relative = ltrim($path, '/');
    if (str_starts_with($relative, 'public/')) {
        $relative = substr($relative, 7);
    }
    if (!str_starts_with($relative, 'assets/')) {
        $relative = 'assets/' . $relative;
    }
    $full = ROOT_PATH . '/public/' . $relative;
    $ver = is_file($full) ? (string) filemtime($full) : '1';
    return asset($path) . '?v=' . $ver;
}

function url(string $action, array $params = []): string {
    return base_path() . '/index.php?' . http_build_query(array_merge(['action' => $action], $params));
}

function redirect(string $action, array $params = []): void {
    header('Location: ' . url($action, $params));
    exit;
}

function require_role(string $role): void {
    if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== $role) {
        redirect('login');
    }
}

function flash_success(string $message): void {
    $_SESSION['success'] = $message;
}

function flash_error(string $message): void {
    $_SESSION['error'] = $message;
}

function first_name(string $fullName): string {
    $parts = preg_split('/\s+/', trim($fullName));
    return $parts[0] ?? $fullName;
}

function portal_section(): string {
    $section = trim($_GET['section'] ?? '');
    return $section !== '' ? $section : 'dashboard';
}

function portal_nav_active(string $sectionKey): string {
    return portal_section() === $sectionKey ? 'active' : '';
}

function build_portal_notifications(array $messages, array $appointments, string $role): array {
    $items = [];

    foreach ($messages as $message) {
        if (!empty($message['is_read'])) {
            continue;
        }
        $items[] = [
            'type' => 'message',
            'title' => 'Unread message',
            'body' => ($message['sender_name'] ?? 'System') . ': ' . ($message['title'] ?? ''),
            'time' => $message['sent_at'] ?? date('Y-m-d H:i:s'),
            'section' => 'messages',
        ];
    }

    foreach ($appointments as $appointment) {
        $status = $appointment['status'] ?? '';
        if ($role === 'advisor' && $status === 'pending') {
            $items[] = [
                'type' => 'appointment',
                'title' => 'Appointment request',
                'body' => ($appointment['student_name'] ?? 'Student') . ' requested a meeting',
                'time' => $appointment['created_at'] ?? $appointment['appointment_date'] ?? date('Y-m-d H:i:s'),
                'section' => 'appointments',
            ];
        } elseif ($role === 'student' && $status === 'pending') {
            $items[] = [
                'type' => 'appointment',
                'title' => 'Appointment pending',
                'body' => 'Your request with ' . ($appointment['advisor_name'] ?? 'advisor') . ' awaits approval',
                'time' => $appointment['created_at'] ?? $appointment['appointment_date'] ?? date('Y-m-d H:i:s'),
                'section' => 'appointments',
            ];
        }
    }

    usort($items, static fn ($a, $b) => strtotime($b['time']) <=> strtotime($a['time']));
    return $items;
}

function filter_messages_by_search(array $messages, string $search): array {
    if ($search === '') {
        return $messages;
    }
    $needle = strtolower($search);
    return array_values(array_filter($messages, static function (array $m) use ($needle) {
        $hay = strtolower(($m['title'] ?? '') . ' ' . ($m['message'] ?? '') . ' ' . ($m['sender_name'] ?? ''));
        return str_contains($hay, $needle);
    }));
}

function filter_students_by_search(array $students, string $search): array {
    if ($search === '') {
        return $students;
    }
    $needle = strtolower($search);
    return array_values(array_filter($students, static function (array $s) use ($needle) {
        $hay = strtolower(($s['student_name'] ?? '') . ' ' . ($s['student_email'] ?? '') . ' ' . ($s['student_number'] ?? ''));
        return str_contains($hay, $needle);
    }));
}
