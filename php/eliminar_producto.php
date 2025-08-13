<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';

    if ($id !== '') {
        try {
            $stmt = $conexion->prepare("DELETE FROM agregar WHERE id_producto = ?");
            $stmt->execute([$id]);
            echo "✅ Producto eliminado correctamente.";
        } catch (PDOException $e) {
            echo "❌ Error al eliminar: " . $e->getMessage();
        }
    } else {
        echo "⚠️ ID inválido.";
    }
} else {
    echo "⚠️ Método no permitido.";
}
?>