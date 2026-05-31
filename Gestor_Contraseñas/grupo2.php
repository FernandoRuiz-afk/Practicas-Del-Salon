<?php
session_start();
require_once 'conexion.php';

$id_grupo = 2; 

$query_grupo = mysqli_query($conexion, "SELECT g.nombre_grupo, c.codigo AS credencial, u.nombre_usuario AS lider 
    FROM grupos g 
    JOIN credenciales c ON g.credencial_grupo_id = c.id 
    JOIN usuarios u ON g.lider_id = u.id 
    WHERE g.id = $id_grupo");
$datos_grupo = mysqli_fetch_assoc($query_grupo);

$query_emp = mysqli_query($conexion, "SELECT nombre_usuario FROM usuarios WHERE grupo_id = $id_grupo AND rol_id = 3");
$empleados = mysqli_fetch_all($query_emp, MYSQLI_ASSOC);

$query_admins = mysqli_query($conexion, "SELECT nombre_usuario FROM usuarios WHERE rol_id = 1");
$admins = mysqli_fetch_all($query_admins, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="estilos/styles-grupos.css">
    <title>Grupo 1</title>
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

<div class="card" style="width: 77rem;">
  <div class="card-header">
    Grupo 2
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item"><strong>Líder de grupo:</strong><br>
        <?php echo $datos_grupo['lider']; ?>
    </li>
    <li class="list-group-item"><strong>Empleados del grupo:</strong><br>
        <?php foreach($empleados as $emp) echo $emp['nombre_usuario'] . " | "; ?>
    </li>
    <li class="list-group-item"><strong>Acceso a la credencial de grupo:</strong><br>
        <?php 
        echo $datos_grupo['lider'] . " (Líder) | "; 
        foreach($admins as $admin) echo $admin['nombre_usuario'] . " (Admin) | ";
        ?>
    </li>
    <li class="list-group-item"><strong>Credencial de grupo:</strong><br>
        <?php echo $datos_grupo['credencial']; ?>
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