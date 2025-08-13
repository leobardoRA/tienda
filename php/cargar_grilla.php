<?php
require_once 'conexion.php';

try {
    $stmt = $conexion->query("SELECT * FROM agregar ORDER BY id_producto DESC");
    $productos = $stmt->fetchAll();

    $total = 0;
    $valor = 0;

    foreach ($productos as $p) {
        $total++;
        $valor += $p['stock'] * $p['precio'];

        echo "<div class='producto' 
                  data-id='{$p['id_producto']}' 
                  data-nombre='{$p['nombre']}' 
                  data-precio='{$p['precio']}' 
                  data-categoria='{$p['categoria']}'>
                <img src='{$p['imagen']}' alt='{$p['nombre']}' />
                <h5>{$p['nombre']}</h5>
                <p>{$p['marca']} — {$p['categoria']}</p>
                <button class='btn-agregar'>🛒 Agregar</button>
              </div>";
    }

    echo "<script>
      document.getElementById('totalProductosStat').textContent = '$total';
      document.getElementById('valorInventarioStat').textContent = '$" . number_format($valor, 2) . "';
    </script>";
} catch (PDOException $e) {
    echo "<p>❌ Error al cargar productos: " . $e->getMessage() . "</p>";
}
?>