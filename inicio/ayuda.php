<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
  header("Location: inicio.php");
  exit();
}

$conexion = mysqli_connect("localhost", "root", "", "abarrotera");
if (!$conexion) { die("Error de conexión: " . mysqli_connect_error()); }
mysqli_set_charset($conexion, "utf8");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Panel GSS - Ayuda</title>
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
      <h2>¡Hola Leo! <span class="emoji">👋</span></h2>
    </div>
    <a href="logout.php" class="btn-cerrar">Cerrar sesión</a>
  </header>

  <div class="layout">
    <aside class="sidebar">
      <nav>
        <ul class="menu">
          <li><a href="inven.php">⬅️ inicio</a></li>
          <li><a href="inventario.php">📦 Inventario</a></li>
          <li><a href="merma.php">🗑️ Merma</a></li>
          <li><a href="proveedores.php">🚚 Proveedores</a></li>
          <li><a href="cuenta.php">⚙️ Editar perfil</a></li>
          <li><a href="Reportes.php">📊 Reportes</a></li>
          <li><a href="ayuda.php">❓ Ayuda</a></li>
        </ul>
      </nav>
    </aside>

    <main class="contenido-ayuda">
      <div class="caja-scrollable">
        <h1>¿Con qué podemos ayudarte?</h1>

        <!-- 🛒 COMPRAS -->
        <section class="bloque tarjetas-ayuda">
          <h3>🛒 Compras</h3>
          <div class="card" onclick="abrirModal('modalCompras')">
            <i class="icono">🛒</i>
            <h4>Registrar compras</h4>
            <p>Agrega productos nuevos al inventario con datos del proveedor.</p>
          </div>
          <div class="card" onclick="abrirModal('modalHistorial')">
            <i class="icono">📅</i>
            <h4>Historial de compras</h4>
            <p>Consulta compras previas con precios y fechas.</p>
          </div>
        </section>

        <!-- 📦 VENTAS -->
        <section class="bloque tarjetas-ayuda">
          <h3>📦 Ventas</h3>
          <div class="card" onclick="abrirModal('modalVentas')">
            <i class="icono">📈</i>
            <h4>Registrar ventas</h4>
            <p>Descuenta del inventario y genera reporte diario.</p>
          </div>
          <div class="card" onclick="abrirModal('modalStock')">
            <i class="icono">🔔</i>
            <h4>Alertas de stock</h4>
            <p>Recibe avisos cuando un producto esté por agotarse.</p>
          </div>
        </section>

        <!-- 👤 CUENTA -->
        <section class="bloque tarjetas-ayuda">
          <h3>👤 Tu cuenta</h3>
          <div class="card" onclick="abrirModal('modalPerfil')">
            <i class="icono">👤</i>
            <h4>Editar perfil</h4>
            <p>Actualiza tu nombre, correo y clave de acceso.</p>
          </div>
          <div class="card" onclick="abrirModal('modalActividad')">
            <i class="icono">📋</i>
            <h4>Panel de actividad</h4>
            <p>Revisa tus movimientos recientes en el sistema.</p>
          </div>
        </section>
      </div>
    </main>
  </div>

  <!-- 🔍 MODALES -->
  <div id="modalCompras" class="modal">
    <div class="modal-contenido">
      <span class="cerrar" onclick="cerrarModal('modalCompras')">&times;</span>
      <h2>Registrar compras</h2>
      <p>Este módulo te permite registrar productos nuevos, especificar proveedor, fecha y cantidad, y agregar al inventario.</p>
    </div>
  </div>

  <div id="modalHistorial" class="modal">
    <div class="modal-contenido">
      <span class="cerrar" onclick="cerrarModal('modalHistorial')">&times;</span>
      <h2>Historial de compras</h2>
      <p>Consulta los productos comprados, precios, fechas y proveedores. Ideal para analizar hábitos y comparar costos.</p>
    </div>
  </div>

  <div id="modalVentas" class="modal">
    <div class="modal-contenido">
      <span class="cerrar" onclick="cerrarModal('modalVentas')">&times;</span>
      <h2>Registrar ventas</h2>
      <p>Descuenta artículos del inventario al vender y genera reportes automáticos de ingresos diarios.</p>
    </div>
  </div>

  <div id="modalStock" class="modal">
    <div class="modal-contenido">
      <span class="cerrar" onclick="cerrarModal('modalStock')">&times;</span>
      <h2>Alertas de stock</h2>
      <p>Activa alertas para productos con pocas existencias y evita faltantes en el negocio.</p>
    </div>
  </div>

  <div id="modalPerfil" class="modal">
    <div class="modal-contenido">
      <span class="cerrar" onclick="cerrarModal('modalPerfil')">&times;</span>
      <h2>Editar perfil</h2>
      <p>Personaliza tu nombre, correo y clave. Configura tu sesión y acceso al panel de forma segura.</p>
    </div>
  </div>

  <div id="modalActividad" class="modal">
    <div class="modal-contenido">
      <span class="cerrar" onclick="cerrarModal('modalActividad')">&times;</span>
      <h2>Panel de actividad</h2>
      <p>Consulta tus últimos movimientos en el sistema: compras, ventas y edición de datos.</p>
    </div>
  </div>

  <!-- 🧠 SCRIPT MODALES -->
  <script>
    function abrirModal(id) {
      document.getElementById(id).style.display = "block";
    }

    function cerrarModal(id) {
      document.getElementById(id).style.display = "none";
    }
  </script>
