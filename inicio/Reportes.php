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
      <section class="tabla-simulacion">
        <h3>📈 Simulación de ventas y pérdidas por mes</h3>
        <canvas id="graficoVentas" height="120"></canvas>
      </section>
    </main>
  </div>

  <script>
    const ctx = document.getElementById('graficoVentas').getContext('2d');
    const meses = <?= json_encode($meses) ?>;
    const compras = <?= json_encode($compras) ?>;
    const devoluciones = <?= json_encode($devoluciones) ?>;
    const danos = <?= json_encode($danos) ?>;
    const netas = compras.map((c, i) => c - devoluciones[i] - danos[i]);

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: meses,
        datasets: [
          {
            label: 'Devoluciones',
            data: devoluciones,
            backgroundColor: '#f39c12'
          },
          {
            label: 'Daños',
            data: danos,
            backgroundColor: '#e74c3c'
          },
          {
            label: 'Ventas netas',
            data: netas,
            backgroundColor: '#3498db',
            borderRadius: 6
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          tooltip: {
            callbacks: {
              label: function(context) {
                return ` ${context.dataset.label}: ${context.parsed.y}`;
              }
            }
          },
          legend: {
            position: 'top',
            labels: {
              font: {
                size: 13
              }
            }
          },
          title: {
            display: true,
            text: 'Comparativa de ventas netas y pérdidas por mes',
            font: {
              size: 16
            }
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
</body>
</html>