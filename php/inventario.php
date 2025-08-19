<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_edicion'])) {
    $id        = $_POST['editar_id'] ?? '';
    $nombre    = $_POST['editar_producto'] ?? '';
    $marca     = $_POST['editar_marca'] ?? '';
    $categoria = $_POST['editar_categoria'] ?? '';
    $stock     = $_POST['editar_stock'] ?? 0;
    $precio    = $_POST['editar_precio'] ?? 0;
    $fecha     = $_POST['editar_fecha_cad'] ?? null;

    if ($id !== '' && $nombre !== '' && $stock >= 0 && $precio >= 0) {
        try {
            $stmt = $conexion->prepare("
                UPDATE agregar
                SET nombre = ?, marca = ?, categoria = ?, stock = ?, precio = ?
                WHERE id_producto = ?
            ");
            $stmt->execute([$nombre, $marca, $categoria, $stock, $precio, $id]);

            header("Location: ../html/inventario.html");
            exit;
        } catch (PDOException $e) {
            echo "❌ Error al editar el producto: " . $e->getMessage();
        }
    } else {
        echo "⚠️ Datos inválidos. Verifica los campos obligatorios.";
    }
} else {
    echo "⚠️ Método no permitido.";
}
?>