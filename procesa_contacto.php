<?php
// procesa_contacto.php

header('Content-Type: application/json');
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre  = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $email   = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $mensaje = filter_input(INPUT_POST, 'mensaje', FILTER_SANITIZE_STRING);

    if (!$nombre || !$email || !$mensaje) {
        http_response_code(400);
        echo json_encode(['mensaje' => 'Datos incorrectos o incompletos.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (nombre, email, mensaje) VALUES (:nombre, :email, :mensaje)");
        $stmt->execute([
            ':nombre'  => $nombre,
            ':email'   => $email,
            ':mensaje' => $mensaje
        ]);
        echo json_encode(['mensaje' => 'Mensaje enviado correctamente.']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['mensaje' => 'Error en la base de datos.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['mensaje' => 'Método no permitido.']);
}
?>
