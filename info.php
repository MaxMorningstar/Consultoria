<?php
try {
    $pdo = new PDO(
      "pgsql:host=db.pnadqbspunigtmcpqsps.supabase.co;port=5432;dbname=postgres",
      "postgres",
      "M@x1m1l1@n029082003",
      [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]
    );
    echo "¡Conexión exitosa!";
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
