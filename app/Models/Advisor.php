<?php


class Advisor extends User {
    public function getProfile(int $userId): ?array {
        $stmt = $this->conn->prepare(
            'SELECT u.id, u.name, u.email, a.id AS advisor_id
             FROM users u
             JOIN advisors a ON u.id = a.user_id
             WHERE u.id = :user_id LIMIT 1'
        );
        $stmt->execute([':user_id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
