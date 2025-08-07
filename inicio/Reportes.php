<?php
session_start();
$conexion = mysqli_connect("localhost", "root", "", "abarrotera");
if (!$conexion) {
  die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");

// Obtener datos mensuales
$meses        = ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"];
$compras      = $devoluciones = $danos = [];

$query = "SELECT * FROM ventas_mensuales ORDER BY FIELD(mes, 'Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic')";
$resultado = mysqli_query($conexion, $query);
if (!$resultado) {
  die("Error en la consulta: " . mysqli_error($conexion));
}
while ($fila = mysqli_fetch_assoc($resultado)) {
  $compras[]      = (int)$fila['compras'];
  $devoluciones[] = (int)$fila['devoluciones'];
  $danos[]        = (int)$fila['danos'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Panel GSS - Simulación de Merma</title>
  <link rel="stylesheet" href="inven.css?v=3" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
        <li><a href="inven.php"><span>⬅️</span> inicio</a></li>
        <li><a href="inventario.php"><span>📦</span> Inventario</a></li>
        <li><a href="merma.php"><span>🗑️</span> Merma</a></li>
        <li><a href="proveedores.php"><span>🚚</span> Proveedores</a></li>
        <li><a href="editar_usuario.php"><span>⚙️</span> Editar perfil</a></li>
        <li><a href="Reportes.php"><span>📊</span> Reportes</a></li>
        <li><a href="ayuda.php"><span>❓</span> Ayuda</a></li>
      </ul>
    </nav>
  </aside>

  <main>
    <section class="tabla-simulacion">
      <h3>📈 Simulación de ventas y pérdidas por mes</h3>
      <canvas id="graficoVentas" height="120"></canvas>
    </section>
  </main>
</div>

<script>
const ctx = document.getElementById('graficoVentas').getContext('2d');
const meses        = <?= json_encode($meses) ?>;
const compras      = <?= json_encode($compras) ?>;
const devoluciones = <?= json_encode($devoluciones) ?>;
const danos        = <?= json_encode($danos) ?>;
const netas        = compras.map((c, i) => c - devoluciones[i] - danos[i]);

new Chart(ctx, {
  type: 'bar',
  data: {
    labels: meses,
    datasets: [
      {
        label: 'Devoluciones',
        data: devoluciones,
        backgroundColor: 'rgba(243, 156, 18, 0.9)',
        borderColor: '#d35400',
        borderWidth: 1
      },
      {
        label: 'Daños',
        data: danos,
        backgroundColor: 'rgba(231, 76, 60, 0.9)',
        borderColor: '#c0392b',
        borderWidth: 1
      },
      {
        label: 'Ventas netas',
        data: netas,
        backgroundColor: 'rgba(52, 152, 219, 0.95)',
        borderColor: '#2980b9',
        borderWidth: 1,
        borderRadius: 6
      }
    ]
  },
  options: {
    responsive: true,
    elements: {
      bar: {
        borderWidth: 1,
        borderSkipped: false
      }
    },
    animation: {
      duration: 1000,
      easing: 'easeOutExpo'
    },
    plugins: {
      tooltip: {
        callbacks: {
          label: context => ` ${context.dataset.label}: ${context.parsed.y}`
        }
      },
      legend: {
        position: 'top',
        labels: {
          font: { size: 13 }
        }
      },
      title: {
        display: true,
        text: 'Comparativa de ventas netas y pérdidas por mes',
        font: { size: 16 }
      }
    },
    scales: {
      x: { stacked: true },
      y: {
        beginAtZero: true,
        stacked: true,
        title: {
          display: true,
          text: 'Cantidad (unidades)'
        }
      }
    }
  }
});
</script>

<style>
.tabla-simulacion {
  margin-top: -20px;
  padding: 30px 20px 30px;
  max-width: 900px;
  margin-left: auto;
  margin-right: auto;
  background-color: #f9fbff;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
  animation: fadeIn 0.8s ease;
}

.tabla-simulacion h3 {
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 20px;
  color: #333;
  text-align: center;
}

#graficoVentas {
  width: 100% !important;
  max-width: 850px;
  margin: auto;
  display: block;
  border-radius: 8px;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to   { opacity: 1; transform: translateY(0); }
}
</style>

</body>
</html>