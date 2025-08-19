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
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Manejo de errores
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Modo de fetch
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desactiva emulación
];

try {
    // 🔌 Conexión segura
    $conexion = new PDO($dsn, $user, $pass, $options);
    echo "✅ Conexión exitosa a la base de datos.";
} catch (PDOException $e) {
    // 🛑 Manejo de error
    echo "❌ Error de conexión: " . $e->getMessage();
}
?>