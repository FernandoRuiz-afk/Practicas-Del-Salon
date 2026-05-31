<?php
session_start();
require_once '../conexion.php';
$mi_id = $_SESSION['usuario_id'];

// Consultar las solicitudes que YO he enviado (Ordenadas por ID en lugar de fecha)
$query_enviadas = "SELECT s.estado, s.credencial_entregada, u.nombre_usuario AS destinatario 
                   FROM solicitudes s 
                   JOIN usuarios u ON s.destinatario_id = u.id 
                   WHERE s.remitente_id = ? ORDER BY s.id DESC";
                   
$stmt_env = mysqli_prepare($conexion, $query_enviadas);
mysqli_stmt_bind_param($stmt_env, "i", $mi_id);
mysqli_stmt_execute($stmt_env);
$resultado_enviadas = mysqli_stmt_get_result($stmt_env);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../estilos/styles-mensaje.css">
    <title>Empleados</title>
</head>
<body>
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="inicio-usuario.php">
      <img src="../imagenes/Sombrero2-logo.png" alt="HatStall" class="d-inline-block align-text-top">
    </a>
    <h1>
    <a class="navbar-brand" href="inicio-usuario.php">HatStall Security</a>
    </h1>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">

        <li class="nav-item">
          <a class="nav-link" href="grupo-usuario.php">grupo</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="solicitud-usuario.php">Solicitar Credencial</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="perfil-usuario.php">ver perfil</a>
          
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="card">
  <div class="card-header">
    Solicitudes enviadas
  </div>
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">Nombre</th>
      <th scope="col"></th>
      <th scope="col">Estado</th>
    </tr>
  </thead>
  <tbody>
    <?php while($fila = mysqli_fetch_assoc($resultado_enviadas)): ?>
    <tr>
        <td><?php echo htmlspecialchars($fila['destinatario']); ?></td>
        <td>Destinatario</td>
        <td>
            <?php 
                if($fila['estado'] == 'Aceptada') {
                    echo "<span class='text-success fw-bold'>" . htmlspecialchars($fila['credencial_entregada']) . "</span>";
                } elseif ($fila['estado'] == 'Rechazada') {
                    echo "<span class='text-danger'>Rechazada</span>";
                } else {
                    echo "<span class=''>Pendiente</span>";
                }
            ?>
        </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>
</div>

<a class="btn regresar" href="perfil-usuario.php">Regresar</a>

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