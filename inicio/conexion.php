<?php 
$conexion = mysqli_connect("localhost", "root", "", "abarrotera");

if (!$conexion) {
    die("❌ Error al conectar con la base de datos: " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8");
echo "✅ Conexión exitosa a la base de datos.";
?>
