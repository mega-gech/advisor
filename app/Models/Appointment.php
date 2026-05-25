<?php


class Appointment extends BaseModel {
    private string $table = 'appointments';

    public $id;
    public $student_id;
    public $advisor_id;
    public $appointment_date;
    public $status;
    public $notes;

    public function create(): bool {
        $query = 'INSERT INTO ' . $this->table . '
                  SET student_id = :student_id, advisor_id = :advisor_id,
                      appointment_date = :appointment_date, notes = :notes, status = "pending"';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $this->student_id);
        $stmt->bindParam(':advisor_id', $this->advisor_id);
        $stmt->bindParam(':appointment_date', $this->appointment_date);
        $stmt->bindParam(':notes', $this->notes);
        return $stmt->execute();
    }

    public static function formatDateTimeInput(string $datetimeLocal): string {
        $normalized = str_replace('T', ' ', trim($datetimeLocal));
        if (strlen($normalized) === 16) {
            $normalized .= ':00';
        }
        return $normalized;
    }

    public function updateStatus($id, $status, $advisor_id): bool {
        $query = 'UPDATE ' . $this->table . ' SET status = :status
                  WHERE id = :id AND advisor_id = :advisor_id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':advisor_id', $advisor_id);
        return $stmt->execute();
    }

    public function getUpcomingForAdvisor($advisor_id, $limit = 5) {
        $query = 'SELECT a.*, u.name AS student_name
                  FROM ' . $this->table . ' a
                  JOIN users u ON a.student_id = u.id
                  WHERE a.advisor_id = :advisor_id
                    AND a.appointment_date >= NOW()
                  ORDER BY a.appointment_date ASC
                  LIMIT ' . (int) $limit;
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':advisor_id', $advisor_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUpcomingForStudent($student_id, $limit = 3) {
        $query = 'SELECT a.*, u.name AS advisor_name
                  FROM ' . $this->table . ' a
                  JOIN users u ON a.advisor_id = u.id
                  WHERE a.student_id = :student_id
                    AND a.appointment_date >= NOW()
                  ORDER BY a.appointment_date ASC
                  LIMIT ' . (int) $limit;
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll(): int {
        $stmt = $this->conn->query('SELECT COUNT(*) FROM ' . $this->table);
        return (int) $stmt->fetchColumn();
    }

    public function countPendingForAdvisor($advisor_id) {
        $query = 'SELECT COUNT(*) FROM ' . $this->table . '
                  WHERE advisor_id = :advisor_id AND status = "pending"';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':advisor_id', $advisor_id);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function countUpcomingForStudent($student_id) {
        $query = 'SELECT COUNT(*) FROM ' . $this->table . '
                  WHERE student_id = :student_id
                    AND appointment_date >= NOW()
                    AND status IN ("pending", "approved")';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function getAllForStudent(int $studentId): array {
        $stmt = $this->conn->prepare(
            'SELECT a.*, u.name AS advisor_name
             FROM ' . $this->table . ' a
             JOIN users u ON a.advisor_id = u.id
             WHERE a.student_id = :student_id
             ORDER BY a.appointment_date DESC'
        );
        $stmt->execute([':student_id' => $studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllForAdvisor(int $advisorId): array {
        $stmt = $this->conn->prepare(
            'SELECT a.*, u.name AS student_name
             FROM ' . $this->table . ' a
             JOIN users u ON a.student_id = u.id
             WHERE a.advisor_id = :advisor_id
             ORDER BY a.appointment_date DESC'
        );
        $stmt->execute([':advisor_id' => $advisorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
