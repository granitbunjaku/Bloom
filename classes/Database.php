<?php

class Database {
    private static $instance = null;
    private $connection = null;

    private function __construct() {
        $this->connection = new mysqli('localhost', 'root', '', 'bloomsm');
    }

    public static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}
