<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$conexion = mysqli_connect("localhost", "root", "", "abarrotera");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $consulta = "SELECT * FROM inventario WHERE id_producto = $id";
    $resultado = mysqli_query($conexion, $consulta);
    $producto = mysqli_fetch_assoc($resultado);

    if (!$producto) {
        die("Producto no encontrado");
    }
} else {
    die("ID inválido");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevoNombre = $_POST['producto'];
    $nuevaMarca = $_POST['marca'];
    $nuevaCategoria = $_POST['categoria'];
    $nuevoStock = intval($_POST['stock']);
    $nuevoPrecio = floatval($_POST['precio']);

    $actualizar = "UPDATE inventario SET 
        producto = '$nuevoNombre',
        marca = '$nuevaMarca',
        categoria = '$nuevaCategoria',
        stock = $nuevoStock,
        precio = $nuevoPrecio
        WHERE id_producto = $id";

    if (mysqli_query($conexion, $actualizar)) {
        header("Location: inventario.php");
        exit;
    } else {
        echo "Error al actualizar: " . mysqli_error($conexion);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar producto</title>
  <link rel="stylesheet" href="inven.css?v=2" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    .formulario-editar {
      max-width: 550px;
      margin: 2rem auto;
      background: #fff;
      padding: 2rem 2.5rem;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    .formulario-editar h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #2c3e50;
    }

    .formulario-editar label {
      display: block;
      font-weight: 600;
      margin-bottom: 0.4rem;
      color: #34495e;
    }

    .formulario-editar input {
      width: 100%;
      padding: 0.6rem;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-bottom: 1.2rem;
      transition: border-color 0.3s ease;
    }

    .formulario-editar input:focus {
      outline: none;
      border-color: #3498db;
    }

    .formulario-editar button {
      width: 100%;
      padding: 0.75rem;
      background-color: #2c3e50;
      color: #fff;
      font-weight: 600;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .formulario-editar button:hover {
      background-color: #34495e;
    }

    .contenedor-panel {
      display: flex;
      height: calc(100vh - 64px);
    }

    main {
      flex: 1;
      padding: 2rem;
      overflow-y: auto;
    }
  </style>
</head>
<body>

  <header class="encabezado">
    <div class="logo-area">
      <img src="img/logo1.png" alt="Logo GSS" class="logo" />
      <span class="titulo">GSS Panel</span>
    </div>
    <div class="saludo">
      <h2>¡Hola Leo! <span class="emoji">👋</span></h2>
      <p>Editando producto</p>
    </div>
    <a href="logout.php" class="btn-cerrar">Cerrar sesión</a>
  </header>

  <div class="contenedor-panel">
    <aside class="sidebar">
      <nav>
        <ul class="menu">
          <li><a href="inventario.php"><span>📦</span> Inventario</a></li>
          <li><a href="merma.html"><span>🗑️</span> Merma</a></li>
          <li><a href="proveedores.html"><span>🚚</span> Proveedores</a></li>
          <li><a href="cuenta.html"><span>⚙️</span> Editar perfil</a></li>
          <li><a href="Reportes.html"><span>📊</span> Reportes</a></li>
          <li><a href="inicio.html"><span>⬅️</span> Atrás</a></li>
          <li><a href="ayuda.html"><span>❓</span> Ayuda</a></li>
        </ul>
      </nav>
    </aside>

    <main>
      <div class="formulario-editar">
        <h2>Editar producto</h2>
        <form method="post">
          <label for="producto">Producto:</label>
          <input type="text" name="producto" id="producto" value="<?= htmlspecialchars($producto['producto']) ?>" required>

          <label for="marca">Marca:</label>
          <input type="text" name="marca" id="marca" value="<?= htmlspecialchars($producto['marca']) ?>" required>

          <label for="categoria">Categoría:</label>
          <input type="text" name="categoria" id="categoria" value="<?= htmlspecialchars($producto['categoria']) ?>" required>

          <label for="stock">Stock:</label>
          <input type="number" name="stock" id="stock" value="<?= $producto['stock'] ?>" required>

          <label for="precio">Precio:</label>
          <input type="number" step="0.01" name="precio" id="precio" value="<?= $producto['precio'] ?>" required>

          <button type="submit">Guardar cambios</button>
        </form>
      </div>
    </main>
  </div>

</body>
</html>