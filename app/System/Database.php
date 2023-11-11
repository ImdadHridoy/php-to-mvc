<?php
namespace App\System;

class Database
{
    private $host;
    private $user;
    private $password;
    private $database;

    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $this->host = getenv('DB_HOST');
        $this->user = getenv('DB_USERNAME');
        $this->password = getenv('DB_PASSWORD');
        $this->database = getenv('DB_DATABASE');

        $this->conn = new \PDO("mysql:host={$this->host};dbname={$this->database}", $this->user, $this->password);
        $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }

}
