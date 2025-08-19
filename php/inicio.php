<?php
session_start();
$conn = new mysqli("localhost", "root", "", "abarrotera");
if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}
$conn->set_charset("utf8");

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $correo = strtolower(trim($_POST["email"] ?? ''));
  $passIngresada = $_POST["password"] ?? '';

  $stmt = $conn->prepare("SELECT id_usuario, contraseña FROM registro WHERE correo = ?");
  if ($stmt) {
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $stmt->bind_result($id_usuario, $passEnBase);
      $stmt->fetch();

      if (password_verify($passIngresada, $passEnBase)) {
        $_SESSION["user_id"] = $id_usuario;
        $_SESSION["correo"] = $correo;
        header("Location: ../html/inven.html");
        exit();
      } else {
        $error = "Contraseña incorrecta.";
      }
    } else {
      $error = "Correo no registrado.";
    }
    $stmt->close();
  } else {
    $error = "Error en la consulta: " . $conn->error;
  }
}
?>