<?php
// pomodoro-back/api/v1/register.php
header("Access-Control-Allow-Origin: https://pomodoro-front-phi.vercel.app");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}
header("Content-Type: application/json");
require_once '../../config/database.php';

// Solo aceptamos peticiones POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
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

    // 1. Encriptar la contraseña (Nivel Senior: BCRYPT)
    $password_hash = password_hash($data->password, PASSWORD_BCRYPT);

    // 2. Preparar la sentencia (Evita SQL Injection)
    $stmt = $db->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $data->username, $data->email, $password_hash);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success", 
            "message" => "Usuario registrado correctamente",
            "user_id" => $db->insert_id
        ]);
    }

} catch (Exception $e) {
    // Manejo de errores (ej. email duplicado)
    if ($db->errno == 1062) {
        echo json_encode(["status" => "error", "message" => "El email o usuario ya existen"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error interno: " . $e->getMessage()]);
    }
}
