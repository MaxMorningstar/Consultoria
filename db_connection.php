<?php
// db_connection.php

$host   = 'db.pnadqbspunigtmcpqsps.supabase.co';
$port   = 5432;
$dbname = 'postgres';  // Nombre de la base de datos que usarás
$user   = 'postgres';     // Usuario proporcionado en la cadena de conexión
$pass   = 'M@x1m1l1@n029082003';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $pdo = new PDO($dsn, $user, $pass, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    // En producción, es recomendable registrar el error sin mostrarlo al usuario
    die("Error en la conexión: " . $e->getMessage());
}
?>
