<?php
header('Content-Type: application/json');

// Recogemos y validamos los datos
$nombre     = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
$email      = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$resumen    = filter_input(INPUT_POST, 'resumen', FILTER_SANITIZE_STRING);
$comentario = filter_input(INPUT_POST, 'comentario', FILTER_SANITIZE_STRING);

if (!$nombre || !$email || !$resumen || !$comentario) {
    http_response_code(400);
    echo json_encode(['mensaje' => 'Datos incorrectos o incompletos.']);
    exit;
}

// Endpoint REST de Supabase para la tabla "reviews"
$url    = 'https://pnadqbspunigtmcpqsps.supabase.co/rest/v1/reviews';
$apiKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InBuYWRxYnNwdW5pZ3RtY3Bxc3BzIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDQxMjQ5NzUsImV4cCI6MjA1OTcwMDk3NX0.rG9l-0MI3lLOnLQ1aev0ammANcS-jBpra4bI_AOMwpo';

$body = json_encode([
    'nombre'     => $nombre,
    'email'      => $email,
    'resumen'    => $resumen,
    'comentario' => $comentario
]);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => [
        'Accept: application/json',
        'Content-Type: application/json',
        'apikey: ' . $apiKey,
        'Authorization: Bearer ' . $apiKey
    ],
    CURLOPT_POSTFIELDS     => $body,
    // temporalmente para desarrollo local:
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
]);

$response = curl_exec($ch);
$err      = curl_error($ch);
$status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Comprobamos el resultado
if ($err || $status >= 400) {
    http_response_code(500);
    echo json_encode([
      'mensaje' => 'Error al guardar: ' . ($err ?: $response)
    ]);
} else {
    echo json_encode(['mensaje' => 'Reseña guardada correctamente.']);
}
