<?php
require_once 'conexion.php';

try {
    $stmt = $conexion->query("SELECT COUNT(*) AS total FROM agregar");
    $resultado = $stmt->fetch();
    echo $resultado['total'];
} catch (PDOException $e) {
    echo "0";
}
?>