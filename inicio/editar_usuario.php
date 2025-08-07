<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

if (!isset($_SESSION['correo'])) {
    header("Location: inicio.php");
    exit();
}

$conexion = mysqli_connect("localhost", "root", "", "abarrotera");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");

$correo_actual = $_SESSION['correo'];
$consulta = "SELECT * FROM registro WHERE correo = ?";
$stmt = mysqli_prepare($conexion, $consulta);
mysqli_stmt_bind_param($stmt, "s", $correo_actual);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$datos = mysqli_fetch_assoc($resultado);

if (!$datos) {
    die("No se encontraron datos del usuario.");
}

// Al enviar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevoNombre = trim($_POST['nombre']);
    $nuevoCorreo = trim($_POST['correo']);
    $nuevaPass = $_POST['contrasena'];

    if (!empty($nuevoNombre) && !empty($nuevoCorreo)) {
        // Verificar si el correo ya existe para otro usuario
        $verificar = mysqli_prepare($conexion, "SELECT 1 FROM registro WHERE correo = ? AND correo != ?");
        mysqli_stmt_bind_param($verificar, "ss", $nuevoCorreo, $correo_actual);
        mysqli_stmt_execute($verificar);
        $existe = mysqli_stmt_get_result($verificar);

        if (mysqli_num_rows($existe) > 0) {
            echo "<script>alert('El correo ya está en uso.');</script>";
        } else {
            // Construir y preparar la consulta final
            if (!empty($nuevaPass)) {
                $sql = "UPDATE registro SET nombre = ?, correo = ?, contrasena = ? WHERE correo = ?";
                $stmt = mysqli_prepare($conexion, $sql);
                if (!$stmt) {
                    die("Error en la preparación de contraseña: " . mysqli_error($conexion));
                }
                $pass_hash = password_hash($nuevaPass, PASSWORD_DEFAULT);
                mysqli_stmt_bind_param($stmt, "ssss", $nuevoNombre, $nuevoCorreo, $pass_hash, $correo_actual);
            } else {
                $sql = "UPDATE registro SET nombre = ?, correo = ? WHERE correo = ?";
                $stmt = mysqli_prepare($conexion, $sql);
                if (!$stmt) {
                    die("Error en la preparación sin contraseña: " . mysqli_error($conexion));
                }
                mysqli_stmt_bind_param($stmt, "sss", $nuevoNombre, $nuevoCorreo, $correo_actual);
            }

            // Ejecutar y redirigir
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['correo'] = $nuevoCorreo;
                $_SESSION['nombre'] = $nuevoNombre;
                echo "<script>alert('Perfil actualizado correctamente'); window.location='inven.php';</script>";
                exit();
            } else {
                echo "Error al actualizar: " . mysqli_stmt_error($stmt);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Perfil</title>
  <link rel="stylesheet" href="inven.css?v=3">
  <style>
    .formulario-editar {
      max-width: 700px;
      margin: auto;
      padding: 20px;
    }

    .form-flotante input {
      width: 100%;
      padding: 14px 12px;
      font-size: 1rem;
      border: 2px solid #ccc;
      border-radius: 6px;
      background-color: #fff;
      transition: border-color 0.3s ease;
    }

    .campo {
      position: relative;
      margin-bottom: 20px;
    }

    .campo label {
      position: absolute;
      top: 10px;
      left: 12px;
      font-size: 0.9rem;
      color: #999;
      pointer-events: none;
      transition: 0.2s ease all;
    }

    .campo input:focus + label,
    .campo input:not(:placeholder-shown) + label {
      top: -10px;
      left: 10px;
      font-size: 0.75rem;
      color: #4a90e2;
      background: #fff;
      padding: 0 5px;
    }

    button {
      padding: 10px 20px;
      font-size: 1rem;
      background-color: #4a90e2;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #357bd8;
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
      <h2>¡Hola <?= htmlspecialchars($datos['nombre']) ?>! <span class="emoji">👋</span></h2>
      <p>Editando tu perfil</p>
    </div>
    <a href="logout.php" class="btn-cerrar">Cerrar sesión</a>
  </header>

  <div class="contenedor-panel">
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
      <div class="formulario-editar">
        <h2>Editar Perfil</h2>
        <form method="post" class="form-flotante">
          <div class="campo">
            <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($datos['nombre']) ?>" required placeholder=" " />
            <label for="nombre">Nombre</label>
          </div>
          <div class="campo">
            <input type="email" name="correo" id="correo" value="<?= htmlspecialchars($datos['correo']) ?>" required placeholder=" " />
            <label for="correo">Correo</label>
          </div>
          <div class="campo">
            <input type="password" name="contrasena" id="contrasena" placeholder=" " />
            <label for="contrasena">Nueva contraseña (opcional)</label>
          </div>
          <button type="submit">Guardar cambios</button>
        </form>
      </div>
    </main>
  </div>
</body>
</html>