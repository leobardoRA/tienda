<?php
session_start();
$conexion = mysqli_connect("localhost", "root", "", "abarrotera");
if (!$conexion) {
  die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");

$mensaje = "";
$mensajeClase = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $producto = trim($_POST['producto']);
  $motivo = $_POST['motivo'];
  $fecha = $_POST['fecha'];
  $cantidad = intval($_POST['cantidad']);

  if ($producto && $motivo && $fecha && $cantidad > 0) {
    // Insertar en merma
    $stmt = mysqli_prepare($conexion, "INSERT INTO merma (producto, motivo, fecha, cantidad) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssi", $producto, $motivo, $fecha, $cantidad);

    if (mysqli_stmt_execute($stmt)) {
      // Descontar stock del inventario
      $buscar = mysqli_prepare($conexion, "SELECT id_producto, stock FROM inventario WHERE producto = ?");
      mysqli_stmt_bind_param($buscar, "s", $producto);
      mysqli_stmt_execute($buscar);
      $resultado = mysqli_stmt_get_result($buscar);

      if ($row = mysqli_fetch_assoc($resultado)) {
        $nuevoStock = max(0, $row['stock'] - $cantidad); // evita stock negativo
        $actualizar = mysqli_prepare($conexion, "UPDATE inventario SET stock = ? WHERE id_producto = ?");
        mysqli_stmt_bind_param($actualizar, "ii", $nuevoStock, $row['id_producto']);
        mysqli_stmt_execute($actualizar);
      }

      $mensaje = "✅ Merma registrada correctamente.";
      $mensajeClase = "success";
    } else {
      $mensaje = "❌ Error al registrar la merma.";
      $mensajeClase = "danger";
    }
  } else {
    $mensaje = "⚠️ Por favor completa todos los campos.";
    $mensajeClase = "warning";
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Panel GSS - Merma</title>
  <link rel="stylesheet" href="inven.css?v=3" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    .mensaje {
      margin: 1rem 0;
      padding: 1rem;
      border-radius: 6px;
      font-weight: 600;
      text-align: center;
    }
    .exito { background-color: #d1e7dd; color: #0f5132; }
    .error { background-color: #f8d7da; color: #842029; }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }
    th {
  font-weight: 600;
  text-transform: none; /* ← Esto quita las mayúsculas */
  color: #333; /* más oscuro pero no blanco */
  background-color: #fdfdfdff; /* gris suave */
  font-size: 14px;
}

  </style>
</head>
<body>
<?php if ($mensaje): ?>
  <div class="alert alert-<?php echo $mensajeClase; ?>" role="alert" style="margin-top: 1rem;">
    <?php echo $mensaje; ?>
  </div>
<?php endif; ?>

  <header class="encabezado">
    <div class="logo-area">
      <img src="img/logo1.png" alt="Logo GSS" class="logo" />
      <span class="titulo">GSS Panel</span>
    </div>
    <div class="saludo">
      <h2>¡Hola! <span class="emoji">👋</span></h2>
    </div>
    <a href="inicio.php" class="btn-cerrar">Cerrar sesión</a>
  </header>

  <div class="layout">
    <aside class="sidebar">
      <nav>
        <ul class="menu">
          <li><a href="inven.php"><span>⬅️</span> inicio</a></li>
          <li><a href="inventario.php"><span>📦</span> Inventario</a></li>
          <li><a href="merma.php"><span>🗑️</span> Merma</a></li>
          <li><a href="proveedores.php"><span>🚚</span> Proveedores</a></li>
          <li><a href="editar_usuario.php"><span>⚙️</span> Editar perfil</a></li>
          <li><a href="Reportes.php"><span>📊</span> Reportes</a></li>
          <li><a href="ayuda.php"><span>❓</span> Ayuda</a></li>
        </ul>
      </nav>
    </aside>

    <main>
      <section class="merma">
        <h3>🗑️ Registro de merma</h3>
        <form class="form-grid" method="POST">
          <div class="grupo">
            <label>Producto</label>
            <input type="text" name="producto" placeholder="Ej. Pan Bimbo Blanco" required>
          </div>
          <div class="grupo">
            <label>Motivo de merma</label>
            <select name="motivo" required>
              <option value="">Selecciona una opción</option>
              <option value="caducado">Producto caducado</option>
              <option value="roto">Producto roto</option>
              <option value="devuelto">Devuelto por cliente</option>
            </select>
          </div>
          <div class="grupo">
            <label>Fecha de merma</label>
            <input type="date" name="fecha" required>
          </div>
          <div class="grupo">
            <label>Cantidad</label>
            <input type="number" name="cantidad" placeholder="1" min="1" required>
          </div>
          <div class="grupo full">
            <button type="submit">Registrar merma</button>
          </div>
        </form>

       <hr>
<h3>📋 Historial de mermas</h3>
<table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left;">
  <tr style="background: #f4f4f4;">
    <th>PRODUCTO</th>
    <th>MOTIVO</th>
    <th>FECHA</th>
    <th>CANTIDAD</th>
  </tr>
  <?php
    $res = mysqli_query($conexion, "SELECT * FROM merma ORDER BY fecha DESC");

    if ($res && mysqli_num_rows($res) > 0) {
      while ($row = mysqli_fetch_assoc($res)) {
        echo "<tr>
                <td>" . htmlspecialchars($row['producto']) . "</td>
                <td>" . htmlspecialchars($row['motivo']) . "</td>
                <td>" . $row['fecha'] . "</td>
                <td>" . $row['cantidad'] . "</td>
              </tr>";
      }
    } else {
      echo "<tr><td colspan='4'>No hay registros de merma.</td></tr>";
    }
  ?>
</table>


      </section>
    </main>
  </div>

</body>
</html>
