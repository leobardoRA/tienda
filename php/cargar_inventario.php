<?php
require_once 'conexion.php';

try {
    $stmt = $conexion->query("SELECT * FROM agregar ORDER BY id_producto DESC");
    $productos = $stmt->fetchAll();

    foreach ($productos as $p) {
        echo "<tr>
            <td>{$p['nombre']}</td>
            <td>{$p['marca']}</td>
            <td>{$p['categoria']}</td>
            <td>{$p['stock']}</td>
            <td>$" . number_format($p['precio'], 2) . "</td>
            <td><img src='{$p['imagen']}' alt='{$p['nombre']}' width='50'></td>
            <td class='celda-acciones'>
              <button class='btn-editar'
                data-id='{$p['id_producto']}'
                data-producto='{$p['nombre']}'
                data-marca='{$p['marca']}'
                data-categoria='{$p['categoria']}'
                data-stock='{$p['stock']}'
                data-precio='{$p['precio']}'
                data-fecha=''>
                <i class='fas fa-edit'></i>
              </button>

              <button class='btn-borrar' data-id='{$p['id_producto']}'>
                <i class='fas fa-trash-alt'></i>
              </button>
            </td>
        </tr>";
    }
} catch (PDOException $e) {
    echo "<tr><td colspan='7'>❌ Error al cargar inventario: " . $e->getMessage() . "</td></tr>";
}
?>