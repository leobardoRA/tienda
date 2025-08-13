<?php
// 📦 Configuración de conexión
$host = 'localhost';
$db   = 'abarrotera';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// 🧪 DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// ⚙️ Opciones PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $conexion = new PDO($dsn, $user, $pass, $options);
    // Puedes comentar esta línea si no quieres salida directa
    // echo "✅ Conexión exitosa.";
} catch (PDOException $e) {
    // Puedes loguear el error en lugar de mostrarlo en producción
    die("❌ Error de conexión: " . $e->getMessage());
}
?>