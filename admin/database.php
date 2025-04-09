<?php
class Database {
    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    private $db   = 'ikruh';
    private $user = 'root';
    private $pass = '';
    private $charset = 'utf8mb4';

    private function __construct() {
        $dsn = "mysql:host=$this->host;dbname=$this->db";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}