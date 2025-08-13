<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>GSS - Registro</title>
  <link rel="stylesheet" href="../css/inven.css?v=2" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
  <div class="pantalla-inicio" id="pantallaInicio">
    <img src="../img/login.png" alt="Logo GSS" />
  </div>

  <div class="container">
    <section class="logo-side">
      <img src="../img/login.png" alt="Logo GSS" />
    </section>

    <section class="form-side">
      <div class="login-content">
        <h2 class="bienvenida">Crea tu cuenta en GSS</h2>
        <img src="../img/image.png" alt="Avatar de usuario" class="avatar" />
      </div>

      <!-- ✅ Mensaje flotante estilizado -->
      <?php if (isset($_SESSION["mensaje"])): ?>
        <div class="mensaje-flotante">
          <?= htmlspecialchars($_SESSION["mensaje"]) ?>
        </div>
        <?php unset($_SESSION["mensaje"]); ?>
      <?php endif; ?>

      <!-- ✅ Formulario de registro -->
      <form method="POST" action="../php/registro.php">
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

        <button type="submit" class="btn-principal">Registrarse</button>
      </form>

      <div class="enlaces-extra">
        <a href="inicio.php" class="link">¿Ya tienes cuenta? Inicia sesión</a>
        <a href="ayuda.php" class="link">Ayuda</a>
      </div>
    </section>
  </div>

  <!-- ✅ Animación de entrada -->
  <script>
    window.addEventListener("load", () => {
      const pantalla = document.getElementById("pantallaInicio");
      pantalla.style.opacity = "0";
      pantalla.style.transition = "opacity 1s ease";
      setTimeout(() => pantalla.remove(), 1000);
    });
  </script>
</body>
</html>