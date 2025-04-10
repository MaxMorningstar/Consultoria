<?php
session_start();
header('Content-Type: application/json');

// Leer JSON de la petición
$input = json_decode(file_get_contents('php://input'), true);
$email    = filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL);
$password = $input['password'] ?? '';

if (!$email || !$password) {
  http_response_code(400);
  echo json_encode(['error' => 'Datos inválidos']);
  exit;
}

// Configuración Supabase
$projectUrl = 'https://pnadqbspunigtmcpqsps.supabase.co/rest/v1/users';
$anonKey    = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InBuYWRxYnNwdW5pZ3RtY3Bxc3BzIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDQxMjQ5NzUsImV4cCI6MjA1OTcwMDk3NX0.rG9l-0MI3lLOnLQ1aev0ammANcS-jBpra4bI_AOMwpo';  // reemplaza con tu anon key

// 1) Autenticar contra Supabase Auth
$ch = curl_init("$projectUrl/auth/v1/token?grant_type=password");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST           => true,
  CURLOPT_HTTPHEADER     => [
    "apikey: $anonKey",
    "Authorization: Bearer $anonKey",
    "Content-Type: application/json"
  ],
  CURLOPT_POSTFIELDS     => json_encode([
    'email'    => $email,
    'password' => $password
  ]),
]);
$response = curl_exec($ch);
$err      = curl_error($ch);
$status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($err) {
  http_response_code(500);
  echo json_encode(['error' => 'Error de conexión: ' . $err]);
  exit;
}

$data = json_decode($response, true);
if ($status !== 200 || empty($data['access_token'])) {
  $msg = $data['error_description'] ?? $data['error'] ?? 'Credenciales inválidas';
  http_response_code(401);
  echo json_encode(['error' => $msg]);
  exit;
}

// 2) Verificar que el usuario tenga role = 'admin' en tu tabla users
$ch2 = curl_init("$projectUrl/rest/v1/users?select=role&email=eq.$email");
curl_setopt_array($ch2, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER     => [
    "apikey: $anonKey",
    "Authorization: Bearer $anonKey",
    "Accept: application/json"
  ],
]);
$res2 = curl_exec($ch2);
$err2 = curl_error($ch2);
curl_close($ch2);

if ($err2) {
  http_response_code(500);
  echo json_encode(['error' => 'Error verificando rol: ' . $err2]);
  exit;
}

$users = json_decode($res2, true);
if (!isset($users[0]['role']) || $users[0]['role'] !== 'admin') {
  http_response_code(403);
  echo json_encode(['error' => 'Acceso no autorizado']);
  exit;
}

// 3) Guardar sesión
$_SESSION['user'] = [
  'email' => $email,
  'role'  => 'admin',
  'token' => $data['access_token']
];

echo json_encode(['success' => true]);
