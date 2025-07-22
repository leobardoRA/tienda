<?php
$conexion = mysqli_connect("localhost", "root", "", "abarrotera");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $eliminar = "DELETE FROM inventario WHERE id_producto = $id";
    if (mysqli_query($conexion, $eliminar)) {
        header("Location: inventario.php"); // Redirige al inventario después
        exit;
    } else {
        echo "Error al eliminar: " . mysqli_error($conexion);
    }
} else {
    echo "ID no válido";
}
?>
