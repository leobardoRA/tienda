<?php
require_once 'conexion.php';

try {
    $stmt = $conexion->query("SELECT stock, precio FROM agregar");
    $productos = $stmt->fetchAll();

    $total = count($productos);
    $valor = 0;

    foreach ($productos as $p) {
        $valor += $p['stock'] * $p['precio'];
    }

    echo json_encode([
        'total' => $total,
        'valor' => number_format($valor, 2)
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'total' => 0,
        'valor' => "0.00"
    ]);
}
?>