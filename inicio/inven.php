<?php
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
  <title>Panel GSS - Inventario</title>
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
      <p>Bienvenido de nuevo</p>
    </div>
    <a href="inicio.html" class="btn-cerrar">Cerrar sesión</a>
  </header>

  <div class="layout">
    <aside class="sidebar">
      <nav>
        <ul class="menu">
          <li><a href="inventario.php"><span>📦</span> Inventario</a></li>
          <li><a href="merma.html"><span>🗑️</span> Merma</a></li>
          <li><a href="proveedores.html"><span>🚚</span> Proveedores</a></li>
          <li><a href="cuenta.html"><span>⚙️</span> Editar perfil</a></li>
          <li><a href="Reportes.html"><span>📊</span> Reportes</a></li>
          <li><a href="inven.php"><span>⬅️</span> Atrás</a></li>
          <li><a href="ayuda.html"><span>❓</span> Ayuda</a></li>
        </ul>
      </nav>
    </aside>

    <main>
      <section class="resumen-inventario">
        <div class="stats">
          <div class="stat">📦 Productos totales: 
            <strong>
              <?php
              $res = mysqli_query($conexion, "SELECT COUNT(*) as cnt FROM inventario");
              $cnt = mysqli_fetch_assoc($res)['cnt'] ?? 0;
              echo $cnt;
              ?>
            </strong>
          </div>
          <div class="stat">💰 Valor inventario: 
            <strong>
              <?php
              $res = mysqli_query($conexion, "SELECT SUM(precio * stock) as total FROM inventario");
              $total = number_format(mysqli_fetch_assoc($res)['total'] ?? 0, 2);
              echo "$" . $total;
              ?>
            </strong>
          </div>
        </div>

        <div class="buscador-productos">
          <input type="text" id="busquedaInput" placeholder="🔍 Buscar producto, marca o categoría..." oninput="filtrarProductos()" />
        </div>

        <section class="categorias-scroll">
          <h4>Categorías</h4>
          <div class="contenedor-categorias">
            <div class="categoria" data-categoria="*">Todas</div>
            <div class="categoria" data-categoria="Bebidas">Bebidas</div>
            <div class="categoria" data-categoria="frituras">Snacks y frituras</div>
            <div class="categoria" data-categoria="Cereales">Cereales</div>
            <div class="categoria" data-categoria="Dulces">Dulces</div>
            <div class="categoria" data-categoria="Lácteos">Lácteos</div>
            <div class="categoria" data-categoria="Carnes frías">Carnes frías</div>
            <div class="categoria" data-categoria="Limpieza">Limpieza</div>
            <div class="categoria" data-categoria="Higiene personal">Higiene personal</div>
            <div class="categoria" data-categoria="Mascotas">Mascotas</div>
          </div>
        </section>

        <div class="grid-productos">
          <?php
          $consulta = "SELECT * FROM inventario ORDER BY producto ASC";
          $resultado = mysqli_query($conexion, $consulta);
          while ($fila = mysqli_fetch_assoc($resultado)):
            $producto  = htmlspecialchars($fila['producto']);
            $marca     = htmlspecialchars($fila['marca']);
            $categoria = htmlspecialchars($fila['categoria']);
            $nombreMin = strtolower($producto . ' ' . $marca);

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
            <div class="producto">
              <img src="<?= $imgSrc ?>" alt="<?= $producto ?>" />
              <h5><?= $producto ?></h5>
              <p><?= $marca ?> — <?= $categoria ?></p>
            </div>
          <?php endwhile; ?>
        </div>
      </section>
    </main>
  </div>

  <script>
    function filtrarProductos() {
      const input = document.getElementById('busquedaInput');
      const filter = input.value.toLowerCase();
      const productos = document.querySelectorAll('.producto');

      productos.forEach(producto => {
        const nombre = producto.querySelector('h5').textContent.toLowerCase();
        const categoria = producto.querySelector('p').textContent.toLowerCase();
        producto.style.display =
          nombre.includes(filter) || categoria.includes(filter) ? '' : 'none';
      });
    }

    function filtrarPorCategoria(categoriaSeleccionada) {
      const productos = document.querySelectorAll('.producto');
      productos.forEach(producto => {
        const textoCategoria = producto.querySelector('p').textContent.toLowerCase();
        producto.style.display = textoCategoria.includes(categoriaSeleccionada.toLowerCase()) ? '' : 'none';
      });
    }

    document.querySelectorAll('.categoria').forEach(cat => {
      cat.addEventListener('click', () => {
        const categoria = cat.getAttribute('data-categoria');
        if (categoria === '*') {
          document.querySelectorAll('.producto').forEach(p => p.style.display = '');
        } else {
          filtrarPorCategoria(categoria);
        }
      });
    });
  </script>

</body>
</html>