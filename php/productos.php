<?php
// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "abarrotera");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");

// Consulta productos
$consulta = "SELECT * FROM inventario";
$resultado = mysqli_query($conexion, $consulta);
?>

<main>
  <section class="tabla">
    <table>
      <thead>
        <tr>
          <th>Producto</th>
          <th>Marca</th>
          <th>Categoría</th>
          <th>Stock</th>
          <th>Precio</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($resultado && mysqli_num_rows($resultado) > 0):
          while ($fila = mysqli_fetch_assoc($resultado)):
            $producto  = htmlspecialchars($fila['producto']);
            $marca     = htmlspecialchars($fila['marca']);
            $categoria = htmlspecialchars($fila['categoria']);
            $stock     = (int)$fila['stock'];
            $precio    = number_format($fila['precio'], 2);
            $id        = $fila['id_producto'];
        ?>
        <tr>
          <td><?= $producto ?></td>
          <td><?= $marca ?></td>
          <td><?= $categoria ?></td>
          <td><?= $stock ?></td>
          <td>$<?= $precio ?></td>
          <td>
            <!-- Botones para editar o borrar -->
            <button class="btn-editar" 
                    data-id="<?= $id ?>" 
                    data-producto="<?= $producto ?>" 
                    data-marca="<?= $marca ?>" 
                    data-categoria="<?= $categoria ?>" 
                    data-stock="<?= $stock ?>" 
                    data-precio="<?= $fila['precio'] ?>">
              Editar
            </button>
            <a href="borrar.php?id=<?= $id ?>" onclick="return confirm('¿Eliminar <?= addslashes($producto) ?>?')">Borrar</a>
          </td>
        </tr>
        <?php
          endwhile;
        else:
        ?>
        <tr><td colspan="6">No hay productos en el inventario.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </section>
</main>
