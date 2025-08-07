<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$conexion = mysqli_connect("localhost", "root", "", "abarrotera");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Panel GSS - Proveedores</title>
  <link rel="stylesheet" href="inven.css?v=5" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    /* ... tus estilos iguales ... */
    /* No los repito aquí para que sea más corto */
  </style>
</head>
<body>
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
          <li><a href="inven.php">⬅️ inicio</a></li>
          <li><a href="inventario.php">📦 Inventario</a></li>
          <li><a href="merma.php">🗑️ Merma</a></li>
          <li><a href="proveedores.php">🚚 Proveedores</a></li>
          <li><a href="editar_usuario.php">⚙️ Editar perfil</a></li>
          <li><a href="Reportes.php">📊 Reportes</a></li>
          <li><a href="ayuda.php">❓ Ayuda</a></li>
        </ul>
      </nav>
    </aside>

    <main class="contenido-principal">
      <section class="panel-central">
        <div class="card-panel">
          <div class="columna izquierda">
            <div class="seccion">
              <label for="miTienda">🏬 Mi tienda:</label>
              <select id="miTienda">
                <option selected>Central</option>
                <option>Sur</option>
                <option>Online</option>
              </select>
            </div>

            <div class="seccion">
              <label for="fechaEntrega">📅 Fecha de entrega:</label>
              <input type="date" value="<?= date('Y-m-d') ?>" id="fechaEntrega" />
            </div>

            <div class="seccion buscador-productos">
              <label for="busquedaInput">🔍 Buscar proveedor:</label>
              <input type="text" id="busquedaInput" placeholder="Buscar por nombre..." oninput="filtrarProductos()" />
            </div>
          </div>

          <div class="columna derecha">
            <div class="proveedores-lista">
              <?php
              $query = "SELECT * FROM proveedores";
              $resultado = mysqli_query($conexion, $query);
              if ($resultado && mysqli_num_rows($resultado) > 0) {
                while ($fila = mysqli_fetch_assoc($resultado)) {
                  $logo = htmlspecialchars($fila['logo']);
                  $empresa = htmlspecialchars($fila['nombre_empresa']);
                  $contacto = htmlspecialchars($fila['nombre_contacto']);
                  echo '
                  <div class="proveedor-card">
                    <img src="' . $logo . '" alt="' . $empresa . '" />
                    <span>' . $empresa . ' – ' . $contacto . '</span>
                  </div>';
                }
              } else {
                echo "<p>No hay proveedores registrados.</p>";
              }
              ?>
            </div>
          </div>
        </div>
        <a class="ayuda-enlace" href="ayuda.php">¿Necesitas ayuda?</a>
      </section>
    </main>
  </div>

  <script>
    function filtrarProductos() {
      const input = document.getElementById('busquedaInput');
      const filtro = input.value.toLowerCase();
      const proveedores = document.querySelectorAll('.proveedor-card');

      proveedores.forEach(card => {
        const texto = card.textContent.toLowerCase();
        card.style.display = texto.includes(filtro) ? 'flex' : 'none';
      });
    }
  </script>
</body>
</html>
