<?php
session_start();

$conn = new mysqli("localhost", "root", "", "abarrotera");
if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}
$conn->set_charset("utf8");

$error = "";
$exito = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nombre = trim($_POST["nombre"] ?? '');
  $correo = strtolower(trim($_POST["email"] ?? ''));
  $pass = $_POST["password"] ?? '';

  // Verificar si el correo ya existe
  $stmt = $conn->prepare("SELECT id_usuario FROM registro WHERE correo = ?");
  $stmt->bind_param("s", $correo);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $error = "Este correo ya está registrado.";
  } else {
    $stmt->close();

    // Insertar nuevo usuario
    $passHash = password_hash($pass, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO registro (nombre, correo, contraseña) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $correo, $passHash);

    if ($stmt->execute()) {
      $exito = "Registro exitoso. Ya puedes iniciar sesión.";
    } else {
      $error = "Error al registrar: " . $conn->error;
    }
  }
  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>GSS - Registro</title>
  <link rel="stylesheet" href="inven.css?v=2" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
  <div class="pantalla-inicio" id="pantallaInicio">
    <img src="img/login.png" alt="Logo GSS" />
  </div>

  <div class="container">
    <section class="logo-side">
      <img src="img/login.png" alt="Logo GSS" />
    </section>

    <section class="form-side">
      <div class="login-content">
        <h2 class="bienvenida">Crea tu cuenta en GSS</h2>
        <img src="img/image.png" alt="Avatar de usuario" class="avatar" />
      </div>

      <form method="POST">
        <div class="grupo">
          <label for="nombre" class="etiqueta">Nombre completo</label>
          <input type="text" name="nombre" id="nombre" placeholder="Tu nombre" required />
        </div>

        <div class="grupo">
          <label for="email" class="etiqueta">Correo electrónico</label>
          <input type="email" name="email" id="email" placeholder="Tu correo" required />
        </div>

        <div class="grupo">
          <label for="password" class="etiqueta">Contraseña</label>
          <input type="password" name="password" id="password" placeholder="Crea una contraseña" required />
        </div>

        <?php if ($error): ?>
          <p class="mensaje-error"><?= htmlspecialchars($error) ?></p>
        <?php elseif ($exito): ?>
          <p class="mensaje-exito"><?= htmlspecialchars($exito) ?></p>
        <?php endif; ?>

        <button type="submit" class="btn-principal">Registrarse</button>
      </form>

      <div class="enlaces-extra">
        <a href="inicio.php" class="link">¿Ya tienes cuenta? Inicia sesión</a>
        <a href="ayuda.php" class="link">Ayuda</a>
      </div>
    </section>
  </div>

  <script>
    setTimeout(() => {
      const pantalla = document.getElementById('pantallaInicio');
      if (pantalla) pantalla.style.display = 'none';
    }, 3000);
  </script>
</body>
</html>