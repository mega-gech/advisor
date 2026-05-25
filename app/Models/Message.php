<?php


class Message extends BaseModel {
    private string $table = 'messages';

    public $id;
    public $sender_id;
    public $receiver_id;
    public $audience_type;
    public $message_type;
    public $title;
    public $message;
    public $sent_at;
    public $is_read;

    public function sendMessage(): bool {
        $stmt = $this->conn->prepare(
            'INSERT INTO ' . $this->table . '
             SET sender_id = :sender_id, receiver_id = :receiver_id, audience_type = :audience_type,
                 message_type = :message_type, title = :title, message = :message'
        );

        $senderId = (int) $this->sender_id;
        $title = htmlspecialchars(strip_tags($this->title));
        $body = htmlspecialchars(strip_tags($this->message));
        $messageType = htmlspecialchars(strip_tags($this->message_type));
        $audienceType = $this->audience_type !== null
            ? htmlspecialchars(strip_tags($this->audience_type))
            : null;

        $stmt->bindValue(':sender_id', $senderId, PDO::PARAM_INT);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':message', $body);
        $stmt->bindValue(':message_type', $messageType);

        if ($this->receiver_id === null) {
            $stmt->bindValue(':receiver_id', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':receiver_id', (int) $this->receiver_id, PDO::PARAM_INT);
        }

        if ($audienceType === null) {
            $stmt->bindValue(':audience_type', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':audience_type', $audienceType);
        }

        return $stmt->execute();
    }

    public function getRecentBroadcasts(int $limit = 4): array {
        $stmt = $this->conn->prepare(
            "SELECT title, message, sent_at FROM {$this->table}
             WHERE message_type = 'broadcast' AND audience_type = 'advisor'
             ORDER BY sent_at DESC LIMIT " . (int) $limit
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMessagesForUser($user_id, $role) {
        // Individual messages for this user
        $query = "SELECT m.*, u.name as sender_name 
                  FROM " . $this->table . " m
                  LEFT JOIN users u ON m.sender_id = u.id
                  WHERE m.receiver_id = :user_id ";

        // If the user is an advisor, also fetch mass broadcasts for advisors
        if ($role === 'advisor') {
            $query .= " OR (m.message_type = 'broadcast' AND m.audience_type = 'advisor') ";
        }

        $query .= " ORDER BY m.sent_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        return $stmt;
    }

    public function markAsRead($message_id, $user_id) {
        $query = 'UPDATE ' . $this->table . ' SET is_read = TRUE WHERE id = :message_id AND receiver_id = :user_id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':message_id', $message_id);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

    public function sendDirectMessage($sender_id, $receiver_id, $title, $body) {
        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;
        $this->audience_type = null;
        $this->message_type = 'individual';
        $this->title = $title;
        $this->message = $body;
        return $this->sendMessage();
    }

    public function countAll(): int {
        $stmt = $this->conn->query('SELECT COUNT(*) FROM ' . $this->table);
        return (int) $stmt->fetchColumn();
    }

    public function countUnreadForUser($user_id, $role) {
        $query = "SELECT COUNT(*) FROM " . $this->table . "
                  WHERE is_read = 0 AND (receiver_id = :user_id";
        if ($role === 'advisor') {
            $query .= " OR (message_type = 'broadcast' AND audience_type = 'advisor')";
        }
        $query .= ")";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function getConversationPartners($user_id, $role) {
        if ($role === 'advisor') {
            $query = "SELECT DISTINCT u.id, u.name, u.email
                      FROM users u
                      JOIN advisor_assignments aa ON aa.student_id = u.id
                      WHERE aa.advisor_id = :user_id AND aa.is_active = 1
                      ORDER BY u.name";
        } else {
            $query = "SELECT u.id, u.name, u.email
                      FROM advisor_assignments aa
                      JOIN users u ON aa.advisor_id = u.id
                      WHERE aa.student_id = :user_id AND aa.is_active = 1
                      LIMIT 1";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $role === 'advisor' ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
