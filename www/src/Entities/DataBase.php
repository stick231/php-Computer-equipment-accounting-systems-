<?php
namespace Entities;

class Database{
    private $user = "user";    
    private $pass = "1234";
    private $db = "dbtest";
    private $host = "mysqlT";
    public $conn;

    public function getConnection()
    {
        try {
            $this->conn = new \PDO("mysql:host=" . $this->host . ";dbname=" . $this->db, $this->user, $this->pass);
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
      