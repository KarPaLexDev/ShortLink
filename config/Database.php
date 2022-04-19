<?php

namespace config;

use PDO;

class Database {

    private $host = "localhost";
    private $db_name = "short_link";
    private $username = "root";
    private $password = "";
    private $conn = null;

    public function getConnection(){
        if (!is_null($this->conn)) {
            return $this->conn;
        }
        $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
        $this->conn->exec("set names utf8");
        return $this->conn;
    }
}