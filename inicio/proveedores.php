<?php
$conexion = mysqli_connect("localhost", "root", "", "abarrotera");
if (!$conexion) {
  die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");

// Aquí podrías obtener proveedores desde la base de datos si lo deseas
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Panel GSS - Simulación de Merma</title>
  <link rel="stylesheet" href="inven.css?v=3" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    <main class="contenido-principal">
      <section class="panel-central">
        <div class="card-panel dividido">
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

            <div class="buscador-productos">
              <input type="text" id="busquedaInput" placeholder="🔍 Buscar producto, marca o categoría..." oninput="filtrarProductos()" />
            </div>
          </div>

          <div class="columna derecha">
            <div class="proveedores-lista">
              <div class="proveedor-card">
                <img src="img/alpura.png" alt="Alpura" />
                <span>Alpura – Juan Hernández</span>
              </div>
              <div class="proveedor-card">
                <img src="img/bimbo.png" alt="Bimbo" />
                <span>Bimbo – Lázaro Bravo</span>
              </div>
              <div class="proveedor-card">
                <img src="img/coca.png" alt="Coca Cola" />
                <span>Coca Cola – Fernando Lara</span>
              </div>
              <div class="proveedor-card">
                <img src="img/barcel.png" alt="Barcel" />
                <span>Barcel – Juan Pablo</span>
              </div>
            </div>
          </div>
        </div>

        <footer class="panel-footer">
          <div class="contacto-box">
            <span>Contáctanos</span>
            <div class="redes-iconos">
              <a href="https://facebook.com"><img src="img/facebook.png" alt="Facebook" /></a>
              <a href="https://wa.me/"><img src="img/whatsapp.png" alt="WhatsApp" /></a>
            </div>
          </div>
          <a class="ayuda-enlace" href="ayuda.php">¿Necesitas ayuda?</a>
        </footer>
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