<style>
.tarjetas-ayuda {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
  margin-top: 20px;
}

.card {
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 12px;
  padding: 20px;
  text-align: center;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: pointer;
}

.card:hover {
  transform: scale(1.03);
  box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.card i.icono {
  font-size: 36px;
  margin-bottom: 10px;
  display: block;
}

.card h4 {
  font-size: 18px;
  margin: 10px 0 5px;
}

.card p {
  font-size: 14px;
  color: #666;
}

/* MODALES */
.modal {
  display: none;
  position: fixed;
  z-index: 999;
  left: 0; top: 0;
  width: 100%; height: 100%;
  background-color: rgba(0,0,0,0.5);
}

.modal-contenido {
  background: #fff;
  margin: 10% auto;
  padding
  .caja-scrollable {
  max-width: 1100px;  /* antes puede estar en 900px o menos */
  margin: 0 auto;
  padding: 20px;
}
.contenido-ayuda {
  width: 100%;
  display: flex;
  justify-content: center;
}

</style>
<style>
  /* 🛠 Ampliar y pulir el área de tarjetas de ayuda */

.contenido-ayuda {
  width: 100%;
  display: flex;
  justify-content: center;
}

.caja-scrollable {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
  padding: 30px;
  max-height: none;       /* Evita que se corte si hay muchas tarjetas */
  overflow-y: visible;    /* Si lo quieres scrollable, cámbialo a auto */
}

/* 🎯 Tarjetas con más cuerpo */
.tarjetas-ayuda {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); /* Columnas más generosas */
  gap: 30px;
  margin-top: 30px;
}

.card {
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 12px;
  padding: 30px;
  text-align: center;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: pointer;
  min-height: 200px;
}

.card:hover {
  transform: scale(1.04);
  box-shadow: 0 12px 24px rgba(0,0,0,0.1);
}

.card i.icono {
  font-size: 42px;
  margin-bottom: 12px;
  display: block;
}

.card h4 {
  font-size: 22px;
  margin: 12px 0 8px;
}

.card p {
  font-size: 15px;
  color: #666;
}
.cerrar {
  position: absolute;
  top: 12px;
  right: 14px;
  font-size: 24px;
  font-weight: bold;
  color: #444;
  cursor: pointer;
  transition: color 0.3s ease;
}

.cerrar:hover {
  color: #e74c3c;
}
.modal-contenido {
  position: relative; /* Necesario para que la "x" se ubique dentro */
  background: #fff;
  margin: 10% auto;
  padding: 30px;
  max-width: 600px;
  border-radius: 12px;
  box-shadow: 0 12px 30px rgba(0,0,0,0.2);
}
</style>
</body>
</html>