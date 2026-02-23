<?php
// pomodoro-back/api/v1/seed_user.php
require_once '../../config/database.php';

try {
    $db = Database::getInstance();
    $user = "marvin_admin";
    $email = "admin@pomodoro.com";
    $pass = password_hash("123456", PASSWORD_BCRYPT);

    $stmt = $db->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->execute([$user, $email, $pass]);

    echo "✅ Usuario de prueba creado: marvin_admin / 123456";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}