<?php
// pomodoro-back/api/v1/register.php
header("Content-Type: application/json");
require_once '../../config/database.php';

// Solo aceptamos peticiones POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "MÃ©todo no permitido"]);
    exit;
}

// Obtenemos los datos (asumiendo que vienen por JSON)
$data = json_decode(file_get_contents("php://input"));

if (empty($data->username) || empty($data->email) || empty($data->password)) {
    echo json_encode(["status" => "error", "message" => "Datos incompletos"]);
    exit;
}

try {
    $db = Database::getInstance();

    // 1. Encriptar la contraseÃ±a (Nivel Senior: BCRYPT)
    $password_hash = password_hash($data->password, PASSWORD_BCRYPT);

    // 2. Preparar la sentencia (Evita SQL Injection)
    $stmt = $db->prepare("INSERT INTO users (username, email, password_hash) VALUES (:user, :email, :pass)");
    
    $stmt->bindParam(':user', $data->username);
    $stmt->bindParam(':email', $data->email);
    $stmt->bindParam(':pass', $password_hash);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success", 
            "message" => "Usuario registrado correctamente",
            "user_id" => $db->lastInsertId()
        ]);
    }

} catch (PDOException $e) {
    // Manejo de errores (ej. email duplicado)
    if ($e->getCode() == 23000) {
        echo json_encode(["status" => "error", "message" => "El email o usuario ya existen"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error interno: " . $e->getMessage()]);
    }
}
