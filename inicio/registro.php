<?php
$conexion = mysqli_connect("localhost", "root", "", "abarrotera");
if (!$conexion) {
  die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");

$exito = false;
$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nombre = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $confirm = $_POST['confirm'];

  if ($nombre && $email && $password && $confirm) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = "Correo inválido.";
    } elseif (strlen($password) < 6) {
      $error = "La contraseña debe tener al menos 6 caracteres.";
    } elseif ($password !== $confirm) {
      $error = "Las contraseñas no coinciden.";
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = mysqli_prepare($conexion, "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
      mysqli_stmt_bind_param($stmt, "sss", $nombre, $email, $hash);
      if (mysqli_stmt_execute($stmt)) {
        $exito = true;
      } else {
        $error = "Error al registrar: el correo ya está registrado.";
      }
    }
  } else {
    $error = "Completa todos los campos.";
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Crear Cuenta</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="inven.css?v=3" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .mensaje {
      text-align: center;
      padding: 1rem;
      font-weight: 600;
      border-radius: 8px;
      margin-bottom: 1rem;
    }
    .exito { background-color: #d1e7dd; color: #0f5132; }
    .error { background-color: #f8d7da; color: #842029; }
  </style>
</head>
<body>
  <div class="container1">
    <h1>👤 Crear una cuenta</h1>

    <?php if ($exito): ?>
      <div class="mensaje exito">✅ Cuenta creada correctamente. <a href="login.php">Inicia sesión</a></div>
    <?php elseif ($error): ?>
      <div class="mensaje error">❌ <?= $error ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
      <div class="campo">
        <label for="username">Nombre de usuario</label>
        <input type="text" id="username" name="username" required />
      </div>

      <div class="campo">
        <label for="email">Correo Electrónico</label>
        <input type="email" id="email" name="email" required />
      </div>

      <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required minlength="6" />
      </div>

      <div class="campo">
        <label for="confirm">Confirmar contraseña</label>
        <input type="password" id="confirm" name="confirm" required />
      </div>

      <button type="submit">Crear cuenta</button>
    </form>
    <a href="inicio.php" class="back">← Regresar al inicio</a>
  </div>
</body>
</html>