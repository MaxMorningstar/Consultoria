<?php
header('Content-Type: application/json');

$nombre  = $_POST['nombre']  ?? '';
$email   = $_POST['email']   ?? '';
$mensaje = $_POST['mensaje'] ?? '';

if (!$nombre || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$mensaje) {
  http_response_code(400);
  echo json_encode(['mensaje'=>'Datos incorrectos.']);
  exit;
}

// URL correcta apuntando al endpoint REST
$url    = 'https://pnadqbspunigtmcpqsps.supabase.co/rest/v1/contact_messages';
$apiKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InBuYWRxYnNwdW5pZ3RtY3Bxc3BzIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDQxMjQ5NzUsImV4cCI6MjA1OTcwMDk3NX0.rG9l-0MI3lLOnLQ1aev0ammANcS-jBpra4bI_AOMwpo';

$body = json_encode([
  'nombre'  => $nombre,
  'email'   => $email,
  'mensaje' => $mensaje
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

if ($err || $status >= 400) {
  http_response_code(500);
  echo json_encode(['mensaje'=>'Error al guardar: ' . ($err ?: $response)]);
} else {
  echo json_encode(['mensaje'=>'Mensaje enviado correctamente.']);
}
