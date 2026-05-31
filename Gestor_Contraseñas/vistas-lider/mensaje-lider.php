<?php
session_start();
require_once '../conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

$mi_id = $_SESSION['usuario_id'];

if (isset($_GET['accion']) && isset($_GET['id_solicitud'])) {
    $id_solicitud = intval($_GET['id_solicitud']);
    $accion = $_GET['accion'];

    if ($accion == 'aceptar') {

        $mi_credencial = "Credencial no encontrada"; 

        $query_credencial = "SELECT c.codigo 
                             FROM grupos g 
                             JOIN credenciales c ON g.credencial_grupo_id = c.id 
                             WHERE g.lider_id = ?";
                             
        $stmt_cred = mysqli_prepare($conexion, $query_credencial);
        mysqli_stmt_bind_param($stmt_cred, "i", $mi_id);
        mysqli_stmt_execute($stmt_cred);
        $resultado_cred = mysqli_stmt_get_result($stmt_cred);
        
        if ($fila_cred = mysqli_fetch_assoc($resultado_cred)) {
            $mi_credencial = $fila_cred['codigo'];
        }

        $stmt = mysqli_prepare($conexion, "UPDATE solicitudes SET estado = 'Aceptada', credencial_entregada = ? WHERE id = ? AND destinatario_id = ?");
        mysqli_stmt_bind_param($stmt, "sii", $mi_credencial, $id_solicitud, $mi_id);
        mysqli_stmt_execute($stmt);
        
    } elseif ($accion == 'rechazar') {
        $stmt = mysqli_prepare($conexion, "UPDATE solicitudes SET estado = 'Rechazada' WHERE id = ? AND destinatario_id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $id_solicitud, $mi_id);
        mysqli_stmt_execute($stmt);
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$query_recibidas = "SELECT s.id, u.nombre_usuario, r.nombre_rol 
                    FROM solicitudes s 
                    JOIN usuarios u ON s.remitente_id = u.id 
                    JOIN roles r ON u.rol_id = r.id 
                    WHERE s.destinatario_id = ? AND s.estado = 'Pendiente'";
$stmt_rec = mysqli_prepare($conexion, $query_recibidas);
mysqli_stmt_bind_param($stmt_rec, "i", $mi_id);
mysqli_stmt_execute($stmt_rec);
$resultado_recibidas = mysqli_stmt_get_result($stmt_rec);

$query_enviadas = "SELECT s.id, u.nombre_usuario AS destinatario, r.nombre_rol, s.estado, s.credencial_entregada 
                   FROM solicitudes s 
                   JOIN usuarios u ON s.destinatario_id = u.id 
                   JOIN roles r ON u.rol_id = r.id 
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../estilos/styles-mensaje.css">
    <title>Solicitudes</title>
</head>
<body>
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="inicio-lider.php">
      <img src="../imagenes/Sombrero2-logo.png" alt="HatStall" class="d-inline-block align-text-top">
    </a>
    <h1>
    <a class="navbar-brand" href="inicio-lider.php">HatStall Security</a>
    </h1>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">

        <li class="nav-item">
          <a class="nav-link" href="usuarios_Lista-lider.php">usuarios</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="grupo-lider.php">grupo</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="solicitud-lider.php">Solicitar Credencial</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="perfil-lider.php">ver perfil</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
    <div class="card mb-4">
      <div class="card-header">
        Solicitudes recibidas
      </div>
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th scope="col">De</th>
            <th scope="col">Rol</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <?php while($fila = mysqli_fetch_assoc($resultado_recibidas)): ?>
          <tr>
              <td class="align-middle"><?php echo htmlspecialchars($fila['nombre_usuario']); ?></td>
              <td class="align-middle"><?php echo htmlspecialchars($fila['nombre_rol']); ?></td>
              <td class="text-center">
                  <a href="?accion=aceptar&id_solicitud=<?php echo $fila['id']; ?>" class="btn btn-sm aceptar">Aceptar</a>
                  <a href="?accion=rechazar&id_solicitud=<?php echo $fila['id']; ?>" class="btn btn-sm rechazar">Rechazar</a>
              </td>
          </tr>
          <?php endwhile; ?>
          <?php if(mysqli_num_rows($resultado_recibidas) == 0): ?>
          <tr><td colspan="3" class="text-center">No hay solicitudes pendientes por aceptar</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="card mb-4">
      <div class="card-header">
        Mis solicitudes enviadas
      </div>
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th scope="col">Para</th>
            <th scope="col">Rol</th>
            <th scope="col">Estado</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <?php while($fila_env = mysqli_fetch_assoc($resultado_enviadas)): ?>
          <tr>
              <td><?php echo htmlspecialchars($fila_env['destinatario']); ?></td>
              <td><?php echo htmlspecialchars($fila_env['nombre_rol']); ?></td>
              <td>
                  <?php 
                  if($fila_env['estado'] == 'Pendiente') {
                      echo '<span>Pendiente</span>';
                  } elseif($fila_env['estado'] == 'Aceptada') {
                      echo '<span">Aceptada</span>';
                  } else {
                      echo '<span">Rechazada</span>';
                  }
                  ?>
              </td>
              <td>
                  <span class='text-success fw-bold'> <?php echo !empty($fila_env['credencial_entregada']) ? htmlspecialchars($fila_env['credencial_entregada']) : '-'; ?> </span>
              </td>
          </tr>
          <?php endwhile; ?>
          <?php if(mysqli_num_rows($resultado_enviadas) == 0): ?>
          <tr><td colspan="4" class="text-center">Aún no has enviado ninguna solicitud</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <a class="btn btn-secondary regresar mb-4" href="perfil-lider.php">Regresar</a>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
