<?php
include 'config.php';

if (isset($_POST["guardar_edicion"])) {
  $id        = $_POST["editar_id"];
  $producto  = trim($_POST['editar_producto']);
  $marca     = trim($_POST['editar_marca']);
  $categoria = trim($_POST['editar_categoria']);
  $stock     = (int) $_POST['editar_stock'];
  $precio    = (float) $_POST['editar_precio'];

  if ($producto && $marca && $categoria && $stock >= 0 && $precio >= 0) {
    $stmt = mysqli_prepare($conexion, "UPDATE inventario SET producto=?, marca=?, categoria=?, stock=?, precio=? WHERE id_producto=?");
    mysqli_stmt_bind_param($stmt, "sssidi", $producto, $marca, $categoria, $stock, $precio, $id);
    mysqli_stmt_execute($stmt);
    header("Location: inventario.php");
    exit;
  }
}
?>
