<?php
session_start();

// Si quieres proteger esta sección solo para usuarios logueados:
if (!isset($_SESSION['usuario'])) {
  header("Location: login.php");
  exit;
}

// Puedes personalizar el saludo si tienes guardado el nombre:
$usuario = $_SESSION['usuario'] ?? 'Leo';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Panel GSS - Resumen</title>
  <link rel="stylesheet" href="inven.css?v=5" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
</head>
<body>

  <header class="encabezado">
    <div class="logo-area">
      <img src="img/logo1.png" alt="Logo GSS" class="logo" />
      <span class="titulo">GSS Panel</span>
    </div>
    <div class="saludo">
      <h2>¡Hola <?= htmlspecialchars($usuario) ?>! <span class="emoji">👋</span></h2>
      <p>Bienvenido de nuevo</p>
    </div>
    <a href="logout.php" class="btn-cerrar">Cerrar sesión</a>
  </header>

  <div class="layout">
    <!-- Menú lateral -->
    <aside class="sidebar">
      <nav>
        <ul class="menu">
          <li><a href="inven.php"><span>⬅️</span> inicio</a></li>
          <li><a href="inventario.php"><span>📦</span> Inventario</a></li>
          <li><a href="merma.php"><span>🗑️</span> Merma</a></li>
          <li><a href="proveedores.php"><span>🚚</span> Proveedores</a></li>
          <li><a href="cuenta.php"><span>⚙️</span> Editar perfil</a></li>
          <li><a href="Reportes.php"><span>📊</span> Reportes</a></li>
          <li><a href="ayuda.php"><span>❓</span> Ayuda</a></li>
        </ul>
      </nav>
    </aside>

    <main class="contenido-ayuda">
      <div class="caja-scrollable">
        <h1>¿Con qué podemos ayudarte?</h1>

        <section class="bloque">
          <h3>🛒 Compras</h3>
          <ul>
            <li><strong>Administrar y cancelar compras</strong><br />Pagar, seguir envíos, modificar o cancelar compras.</li>
            <li><strong>Devoluciones y reembolsos</strong><br />Devolver un producto o consultar reintegros.</li>
            <li><strong>Preguntas frecuentes</strong><br />Consultas comunes sobre el proceso de compra.</li>
          </ul>
        </section>

        <section class="bloque">
          <h3>📦 Ventas</h3>
          <ul>
            <li><strong>Reputación, ventas y envíos</strong><br />Reclamar reputación, consultar envíos o devoluciones.</li>
            <li><strong>Administrar publicaciones</strong><br />Modificar, mejorar calidad o resolver problemas.</li>
            <li><strong>Preguntas frecuentes</strong><br />Dudas comunes sobre vender en GSS.</li>
          </ul>
        </section>

        <section class="bloque">
          <h3>👤 Tu cuenta</h3>
          <ul>
            <li><strong>Perfil</strong><br />Configuración y visibilidad.</li>
            <li><strong>Seguridad y acceso</strong><br />Cambio de clave, recuperación y verificación.</li>
          </ul>
        </section>
      </div>
    </main>
  </div>

</body>
</html>