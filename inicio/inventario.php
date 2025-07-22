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
  <title>Panel GSS</title>
  <link rel="stylesheet" href="inven.css?v=2" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>

  <!-- Encabezado -->
  <header class="encabezado">
    <div class="logo-area">
      <img src="img/logo1.png" alt="Logo GSS" class="logo" />
      <span class="titulo">GSS Panel</span>
    </div>
    <div class="saludo">
      <h2>¡Hola Leo! <span class="emoji">👋</span></h2>
      <p>Bienvenido de nuevo</p>
    </div>
    <a href="logout.php" class="btn-cerrar">Cerrar sesión</a>
  </header>

  <div class="layout">
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

    <main>
      <section class="acciones panel-acciones">
        <button onclick="location.href='agregar producto.php'">➕ Agregar producto</button>
        <button id="totalProductos">Total: 
          <?php 
            $res = mysqli_query($conexion, "SELECT COUNT(*) as cnt FROM inventario");
            $cnt = mysqli_fetch_assoc($res)['cnt'] ?? 0;
            echo $cnt;
          ?>
        </button>
      </section>

      <section class="tabla">
        <table>
          <thead>
            <tr>
              <th>Producto</th>
              <th>Marca</th>
              <th>Categoría</th>
              <th>Stock</th>
              <th>Precio</th>
              <th>Img</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $consulta = "SELECT * FROM inventario";
            $resultado = mysqli_query($conexion, $consulta);

            if (mysqli_num_rows($resultado) > 0):
              while ($fila = mysqli_fetch_assoc($resultado)):
                $producto  = strtolower($fila['producto']);
                $marca     = strtolower($fila['marca']);
                $categoria = htmlspecialchars($fila['categoria']);
                $stock     = (int)$fila['stock'];
                $precio    = number_format($fila['precio'], 2);
                $id        = $fila['id_producto'];

                // Imagen automática
               $imgNombre = 'default.png';
$nombreMin = strtolower($producto . ' ' . $marca);

