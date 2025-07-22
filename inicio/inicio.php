<?php
session_start();
$conexion = mysqli_connect("localhost", "root", "", "abarrotera");
if (!$conexion) {
  die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = trim($_POST['email']);
  $pass = trim($_POST['password']);

  // Buscar usuario
  $query = "SELECT * FROM usuarios WHERE email = '$email'";
  $resultado = mysqli_query($conexion, $query);

  if ($fila = mysqli_fetch_assoc($resultado)) {
    // Verifica contraseña (asumiendo password_hash)
    if (password_verify($pass, $fila['password'])) {
      $_SESSION['usuario'] = $fila['email'];
      header("Location: inventario.php");
      exit;
    } else {
      $error = "Contraseña incorrecta.";
    }
  } else {
    $error = "Usuario no encontrado.";
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GSS - Iniciar sesión</title>
  <link rel="stylesheet" href="inven.css?v=1" />
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
        <h2 class="bienvenida">Bienvenido a GSS</h2>
        <img src="img/image.png" alt="Avatar de usuario" class="avatar" />
      </div>

      <form method="POST">
        <div class="grupo">
          <label for="email" class="etiqueta">Correo electrónico</label>
          <input type="email" name="email" id="email" placeholder="Ingresa tu email" required />
        </div>

        <div class="grupo">
          <label for="password" class="etiqueta">Contraseña</label>
          <input type="password" name="password" id="password" placeholder="Ingresa tu contraseña" required />
        </div>

        <div class="acciones-form">
          <a href="olvi.php" class="forgot">¿Has olvidado la contraseña?</a>
        </div>

        <?php if (isset($error)): ?>
          <p style="color:red; margin-bottom: 1rem;"><?= $error ?></p>
        <?php endif; ?>

        <button type="submit" class="btn-principal">Iniciar sesión</button>
      </form>

      <div class="enlaces-extra">
        <a href="registro.php" class="link">Crea una cuenta</a>
        <a href="ayuda.html" class="link">Ayuda</a>
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