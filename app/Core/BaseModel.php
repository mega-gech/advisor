<?php

abstract class BaseModel {
    protected PDO $conn;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    protected function connection(): PDO {
        return $this->conn;
    }
}
