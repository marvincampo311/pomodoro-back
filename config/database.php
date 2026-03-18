<?php
// pomodoro-back/config/database.php

$host = "sql305.infinityfree.com";
$user = "if0_41414898";
$pass = "M2265045V";
$db   = "if0_41414898_pomodoro";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}
