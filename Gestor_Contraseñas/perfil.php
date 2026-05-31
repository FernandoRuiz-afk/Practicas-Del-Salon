<?php
session_start();
require_once 'conexion.php';

$perfil = null;
$mensaje = "";

if (isset($_SESSION['usuario_id'])) {
    $user_id = $_SESSION['usuario_id'];
    
    $stmt = mysqli_prepare($conexion, "SELECT u.*, r.nombre_rol, g.nombre_grupo, c.codigo as credencial_admin 
        FROM usuarios u 
        LEFT JOIN roles r ON u.rol_id = r.id 
        LEFT JOIN grupos g ON u.grupo_id = g.id 
        LEFT JOIN credenciales c ON u.credencial_admin_id = c.id 
        WHERE u.id = ?");
    
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    
    $resultado = mysqli_stmt_get_result($stmt);
    $perfil = mysqli_fetch_assoc($resultado);
} else {
    $mensaje = "Inicia sesión para ver tu perfil.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="estilos/styles-perfil.css">
    <title>Perfil</title>
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

<div class="card" style="width: 18rem;">
  <div class="card-header">
   Tu perfil <?php echo $perfil ? "(" . $perfil['nombre_usuario'] . ")" : ""; ?>
  </div>
  <?php if($perfil): ?>
  <ul class="list-group list-group-flush">
    <li class="list-group-item"><strong>Correo electrónico:</strong><br><?php echo $perfil['correo_electronico']; ?></li>
    <li class="list-group-item"><strong>Grupo de trabajo:</strong><br><?php echo $perfil['nombre_grupo'] ?? 'Ninguno'; ?></li>
    <li class="list-group-item"><strong>Rol:</strong><br><?php echo $perfil['nombre_rol']; ?></li>
    
    <?php if($perfil['rol_id'] == 1): ?>
        <li class="list-group-item"><strong>Credencial de administrador:</strong><br><?php echo $perfil['credencial_admin']; ?></li>
    <?php endif; ?>
  </ul>
  <?php else: ?>
    <div class="card-body">
        <p><?php echo $mensaje; ?></p>
    </div>
  <?php endif; ?>
</div>

<a class="btn cerrar" href="mensaje.php">Solicitudes</a>
<a class="btn cerrar" href="cerrar.php">Cerrar Sesion</a>

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