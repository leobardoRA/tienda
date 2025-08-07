<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start(); // Necesario para capturar al usuario en sesión

$conexion = mysqli_connect("localhost", "root", "", "abarrotera");
if (!$conexion) { die("Error de conexión: " . mysqli_connect_error()); }
mysqli_set_charset($conexion, "utf8");

$user_id = $_SESSION["user_id"] ?? 0;

/* Totales personalizados */
$q1 = mysqli_query($conexion, "SELECT SUM(stock) AS total_unidades FROM inventario WHERE user_id = $user_id");
$total_unidades = (float) (mysqli_fetch_assoc($q1)['total_unidades'] ?? 0);

$q2 = mysqli_query($conexion, "SELECT SUM(precio * stock) AS total_valor FROM inventario WHERE user_id = $user_id");
$total_valor = number_format((float) (mysqli_fetch_assoc($q2)['total_valor'] ?? 0), 2);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
 <link rel="stylesheet" href="inven.css?v=2" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    .carrito-boton{
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: #0d6efd;
      color: white;
      padding: 16px 20px;
      border-radius: 50%;
      font-size: 24px;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
      z-index: 101;
      transition: transform 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .carrito-boton:hover{ transform: scale(1.08); }
    .carrito-boton .badge{
      position: absolute;
      top: -6px;
      right: -4px;
      background: #e63946;
      color: #fff;
      border-radius: 999px;
      font-size: 12px;
      padding: 2px 6px;
      min-width: 18px;
      text-align: center;
      font-weight: 700;
    }

    .carrito-lateral{
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 300px;
      background: #fff;
      border: 1px solid #ccc;
      padding: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      border-radius: 12px;
      z-index: 100;
      transform: translateY(120%);
      transition: transform .3s ease-in-out;
    }
    .carrito-lateral.mostrar{ transform: translateY(0); }

    .carrito-lateral h3{ margin: 0 0 8px 0; font-size: 18px; }
    .carrito-lateral ul{
      list-style: none;
      padding-left: 0;
      max-height: 200px;
      overflow: auto;
      margin-bottom: 10px;
    }
    .carrito-lateral li{
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap: 6px;
      font-size:14px;
      margin-bottom:6px;
      border-bottom:1px dashed #eee;
      padding-bottom:2px;
      flex-wrap: wrap;
    }
    .carrito-lateral .qty{
      display:flex; align-items:center; gap:4px;
    }
    .carrito-lateral .qty button{
      width:20px;height:20px;line-height:18px;text-align:center;border:none;border-radius:4px;cursor:pointer;background:#ddd;
    }
    .btn-eliminar-item{
      background:#e74c3c;
      color:#fff;
      border:none;
      border-radius:4px;
      cursor:pointer;
      padding:0 6px;
      font-size:12px;
    }
    #btn-finalizar{
      width:100%;
      background:#0d6efd;
      color:#fff;
      border:none;
      border-radius:8px;
      padding:8px 10px;
      cursor:pointer;
      font-weight:600;
    }
    #btn-finalizar:disabled{
      opacity:.5;
      cursor:not-allowed;
    }

    .btn-agregar{
      background-color: #38a169;
      color: white;
      border: none;
      padding: 5px 10px;
      margin-top: 6px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
    }
    .btn-agregar:hover{ background-color:#2f855a; }

    /* Grilla productos (si la usas) */
    .grid-productos{
      display: grid;
      grid-template-columns: repeat(auto-fill,minmax(180px,1fr));
      gap: 14px;
    }
    .producto{
      border:1px solid #e5e5e5;
      padding:10px;
      border-radius:8px;
      text-align:center;
      background:#fff;
    }
    .producto img{
      max-width:100%;
      height:80px;
      object-fit:contain;
      margin-bottom:8px;
    }
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
      <section class="resumen-inventario">
        <div class="stats">
          <div class="stat">📦 Productos totales: 
            <strong id="totalProductosStat"><?php echo number_format($total_unidades, 0); ?></strong>
          </div>
          <div class="stat">💰 Valor inventario: 
            <strong id="valorInventarioStat">$<?php echo $total_valor; ?></strong>
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
            $precio    = isset($fila['precio']) ? (float)$fila['precio'] : 0;
            $stock     = isset($fila['stock']) ? (int)$fila['stock'] : 0;
            $id        = (int)($fila['id_producto'] ?? 0); // usa id_producto

            $nombreMin = strtolower($producto . ' ' . $marca);
            $imgNombre = 'default.png';

            if (strpos($nombreMin, 'cheetos') !== false) { $imgNombre = 'chetos.png';
            } elseif (strpos($nombreMin, 'coca') !== false || strpos($nombreMin, 'coca cola') !== false) { $imgNombre = 'coca.png';
            } elseif (strpos($nombreMin, 'pepsi') !== false) { $imgNombre = 'pepsi.png';
            } elseif (strpos($nombreMin, 'bimbo') !== false) { $imgNombre = 'bimbo.png';
            } elseif (strpos($nombreMin, 'marinela') !== false) { $imgNombre = 'marinela.png';
            } elseif (strpos($nombreMin, 'barcel') !== false) { $imgNombre = 'barcel.png';
            } elseif (strpos($nombreMin, 'sabritas') !== false) { $imgNombre = 'sabritas.png';
            } elseif (strpos($nombreMin, 'lala') !== false) { $imgNombre = 'lala.png';
            } elseif (strpos($nombreMin, 'nutri leche') !== false || strpos($nombreMin, 'nutri') !== false) { $imgNombre = 'nutrileche.png';
            } elseif (strpos($nombreMin, 'alpura') !== false) { $imgNombre = 'alpura.png';
            } elseif (strpos($nombreMin, 'nestle') !== false) { $imgNombre = 'nestle.png';
            } elseif (strpos($nombreMin, 'danone') !== false) { $imgNombre = 'danone.png';
            } elseif (strpos($nombreMin, 'panditas') !== false) { $imgNombre = 'panditas.png';
            } elseif (strpos($nombreMin, 'gatorade') !== false) { $imgNombre = 'gatorade.png';
            } elseif (strpos($nombreMin, 'powerade') !== false) { $imgNombre = 'powerade.png';
            } elseif (strpos($nombreMin, 'bonafont') !== false || strpos($nombreMin, 'epura') !== false || strpos($nombreMin, 'ciel') !== false) { $imgNombre = 'agua.png';
            } elseif (strpos($nombreMin, 'jumex') !== false) { $imgNombre = 'jumex.png';
            } elseif (strpos($nombreMin, 'del valle') !== false || strpos($nombreMin, 'valle') !== false) { $imgNombre = 'delvalle.png';
            } elseif (strpos($nombreMin, 'gamesa') !== false) { $imgNombre = 'gamesa.png';
            } elseif (strpos($nombreMin, 'maizoro') !== false) { $imgNombre = 'maizoro.png';
            } elseif (strpos($nombreMin, 'hershey') !== false) { $imgNombre = 'hershey.png';
            } elseif (strpos($nombreMin, 'mazapan') !== false || strpos($nombreMin, 'de la rosa') !== false || strpos($nombreMin, 'delarosa') !== false) { $imgNombre = 'delarosa.png';
            } elseif (strpos($nombreMin, 'ricolino') !== false) { $imgNombre = 'ricolino.png';
            } elseif (strpos($nombreMin, 'colgate') !== false) { $imgNombre = 'colgate.png';
            } elseif (strpos($nombreMin, 'suavel') !== false || strpos($nombreMin, 'suavitel') !== false) { $imgNombre = 'suavitel.png';
            } elseif (strpos($nombreMin, 'zote') !== false) { $imgNombre = 'zote.png';
            } elseif (strpos($nombreMin, 'axion') !== false) { $imgNombre = 'axion.png';
            } elseif (strpos($nombreMin, 'cloralex') !== false) { $imgNombre = 'cloralex.png';
            } elseif (strpos($nombreMin, 'fabuloso morado') !== false) { $imgNombre = 'fabuloso morado.png';
            } elseif (strpos($nombreMin, 'pinol') !== false) { $imgNombre = 'pinol.png';
            } elseif (strpos($nombreMin, 'sanitizante') !== false || strpos($nombreMin, 'gel') !== false) { $imgNombre = 'gel.png';
            } elseif (strpos($nombreMin, 'papel higienico') !== false || strpos($nombreMin, 'regio') !== false) { $imgNombre = 'papel.png';
            } elseif (strpos($nombreMin, 'maseca') !== false) { $imgNombre = 'maseca.png';
            } elseif (strpos($nombreMin, 'knorr') !== false || strpos($nombreMin, 'suiza') !== false) { $imgNombre = 'knorr.png';
            } elseif (strpos($nombreMin, 'maggi') !== false) { $imgNombre = 'maggi.png';
            } elseif (strpos($nombreMin, 'valentina') !== false) { $imgNombre = 'valentina.png';
            } elseif (strpos($nombreMin, 'herdez') !== false) { $imgNombre = 'herdez.png';
            } elseif (strpos($nombreMin, 'la costeña') !== false || strpos($nombreMin, 'costeña') !== false) { $imgNombre = 'costena.png';
            } elseif (strpos($nombreMin, 'kinder') !== false) { $imgNombre = 'kinder.png';
            } elseif (strpos($nombreMin, 'pulparindo') !== false || strpos($nombreMin, 'tamarindo') !== false) { $imgNombre = 'pulparindo.png';
            } elseif (strpos($nombreMin, 'topo chico') !== false) { $imgNombre = 'topochico.png';
            } elseif (strpos($nombreMin, 'yakult') !== false) { $imgNombre = 'Yakult.png'; }

            $imgSrc = 'img/' . $imgNombre;
          ?>
            <div class="producto" 
                 data-id="<?= $id ?>" 
                 data-nombre="<?= $producto ?>" 
                 data-precio="<?= $precio ?>">
              <img src="<?= $imgSrc ?>" alt="<?= $producto ?>" />
              <h5><?= $producto ?></h5>
              <p><?= $marca ?> — <?= $categoria ?></p>
              <button class="btn-agregar">🛒 Agregar</button>
            </div>
          <?php endwhile; ?>
        </div>

        <!-- Carrito lateral -->
        <div class="carrito-lateral" id="carritoLateral">
          <h3>🧾 Carrito</h3>
          <ul id="lista-carrito"></ul>
          <p id="total-carrito">Total: $0.00</p>
          <button id="btn-finalizar" disabled>Finalizar compra</button>
        </div>

        <!-- Botón flotante -->
        <div class="carrito-boton" id="toggleCarrito">
          🛒
          <span class="badge" id="badgeCarrito">0</span>
        </div>

      </section>
    </main>
  </div>

  <script>
    /* ------ Filtro texto ------ */
    function filtrarProductos() {
      const input = document.getElementById('busquedaInput');
      const filter = input.value.toLowerCase();
      document.querySelectorAll('.producto').forEach(producto => {
        const nombre = producto.querySelector('h5').textContent.toLowerCase();
        const categoria = producto.querySelector('p').textContent.toLowerCase();
        producto.style.display = (nombre.includes(filter) || categoria.includes(filter)) ? '' : 'none';
      });
    }

    /* ------ Filtro por categoría ------ */
    function filtrarPorCategoria(categoriaSeleccionada) {
      document.querySelectorAll('.producto').forEach(producto => {
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

    /* ------ Carrito Front-End ------ */
    const carrito = [];

    function actualizarBadge(){
      const badge = document.getElementById('badgeCarrito');
      const totalItems = carrito.reduce((acc, p) => acc + p.cantidad, 0);
      badge.textContent = totalItems;
    }

    function actualizarCarrito(){
      const lista = document.getElementById('lista-carrito');
      const btnFinalizar = document.getElementById('btn-finalizar');
      const totalHtml = document.getElementById('total-carrito');
      lista.innerHTML = '';
      let total = 0;

      carrito.forEach((prod, idx) => {
        const sub = prod.precio * prod.cantidad;
        total += sub;

        const li = document.createElement('li');
        li.innerHTML = `
          <span>${prod.nombre}</span>
          <div class="qty">
            <button data-idx="${idx}" data-act="-" title="Restar">-</button>
            <span>${prod.cantidad}</span>
            <button data-idx="${idx}" data-act="+" title="Sumar">+</button>
          </div>
          <span>$${sub.toFixed(2)}</span>
          <button class="btn-eliminar-item" data-idx="${idx}">X</button>
        `;
        lista.appendChild(li);
      });

      totalHtml.textContent = `Total: $${total.toFixed(2)}`;
      btnFinalizar.disabled = carrito.length === 0;

      // eventos +/- y eliminar
      lista.querySelectorAll('button[data-act]').forEach(btn => {
        btn.addEventListener('click', e => {
          const i = parseInt(btn.dataset.idx);
          const act = btn.dataset.act;
          if (act === '+') carrito[i].cantidad++;
          if (act === '-' && carrito[i].cantidad > 1) carrito[i].cantidad--;
          actualizarCarrito();
          actualizarBadge();
        });
      });

      lista.querySelectorAll('.btn-eliminar-item').forEach(btn => {
        btn.addEventListener('click', e => {
          const i = parseInt(btn.dataset.idx);
          carrito.splice(i,1);
          actualizarCarrito();
          actualizarBadge();
        });
      });

      actualizarBadge();
    }

    // Agregar al carrito
    document.querySelectorAll('.btn-agregar').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const card = btn.closest('.producto');
        const id = parseInt(card.dataset.id || 0, 10);
        const nombre = card.dataset.nombre;
        const precio = parseFloat(card.dataset.precio);

        let item = carrito.find(p => p.id === id);
        if (!item){
          carrito.push({ id, nombre, precio, cantidad: 1 });
        } else {
          item.cantidad++;
        }
        actualizarCarrito();
        document.getElementById('carritoLateral').classList.add('mostrar');
      });
    });

    // Toggle del carrito
    const toggleBtn = document.getElementById('toggleCarrito');
    const carritoLateral = document.getElementById('carritoLateral');
    toggleBtn.addEventListener('click', () => {
      carritoLateral.classList.toggle('mostrar');
    });

    // Finalizar compra (actualiza inventario + stats en vivo)
    document.getElementById('btn-finalizar').addEventListener('click', async () => {
      if (!carrito.length) return;

      try {
        const resp = await fetch('actualizar_inventario.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ carrito })
        });

        const text = await resp.text(); // para ver errores no JSON
        let data;
        try { data = JSON.parse(text); }
        catch (e) {
          console.error('Respuesta no JSON del servidor:', text);
          alert('Error: el servidor no devolvió JSON. Revisa la consola.');
          return;
        }

        if (!data.ok) {
          console.error('PHP error:', data);
          alert(data.msg || 'Error al finalizar la compra');
          return;
        }

        // Actualizar stats
        const totalProductosStat = document.getElementById('totalProductosStat');
        const valorInventarioStat = document.getElementById('valorInventarioStat');

        if (typeof data.totalProductos !== 'undefined') {
          totalProductosStat.textContent = parseInt(data.totalProductos).toLocaleString('es-MX');
        }
        if (typeof data.totalValor !== 'undefined') {
          valorInventarioStat.textContent = '$' + data.totalValor;
        }

        alert('Gracias por tu compra, Leo 🎉');
        carrito.length = 0;
        actualizarCarrito();
        carritoLateral.classList.remove('mostrar');
      } catch (e) {
        console.error(e);
        alert('Ocurrió un error al finalizar la compra');
      }
    });
  </script>

</body>
</html>
