<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['producto'])) {
  $producto  = trim($_POST['producto']);
  $marca     = trim($_POST['marca']);
  $categoria = trim($_POST['categoria']);
  $stock     = isset($_POST['stock']) ? (int) $_POST['stock'] : 0;
  $precio    = isset($_POST['precio']) ? (float) $_POST['precio'] : 0.0;
  $user_id   = $_SESSION['user_id'] ?? 0;

  if ($producto && $marca && $categoria && $stock >= 0 && $precio >= 0) {
    $stmt = mysqli_prepare($conexion, "INSERT INTO inventario (user_id, producto, marca, categoria, stock, precio) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt) {
      mysqli_stmt_bind_param($stmt, "isssid", $user_id, $producto, $marca, $categoria, $stock, $precio);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
    }
  }

  header("Location: ../html/inventario.php");
  exit;
} else {
  header("Location: ../html/inventario.php?error=1");
  exit;
}
?>