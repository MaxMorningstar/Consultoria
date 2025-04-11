<?php
// db_connection.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host   = 'db.pnadqbspunigtmcpqsps.supabase.co';
$port   = 5432;
$dbname = 'postgres';  // Según la cadena de conexión, la base de datos se llama "postgres"
$user   = 'postgres';
$pass   = 'M@x1m1l1@n029082003';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $pdo = new PDO($dsn, $user, $pass, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['mensaje' => "Error en la conexión: " . $e->getMessage()]);
    exit;
}
?>
