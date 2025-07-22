<?php
session_start();
$conexion = mysqli_connect("localhost", "root", "", "abarrotera");
if (!$conexion) {
  die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");

$exito = false;
$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $producto = trim($_POST['producto']);
  $motivo   = $_POST['motivo'];
  $fecha    = $_POST['fecha'];
  $cantidad = intval($_POST['cantidad']);

  if ($producto && $motivo && $fecha && $cantidad > 0) {
    $stmt = mysqli_prepare($conexion, "INSERT INTO merma (producto, motivo, fecha, cantidad) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssi", $producto, $motivo, $fecha, $cantidad);

    if (mysqli_stmt_execute($stmt)) {
      $exito = true;
    } else {
      $error = "Error al registrar: " . mysqli_error($conexion);
    }
  } else {
    $error = "Completa todos los campos correctamente.";
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
      <p>Bienvenido de nuevo</p>
    </div>
    <a href="inicio.php" class="btn-cerrar">Cerrar sesión</a>
  </header>

  <div class="layout">
    <aside class="sidebar">
      <nav>
        <ul class="menu">
          <li><a href="inventario.php"><span>📦</span> Inventario</a></li>
          <li><a href="merma.php"><span>🗑️</span> Merma</a></li>
          <li><a href="proveedores.php"><span>🚚</span> Proveedores</a></li>
          <li><a href="cuenta.php"><span>⚙️</span> Editar perfil</a></li>
          <li><a href="reportes.php"><span>📊</span> Reportes</a></li>
          <li><a href="inicio.php"><span>⬅️</span> Atrás</a></li>
          <li><a href="ayuda.php"><span>❓</span> Ayuda</a></li>
        </ul>
      </nav>
    </aside>

    <main>
      <section class="merma">
        <h3>🗑️ Registro de merma</h3>

        <?php if ($exito): ?>
          <div class="mensaje exito">✅ Merma registrada correctamente.</div>
        <?php elseif ($error): ?>
          <div class="mensaje error">❌ <?= $error ?></div>
        <?php endif; ?>

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
      </section>
    </main>
  </div>

</body>
</html>