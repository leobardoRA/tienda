<?php
require_once 'conexion.php';

try {
    $stmt = $conexion->query("SELECT * FROM agregar ORDER BY id_producto DESC");
    $productos = $stmt->fetchAll();

    foreach ($productos as $p) {
        // Sanitizar variables
        $id = htmlspecialchars($p['id_producto']);
        $nombre = htmlspecialchars($p['nombre']);
        $marca = htmlspecialchars($p['marca']);
        $categoria = htmlspecialchars($p['categoria']);
        $precio = number_format($p['precio'], 2, '.', '');
        $imagen = htmlspecialchars($p['imagen']);

        echo "
        <div class='producto' 
             data-id='$id' 
             data-nombre='$nombre' 
             data-precio='$precio' 
             data-categoria='$categoria'>
             
            <img src='$imagen' alt='$nombre' />
            <h5>$nombre</h5>
            <p>$marca — $categoria</p>
            <button class='btn-agregar'>🛒 Agregar</button>
        </div>";
    }
} catch (PDOException $e) {
    echo "<p>❌ Error al cargar productos: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>