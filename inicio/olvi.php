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
  $empresa = trim($_POST['empresa']);
  $contacto = trim($_POST['contacto']);
  $telefono = trim($_POST['telefono']);
  $email = trim($_POST['email']);

  if ($empresa && $contacto && $telefono && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $stmt = mysqli_prepare($conexion, "INSERT INTO proveedores (empresa, contacto, telefono, email) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $empresa, $contacto, $telefono, $email);

    if (mysqli_stmt_execute($stmt)) {
      $exito = true;
    } else {
      $error = "Error al guardar: " . mysqli_error($conexion);
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
  <title>Panel GSS - Proveedores</title>
  <link rel="stylesheet" href="inven.css?v=3" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    .mensaje {
      padding: 1rem;
      margin: 1rem 0;
      font-weight: 600;
      border-radius: 8px;
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
      <section class="proveedor-form">
        <h3>🚚 Registrar proveedor</h3>

        <?php if ($exito): ?>
          <div class="mensaje exito">✅ Proveedor registrado correctamente.</div>
        <?php elseif ($error): ?>
          <div class="mensaje error">❌ <?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="form-grid">
          <div class="grupo">
            <label>Empresa</label>
            <input type="text" name="empresa" placeholder="Ej. Coca Cola" required>
          </div>
          <div class="grupo">
            <label>Nombre del contacto</label>
            <input type="text" name="contacto" placeholder="Ej. Juan Pérez" required>
          </div>
          <div class="grupo">
            <label>Teléfono</label>
            <input type="text" name="telefono" placeholder="Ej. 2223456789" required>
          </div>
          <div class="grupo">
            <label>Correo electrónico</label>
            <input type="email" name="email" placeholder="ejemplo@correo.com" required>
          </div>
          <div class="grupo full">
            <button type="submit">Registrar proveedor</button>
          </div>
        </form>
      </section>
    </main>
  </div>
</body>
</html>