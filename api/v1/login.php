<?php
// pomodoro-back/api/v1/login.php
header("Content-Type: application/json");
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "MÃ©todo no permitido"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->email) || empty($data->password)) {
    echo json_encode(["status" => "error", "message" => "Email y contraseÃ±a requeridos"]);
    exit;
}

try {
    $db = Database::getInstance();
    
    // Buscamos al usuario por email
    $stmt = $db->prepare("SELECT id, username, password_hash FROM users WHERE email = ?");
    $stmt->execute([$data->email]);
    $user = $stmt->fetch();

    // Verificamos si existe y si la contraseÃ±a coincide con el hash
    if ($user && password_verify($data->password, $user['password_hash'])) {
        // En una app Senior real, aquÃ­ generarÃ­amos un JWT. 
        // Por ahora, devolveremos el Ã©xito para el frontend.
        echo json_encode([
            "status" => "success",
            "message" => "Login exitoso",
            "user" => [
                "id" => $user['id'],
                "username" => $user['username']
            ]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Credenciales incorrectas"]);
    }

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Error en el servidor"]);
}
