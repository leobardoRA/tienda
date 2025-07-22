<?php
$conexion = mysqli_connect("localhost", "root", "", "abarrotera");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $producto = $_POST['producto'];
    $marca = $_POST['marca'];
    $categoria = $_POST['categoria'];
    $stock = (int)$_POST['stock'];
    $precio = (float)$_POST['precio'];

    $stmt = mysqli_prepare($conexion, "INSERT INTO inventario (producto, marca, categoria, stock, precio) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssdi", $producto, $marca, $categoria, $stock, $precio);
    mysqli_stmt_execute($stmt);

    header("Location: inventario.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Agregar Producto</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: #f0f4f8;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .form-container {
      background-color: #fff;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 480px;
    }

    .form-container h2 {
      margin-bottom: 20px;
      color: #333;
      text-align: center;
    }

    label {
      font-weight: 600;
      display: block;
      margin-bottom: 6px;
      margin-top: 15px;
    }

    input {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      transition: border 0.3s ease;
    }

    input:focus {
      border-color: #007BFF;
      outline: none;
    }

    button {
      margin-top: 25px;
      width: 100%;
      padding: 12px;
      background-color: #007BFF;
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background-color: #0056b3;
    }

    @media (max-width: 500px) {
      .form-container {
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>➕ Agregar Producto</h2>
    <form method="POST" action="agregar producto.php">
      <label>Producto:</label>
      <input type="text" name="producto" required>

      <label>Marca:</label>
      <input type="text" name="marca" required>

      <label>Categoría:</label>
      <input type="text" name="categoria" required>

      <label>Stock:</label>
      <input type="number" name="stock" required>

      <label>Precio:</label>
      <input type="number" step="0.01" name="precio" required>

      <button type="submit">Guardar</button>
    </form>
  </div>
</body>
</html>
