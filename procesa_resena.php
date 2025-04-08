<?php
// procesa_resena.php

header('Content-Type: application/json');
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanear y validar los datos recibidos
    $nombre     = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $email      = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $resumen    = filter_input(INPUT_POST, 'resumen', FILTER_SANITIZE_STRING);
    $comentario = filter_input(INPUT_POST, 'comentario', FILTER_SANITIZE_STRING);

    if (!$nombre || !$email || !$resumen || !$comentario) {
        http_response_code(400);
        echo json_encode(['mensaje' => 'Datos incorrectos o incompletos.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO reviews (nombre, email, resumen, comentario) VALUES (:nombre, :email, :resumen, :comentario)");
        $stmt->execute([
            ':nombre'     => $nombre,
            ':email'      => $email,
            ':resumen'    => $resumen,
            ':comentario' => $comentario
        ]);
        echo json_encode(['mensaje' => 'Reseña guardada correctamente.']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['mensaje' => 'Error en la base de datos.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['mensaje' => 'Método no permitido.']);
}
?>
