<?php


class User extends BaseModel {
    private string $table = 'users';

    public $id;
    public $name;
    public $email;
    public $password;
    public $role;
    public $status;
    public $student_number;

    public function register(): bool {
        try {
            $this->conn->beginTransaction();

            $query = 'INSERT INTO ' . $this->table . '
                      SET name = :name, email = :email, password = :password,
                          role = :role, status = :status, student_number = :student_number';
            $stmt = $this->conn->prepare($query);

            $name = htmlspecialchars(strip_tags($this->name));
            $email = htmlspecialchars(strip_tags($this->email));
            $hash = password_hash($this->password, PASSWORD_BCRYPT);
            $role = htmlspecialchars(strip_tags($this->role));
            $status = htmlspecialchars(strip_tags($this->status));
            $studentNumber = htmlspecialchars(strip_tags($this->student_number));

            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $hash,
                ':role' => $role,
                ':status' => $status,
                ':student_number' => $studentNumber,
            ]);

            $newUserId = (int) $this->conn->lastInsertId();
            $this->insertRoleRecord($role, $newUserId);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    private function insertRoleRecord(string $role, int $userId): void {
        if ($role === 'student') {
            $sql = 'INSERT INTO students SET id = :id, user_id = :user_id';
        } elseif ($role === 'advisor') {
            $sql = 'INSERT INTO advisors SET id = :id, user_id = :user_id';
        } else {
            return;
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $userId, ':user_id' => $userId]);
    }

    public function login(): bool {
        $stmt = $this->conn->prepare(
            'SELECT id, name, email, password, role, status FROM ' . $this->table . ' WHERE email = :email LIMIT 1'
        );
        $stmt->execute([':email' => $this->email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($this->password, $row['password'])) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->role = $row['role'];
            $this->status = $row['status'];
            return true;
        }
        return false;
    }

    public function emailExists(): bool {
        $stmt = $this->conn->prepare('SELECT id FROM ' . $this->table . ' WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $this->email]);
        return (bool) $stmt->fetch();
    }

    public function approveStudent(int $id): bool {
        $stmt = $this->conn->prepare(
            'UPDATE ' . $this->table . ' SET status = "approved" WHERE id = :id AND role = "student"'
        );
        return $stmt->execute([':id' => $id]);
    }

    public function rejectStudent(int $id): bool {
        $stmt = $this->conn->prepare(
            'UPDATE ' . $this->table . ' SET status = "rejected" WHERE id = :id AND role = "student"'
        );
        return $stmt->execute([':id' => $id]);
    }

    public function createAdvisor(): bool {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare(
                'INSERT INTO ' . $this->table . '
                 SET name = :name, email = :email, password = :password, role = "advisor", status = "approved"'
            );
            $stmt->execute([
                ':name' => htmlspecialchars(strip_tags($this->name)),
                ':email' => htmlspecialchars(strip_tags($this->email)),
                ':password' => password_hash($this->password, PASSWORD_BCRYPT),
            ]);

            $newUserId = (int) $this->conn->lastInsertId();
            $this->insertRoleRecord('advisor', $newUserId);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getAllUsers(string $search = '', string $roleFilter = ''): PDOStatement {
        $query = 'SELECT id, name, email, role, status, student_number, created_at FROM ' . $this->table . ' WHERE 1=1';
        $params = [];

        if ($search !== '') {
            $query .= ' AND (name LIKE :search OR email LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }
        if ($roleFilter !== '') {
            $query .= ' AND role = :role_filter';
            $params[':role_filter'] = $roleFilter;
        }
        $query .= ' ORDER BY created_at DESC';

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

    public function deleteUser(int $id): bool {
        $stmt = $this->conn->prepare('DELETE FROM ' . $this->table . ' WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function getUserById(int $id): ?array {
        $stmt = $this->conn->prepare(
            'SELECT id, name, email, role, status, student_number FROM ' . $this->table . ' WHERE id = :id LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function countByRole(string $role): int {
        $stmt = $this->conn->prepare('SELECT COUNT(id) FROM ' . $this->table . ' WHERE role = :role');
        $stmt->execute([':role' => $role]);
        return (int) $stmt->fetchColumn();
    }

    public function countByRoleAndStatus(string $role, string $status): int {
        $stmt = $this->conn->prepare(
            'SELECT COUNT(id) FROM ' . $this->table . ' WHERE role = :role AND status = :status'
        );
        $stmt->execute([':role' => $role, ':status' => $status]);
        return (int) $stmt->fetchColumn();
    }

    public function getApprovedStudents(): array {
        $stmt = $this->conn->query(
            "SELECT id, name FROM users WHERE role = 'student' AND status = 'approved' ORDER BY name"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendingStudents(): array {
        $stmt = $this->conn->query(
            "SELECT id, name, email, student_number, created_at FROM users WHERE role = 'student' AND status = 'pending' ORDER BY created_at DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAdvisorList(): array {
        $stmt = $this->conn->query(
            "SELECT id, name FROM users WHERE role = 'advisor' ORDER BY name"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
