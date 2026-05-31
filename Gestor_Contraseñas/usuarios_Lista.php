<?php
session_start();
require_once 'conexion.php';


$query_admins = mysqli_query($conexion, "SELECT nombre_usuario FROM usuarios WHERE rol_id = 1");
$admins = mysqli_fetch_all($query_admins, MYSQLI_ASSOC);


$query_lideres = mysqli_query($conexion, "SELECT u.nombre_usuario, g.nombre_grupo FROM usuarios u JOIN grupos g ON u.grupo_id = g.id WHERE u.rol_id = 2");
$lideres = mysqli_fetch_all($query_lideres, MYSQLI_ASSOC);


$query_emp1 = mysqli_query($conexion, "SELECT nombre_usuario FROM usuarios WHERE rol_id = 3 AND grupo_id = 1");
$empleadosG1 = mysqli_fetch_all($query_emp1, MYSQLI_ASSOC);


$query_emp2 = mysqli_query($conexion, "SELECT nombre_usuario FROM usuarios WHERE rol_id = 3 AND grupo_id = 2");
$empleadosG2 = mysqli_fetch_all($query_emp2, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="estilos/styles-usuarios.css">
    <title>Empleados</title>
</head>
<body>
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="inicio.php">
      <img src="imagenes\Sombrero2-logo.png" alt="HatStall" class="d-inline-block align-text-top">
    </a>
    <h1>
    <a class="navbar-brand" href="inicio.php">HatStall Security</a>
    </h1>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">

        <li class="nav-item">
          <a class="nav-link" href="usuarios_Lista.php">usuarios</a>
        </li>

        <li class="nav-item dropdown">
          <button class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            Grupos
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="grupo1.php">Grupo 1</a></li>
            <li><a class="dropdown-item" href="grupo2.php">Grupo 2</a></li>
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="credenciales.php">credenciales</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="registro.php">agg usuario</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="perfil.php">ver perfil</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="card">
  <div class="card-header">
    Lista de Empleados
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item">
        <strong>Administradores:</strong><br>
        <?php foreach($admins as $admin) echo $admin['nombre_usuario'] . " | "; ?>
    </li>
    <li class="list-group-item">
        <strong>Lideres de Grupo:</strong><br>
        <?php foreach($lideres as $lider) echo $lider['nombre_usuario'] . " (" . $lider['nombre_grupo'] . ") | "; ?>
    </li>
    <li class="list-group-item">
        <strong>Empleados de Grupo 1:</strong><br>
        <?php foreach($empleadosG1 as $emp) echo $emp['nombre_usuario'] . " | "; ?>
    </li>
    <li class="list-group-item">
        <strong>Empleados de Grupo 2:</strong><br>
        <?php foreach($empleadosG2 as $emp) echo $emp['nombre_usuario'] . " | "; ?>
    </li>
  </ul>
</div>

<footer>
    <div class="container-fluid bg-dark p-5 text-center">
    <div class="row">
        <div class="col text-light">
            <h2>HatStall Security</h2>
            <p>Nos encargamos de resguardar tu seguridad.</p>
        </div>
        <div class="col text-light">
            <h2>Contacto</h2>
            <p>0412-5334073</p>
            <p>fernandoruizbriz@gmail.com</p>
        </div>
    </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>
</html>