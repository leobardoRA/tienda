<?php
session_start();

$conn = new mysqli("localhost", "root", "", "abarrotera");
if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}
$conn->set_charset("utf8");

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $correo = strtolower(trim($_POST["email"] ?? ''));
  $passIngresada = $_POST["password"] ?? '';

  $stmt = $conn->prepare("SELECT id_usuario, contraseña FROM registro WHERE correo = ?");
  if ($stmt) {
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $stmt->bind_result($id_usuario, $passEnBase);
      $stmt->fetch();

      if (password_verify($passIngresada, $passEnBase)) {
        $_SESSION["user_id"] = $id_usuario; // ⚠️ Este es el importante
        $_SESSION["correo"] = $correo;

        header("Location: inven.php");
        exit();
      } else {
        $error = "Contraseña incorrecta.";
      }
    } else {
      $error = "Correo no registrado.";
    }
    $stmt->close();
  } else {
    $error = "Error en la consulta: " . $conn->error;
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>GSS - Iniciar sesión</title>
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
        <h2 class="bienvenida">Bienvenido a GSS</h2>
        <img src="img/image.png" alt="Avatar de usuario" class="avatar" />
      </div>

      <form method="POST">
        <div class="grupo">
          <label for="email" class="etiqueta">Correo electrónico</label>
          <input type="email" name="email" id="email" placeholder="Ingresa tu correo" required />
        </div>

        <div class="grupo">
          <label for="password" class="etiqueta">Contraseña</label>
          <input type="password" name="password" id="password" placeholder="Ingresa tu contraseña" required />
        </div>

        <div class="acciones-form">
          <a href="olvi.php" class="forgot">¿Has olvidado la contraseña?</a>
        </div>

        <?php if ($error): ?>
          <p class="mensaje-error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <button type="submit" class="btn-principal">Iniciar sesión</button>
      </form>

      <div class="enlaces-extra">
       <a href="registro.php" class="link">Crea una cuenta</a>
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
