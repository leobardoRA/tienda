<?php
session_start();
session_destroy();
header("Location: inicio.php"); // o donde quieras redirigir después
exit;