if (strpos($nombreMin, 'cheetos') !== false) {
  $imgNombre = 'cheetos.png';
} elseif (strpos($nombreMin, 'coca') !== false || strpos($nombreMin, 'coca cola') !== false) {
  $imgNombre = 'coca.png';
} elseif (strpos($nombreMin, 'pepsi') !== false) {
  $imgNombre = 'pepsi.png';
} elseif (strpos($nombreMin, 'bimbo') !== false) {
  $imgNombre = 'bimbo.png';
} elseif (strpos($nombreMin, 'marinela') !== false) {
  $imgNombre = 'marinela.png';
} elseif (strpos($nombreMin, 'barcel') !== false) {
  $imgNombre = 'barcel.png';
} elseif (strpos($nombreMin, 'sabritas') !== false) {
  $imgNombre = 'sabritas.png';
} elseif (strpos($nombreMin, 'lala') !== false) {
  $imgNombre = 'lala.png';
} elseif (strpos($nombreMin, 'nutri leche') !== false || strpos($nombreMin, 'nutri') !== false) {
  $imgNombre = 'nutrileche.png';
} elseif (strpos($nombreMin, 'alpura') !== false) {
  $imgNombre = 'alpura.png';
} elseif (strpos($nombreMin, 'nestle') !== false) {
  $imgNombre = 'nestle.png';
} elseif (strpos($nombreMin, 'danone') !== false) {
  $imgNombre = 'danone.png';
} elseif (strpos($nombreMin, 'panditas') !== false) {
  $imgNombre = 'panditas.png';
} elseif (strpos($nombreMin, 'gatorade') !== false) {
  $imgNombre = 'gatorade.png';
} elseif (strpos($nombreMin, 'powerade') !== false) {
  $imgNombre = 'powerade.png';
} elseif (strpos($nombreMin, 'bonafont') !== false || strpos($nombreMin, 'epura') !== false || strpos($nombreMin, 'ciel') !== false) {
  $imgNombre = 'agua.png';
} elseif (strpos($nombreMin, 'jumex') !== false) {
  $imgNombre = 'jumex.png';
} elseif (strpos($nombreMin, 'del valle') !== false || strpos($nombreMin, 'valle') !== false) {
  $imgNombre = 'delvalle.png';
} elseif (strpos($nombreMin, 'gamesa') !== false) {
  $imgNombre = 'gamesa.png';
} elseif (strpos($nombreMin, 'maizoro') !== false) {
  $imgNombre = 'maizoro.png';
} elseif (strpos($nombreMin, 'hershey') !== false) {
  $imgNombre = 'hershey.png';
} elseif (strpos($nombreMin, 'mazapan') !== false || strpos($nombreMin, 'de la rosa') !== false || strpos($nombreMin, 'delarosa') !== false) {
  $imgNombre = 'delarosa.png';
} elseif (strpos($nombreMin, 'ricolino') !== false) {
  $imgNombre = 'ricolino.png';
} elseif (strpos($nombreMin, 'colgate') !== false) {
  $imgNombre = 'colgate.png';
} elseif (strpos($nombreMin, 'suavel') !== false || strpos($nombreMin, 'suavitel') !== false) {
  $imgNombre = 'suavitel.png';
} elseif (strpos($nombreMin, 'zote') !== false) {
  $imgNombre = 'zote.png';
} elseif (strpos($nombreMin, 'axion') !== false) {
  $imgNombre = 'axion.png';
} elseif (strpos($nombreMin, 'cloralex') !== false) {
  $imgNombre = 'cloralex.png';
} elseif (strpos($nombreMin, 'fabuloso morado') !== false) {
  $imgNombre = 'fabuloso morado.png';
} elseif (strpos($nombreMin, 'pinol') !== false) {
  $imgNombre = 'pinol.png';
} elseif (strpos($nombreMin, 'sanitizante') !== false || strpos($nombreMin, 'gel') !== false) {
  $imgNombre = 'gel.png';
} elseif (strpos($nombreMin, 'papel higienico') !== false || strpos($nombreMin, 'regio') !== false) {
  $imgNombre = 'papel.png';
} elseif (strpos($nombreMin, 'maseca') !== false) {
  $imgNombre = 'maseca.png';
} elseif (strpos($nombreMin, 'knorr') !== false || strpos($nombreMin, 'suiza') !== false) {
  $imgNombre = 'knorr.png';
} elseif (strpos($nombreMin, 'maggi') !== false) {
  $imgNombre = 'maggi.png';
} elseif (strpos($nombreMin, 'valentina') !== false) {
  $imgNombre = 'valentina.png';
} elseif (strpos($nombreMin, 'herdez') !== false) {
  $imgNombre = 'herdez.png';
} elseif (strpos($nombreMin, 'la costeña') !== false || strpos($nombreMin, 'costeña') !== false) {
  $imgNombre = 'costena.png';
} elseif (strpos($nombreMin, 'kinder') !== false) {
  $imgNombre = 'kinder.png';
} elseif (strpos($nombreMin, 'pulparindo') !== false || strpos($nombreMin, 'tamarindo') !== false) {
  $imgNombre = 'pulparindo.png';
} elseif (strpos($nombreMin, 'topo chico') !== false) {
  $imgNombre = 'topochico.png';
  } elseif (strpos($nombreMin, 'yakult') !== false) {
  $imgNombre = 'Yakult.png';
}

$imgSrc = 'img/' . $imgNombre;
            ?>

            <tr>
              <td><?= htmlspecialchars($fila['producto']) ?></td>
              <td><?= htmlspecialchars($fila['marca']) ?></td>
              <td><?= $categoria ?></td>
              <td><?= $stock ?></td>
              <td>$<?= $precio ?></td>
              <td><img src="<?= $imgSrc ?>" alt="<?= $producto ?>" width="50" /></td>
              <td class="celda-acciones">
                <a class="btn-editar" href="editar.php?id=<?= $id ?>"><i class="fas fa-edit"></i></a>
                <a class="btn-borrar" href="borrar.php?id=<?= $id ?>" onclick="return confirm('¿Eliminar <?= addslashes($producto) ?>?')"><i class="fas fa-trash-alt"></i></a>
              </td>
            </tr>
            <?php
              endwhile;
            else:
            ?>
            <tr><td colspan="7">No hay productos en el inventario.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </section>
    </main>
  </div>
  <style>
    .tabla {
  max-height: 600px; /* ajusta según el alto que quieras */
  overflow-y: auto;
  border: 1px solid #ccc;
}

table {
  width: 100%;
  border-collapse: collapse;
}

thead th {
  position: sticky;
  top: 0;
  background: #082236ff;
  color: white;
  z-index: 5;
  text-align: center;
  padding: 0.75rem;
  font-weight: 600;
}

tbody td {
  padding: 0.6rem;
  text-align: center;
  background-color: #f9f9f9;
}
  </style>
  <style>td.celda-acciones {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem;
}

.celda-acciones a {
  width: 40px;
  height: 100px;
  font-size: 1.2rem;
  border-radius: 6px;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: transform 0.2s ease, color 0.2s ease;
  color: #333;
}

.celda-acciones a:hover {
  transform: scale(1.2);
  color: #008cff;
}</style>
  <script src="inven.js"></script>
</body>
</html>
