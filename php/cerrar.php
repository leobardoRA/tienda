<?php
// 🧠 Inicio de sesión para acceder a la sesión actual
session_start();

// 🔒 Destruye todos los datos de sesión
$_SESSION = []; // Limpia el array de sesión
session_unset(); // Elimina variables de sesión
session_destroy(); // Destruye la sesión

// 🧭 Redirección segura al inicio o login
header("Location: inicio.php");
exit;
?>