<?php
session_start();
$conexion = mysqli_connect("localhost", "root", "", "abarrotera");
if (!$conexion) {
  die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");

if (!isset($_SESSION['usuario'])) {
  header("Location: login.php");
  exit;
}

// Obtener usuario actual
$emailActual = $_SESSION['usuario'];
$stmt = mysqli_prepare($conexion, "SELECT nombre, email FROM usuarios WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $emailActual);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$datos = mysqli_fetch_assoc($resultado);

// Almacenar estado post-guardado
$exito = false;

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nombre = trim($_POST['nombre']);
  $nuevoCorreo = trim($_POST['correo']);
  $nuevaPass = $_POST['password'];

  // Validaciones mínimas
  if (!filter_var($nuevoCorreo, FILTER_VALIDATE_EMAIL)) {
    $error = "Correo inválido.";
  } else {
    // Actualizar datos
    if (!empty($nuevaPass)) {
      $passHash = password_hash($nuevaPass, PASSWORD_DEFAULT);
      $stmt = mysqli_prepare($conexion, "UPDATE usuarios SET nombre = ?, email = ?, password = ? WHERE email = ?");
      mysqli_stmt_bind_param($stmt, "ssss", $nombre, $nuevoCorreo, $passHash, $emailActual);
    } else {
      $stmt = mysqli_prepare($conexion, "UPDATE usuarios SET nombre = ?, email = ? WHERE email = ?");
      mysqli_stmt_bind_param($stmt, "sss", $nombre, $nuevoCorreo, $emailActual);
    }

    if (mysqli_stmt_execute($stmt)) {
      $_SESSION['usuario'] = $nuevoCorreo;
      $datos['nombre'] = $nombre;
      $datos['email'] = $nuevoCorreo;
      $exito = true;
    } else {
      $error = "Error al guardar: " . mysqli_error($conexion);
    }
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Panel GSS - Editar perfil</title>
  <link rel="stylesheet" href="inven.css?v=7" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    .mensaje-exito {
      background-color: #d1e7dd;
      color: #0f5132;
      border-radius: 6px;
      padding: 1rem;
      margin-bottom: 1rem;
      font-weight: 600;
      text-align: center;
    }
    .mensaje-error {
      background-color: #f8d7da;
      color: #842029;
      border-radius: 6px;
      padding: 1rem;
      margin-bottom: 1rem;
      font-weight: 600;
      text-align: center;
    }
    body {
      background: radial-gradient(circle at top left, #93c5fd, #3b82f6);
      margin: 0;
      min-height: 100vh;
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
      <h2>¡Hola <?= htmlspecialchars($datos['nombre']) ?>! <span class="emoji">👋</span></h2>
      <p>Edita tu perfil</p>
    </div>
    <a href="logout.php" class="btn-cerrar">Cerrar sesión</a>
  </header>

  <main>
    <section class="perfil-usuario">
      <h2>Editar perfil</h2>

      <?php if ($exito): ?>
        <div class="mensaje-exito">✅ Cambios guardados correctamente.</div>
      <?php elseif (isset($error)): ?>
        <div class="mensaje-error">❌ <?= $error ?></div>
      <?php endif; ?>

      <form class="formulario" method="POST">
        <div class="input-group">
          <label for="nombre">Nombre completo</label>
          <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($datos['nombre']) ?>" required />
        </div>

        <div class="input-group">
          <label for="correo">Correo electrónico</label>
          <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($datos['email']) ?>" required />
        </div>

        <div class="input-group">
          <label for="password">Nueva contraseña</label>
          <input type="password" id="password" name="password" placeholder="••••••••" />
        </div>

        <div class="acciones">
          <a href="inventario.php" class="btn-secundario">Volver</a>
          <button type="submit" class="btn-principal">Guardar cambios</button>
        </div>
      </form>
    </section>
  </main>
</body>
</html>