<?php


class Student extends User {
    public function getProfile(int $userId): ?array {
        $stmt = $this->conn->prepare(
            'SELECT u.id, u.name, u.email, u.student_number, s.id AS student_id
             FROM users u
             JOIN students s ON u.id = s.user_id
             WHERE u.id = :user_id LIMIT 1'
        );
        $stmt->execute([':user_id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
