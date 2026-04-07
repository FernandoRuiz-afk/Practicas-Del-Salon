<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "mi_practica";

$conexion = mysqli_connect($host, $user, $pass, $db);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>