<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

$conexion = mysqli_connect("localhost", "root", "", "abarrotera");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");

// EDITAR PRODUCTO
if (isset($_POST["guardar_edicion"])) {
  $id        = $_POST["editar_id"];
  $producto  = trim($_POST['editar_producto']);
  $marca     = trim($_POST['editar_marca']);
  $categoria = trim($_POST['editar_categoria']);
  $stock     = (int) $_POST['editar_stock'];
  $precio    = (float) $_POST['editar_precio'];

  if ($producto && $marca && $categoria && $stock >= 0 && $precio >= 0) {
    $stmt = mysqli_prepare($conexion, "UPDATE inventario SET producto=?, marca=?, categoria=?, stock=?, precio=? WHERE id_producto=?");
    mysqli_stmt_bind_param($stmt, "sssidi", $producto, $marca, $categoria, $stock, $precio, $id);
    mysqli_stmt_execute($stmt);
    header("Location: inventario.php");
    exit;
  }
}

// AGREGAR NUEVO PRODUCTO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['producto'])) {
  $producto  = trim($_POST['producto']);
  $marca     = trim($_POST['marca']);
  $categoria = trim($_POST['categoria']);
  $stock     = (int) $_POST['stock'];
  $precio    = (float) $_POST['precio'];
  $user_id   = $_SESSION['user_id'] ?? 0;

  if ($producto && $marca && $categoria && $stock >= 0 && $precio >= 0) {
    $stmt = mysqli_prepare($conexion, "INSERT INTO inventario (user_id, producto, marca, categoria, stock, precio) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "isssid", $user_id, $producto, $marca, $categoria, $stock, $precio);
    mysqli_stmt_execute($stmt);
    header("Location: inventario.php");
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Panel GSS</title>
  <link rel="stylesheet" href="inven.css?v=2" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    .tabla {
      max-height: 600px;
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

    td.celda-acciones {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem;
    }

    .celda-acciones a,
    .celda-acciones button {
      width: 40px;
      height: 100px;
      font-size: 1.2rem;
      border-radius: 6px;
      display: flex;
      justify-content: center;
      align-items: center;
      transition: transform 0.2s ease, color 0.2s ease;
      color: #333;
      border: none;
      background: none;
      cursor: pointer;
    }

    .celda-acciones a:hover,
    .celda-acciones button:hover {
      transform: scale(1.2);
      color: #008cff;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 10;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.6);
    }

    .modal-contenido {
      background-color: #fff;
      margin: 10% auto;
      padding: 2rem;
      border: 1px solid #888;
      width: 300px;
      border-radius: 10px;
      font-family: Poppins, sans-serif;
    }

    .modal-contenido input {
      width: 100%;
      margin-bottom: 10px;
      padding: 6px;
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
                $producto  = htmlspecialchars($fila['producto']);
                $marca     = htmlspecialchars($fila['marca']);
                $categoria = htmlspecialchars($fila['categoria']);
                $stock     = (int)$fila['stock'];
                $precio    = number_format($fila['precio'], 2);
                $id        = $fila['id_producto'];

                $imgNombre = 'default.png';
                $nombreMin = strtolower($producto . ' ' . $marca);

                if (strpos($nombreMin, 'coca') !== false) {
                  $imgNombre = 'coca.png';
                } elseif (strpos($nombreMin, 'bimbo') !== false) {
                  $imgNombre = 'bimbo.png';
                }

                $imgSrc = 'img/' . $imgNombre;
            ?>
            <tr>
              <td><?= $producto ?></td>
              <td><?= $marca ?></td>
              <td><?= $categoria ?></td>
              <td><?= $stock ?></td>
              <td>$<?= $precio ?></td>
              <td><img src="<?= $imgSrc ?>" alt="<?= $producto ?>" width="50" /></td>
              <td class="celda-acciones">
                <button class="btn-editar" 
                        data-id="<?= $id ?>" 
                        data-producto="<?= $producto ?>" 
                        data-marca="<?= $marca ?>" 
                        data-categoria="<?= $categoria ?>" 
                        data-stock="<?= $stock ?>" 
                        data-precio="<?= $fila['precio'] ?>">
                  <i class="fas fa-edit"></i>
                </button>
                <a class="btn-borrar" href="borrar.php?id=<?= $id ?>" onclick="return confirm('¿Eliminar <?= addslashes($producto) ?>?')"><i class="fas fa-trash-alt"></i></a>
              </td>
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="7">No hay productos en el inventario.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </section>
    </main>
  </div>

  <!-- MODAL DE EDICIÓN -->
  <div id="modal-editar" class="modal">
    <div class="modal-contenido">
      <h2>Editar producto</h2>
      <form method="POST">
        <input type="hidden" name="editar_id" id="editar_id">
        <label>Producto: <input type="text" name="editar_producto" id="editar_producto"></label>
        <label>Marca: <input type="text" name="editar_marca" id="editar_marca"></label>
        <label>Categoría: <input type="text" name="editar_categoria" id="editar_categoria"></label>
        <label>Stock: <input type="number" name="editar_stock" id="editar_stock"></label>
        <label>Precio: <input type="number" step="0.01" name="editar_precio" id="editar_precio"></label><br><br>
        <button type="submit" name="guardar_edicion">Guardar cambios</button>
        <button type="button" onclick="cerrarModal()">Cancelar</button>
      </form>
    </div>
  </div>

  <script>
    const botonesEditar = document.querySelectorAll(".btn-editar");
    const modal = document.getElementById("modal-editar");

    botonesEditar.forEach(btn => {
      btn.addEventListener("click", () => {
        document.getElementById("editar_id").value = btn.dataset.id;
        document.getElementById("editar_producto").value = btn.dataset.producto;
        document.getElementById("editar_marca").value = btn.dataset.marca;
        document.getElementById("editar_categoria").value = btn.dataset.categoria;
        document.getElementById("editar_stock").value = btn.dataset.stock;
        document.getElementById("editar_precio").value = btn.dataset.precio;
        modal.style.display = "block";
      });
    });

    function cerrarModal() {
      modal.style.display = "none";
    }

    // Cierra el modal si se da clic afuera
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
  </script>
</body>
</html>
