<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = $_POST['producto'] ?? '';
    $marca     = $_POST['marca'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $stock     = $_POST['stock'] ?? 0;
    $precio    = $_POST['precio'] ?? 0;
    $fecha     = $_POST['fecha_caducidad'] ?? null;
    $imagen    = 'img/default.png';

    if ($nombre !== '' && $stock >= 0 && $precio >= 0) {
        try {
            $stmt = $conexion->prepare("
                INSERT INTO agregar (nombre, marca, categoria, stock, precio, imagen)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$nombre, $marca, $categoria, $stock, $precio, $imagen]);
            echo "✅ Producto agregado correctamente.";
        } catch (PDOException $e) {
            echo "❌ Error al agregar el producto: " . $e->getMessage();
        }
    } else {
        echo "⚠️ Datos inválidos. Verifica los campos obligatorios.";
    }
} else {
    echo "⚠️ Método no permitido.";
}
?>