<?php
session_start();
require_once 'conexion.php';

// Redirigir si no hay sesión activa
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$mi_id = $_SESSION['usuario_id'];

// Procesar acciones de Aceptar o Rechazar
if (isset($_GET['accion']) && isset($_GET['id_solicitud'])) {
    $id_solicitud = intval($_GET['id_solicitud']);
    $accion = $_GET['accion'];

    if ($accion == 'aceptar') {
        
        // 1. BUSCAR LA CREDENCIAL DEL ADMINISTRADOR ACTUAL
        $mi_credencial = "Credencial no encontrada"; // Valor por defecto en caso de error
        
        $query_credencial = "SELECT c.codigo 
                             FROM usuarios u 
                             JOIN credenciales c ON u.credencial_admin_id = c.id 
                             WHERE u.id = ?";
                             
        $stmt_cred = mysqli_prepare($conexion, $query_credencial);
        mysqli_stmt_bind_param($stmt_cred, "i", $mi_id);
        mysqli_stmt_execute($stmt_cred);
        $resultado_cred = mysqli_stmt_get_result($stmt_cred);
        
        // Si encontramos la credencial, la guardamos en la variable
        if ($fila_cred = mysqli_fetch_assoc($resultado_cred)) {
            $mi_credencial = $fila_cred['codigo'];
        }

        // 2. ACTUALIZAR LA SOLICITUD ENTREGANDO LA CREDENCIAL REAL
        $stmt = mysqli_prepare($conexion, "UPDATE solicitudes SET estado = 'Aceptada', credencial_entregada = ? WHERE id = ? AND destinatario_id = ?");
        mysqli_stmt_bind_param($stmt, "sii", $mi_credencial, $id_solicitud, $mi_id);
        mysqli_stmt_execute($stmt);
        
    } elseif ($accion == 'rechazar') {
        $stmt = mysqli_prepare($conexion, "UPDATE solicitudes SET estado = 'Rechazada' WHERE id = ? AND destinatario_id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $id_solicitud, $mi_id);
        mysqli_stmt_execute($stmt);
    }
    
    // Recargar la página para limpiar la URL
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Consultar solicitudes entrantes pendientes
$query_recibidas = "SELECT s.id, u.nombre_usuario, r.nombre_rol 
                    FROM solicitudes s 
                    JOIN usuarios u ON s.remitente_id = u.id 
                    JOIN roles r ON u.rol_id = r.id 
                    WHERE s.destinatario_id = ? AND s.estado = 'Pendiente'";
$stmt_rec = mysqli_prepare($conexion, $query_recibidas);
mysqli_stmt_bind_param($stmt_rec, "i", $mi_id);
mysqli_stmt_execute($stmt_rec);
$resultado_recibidas = mysqli_stmt_get_result($stmt_rec);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="estilos/styles-mensaje.css">
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
    Solicitudes recibidas
  </div>
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">Nombre</th>
      <th scope="col">Rol</th>
      <th scope="col"></th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
    <?php while($fila = mysqli_fetch_assoc($resultado_recibidas)): ?>
    <tr>
        <td><?php echo htmlspecialchars($fila['nombre_usuario']); ?></td>
        <td><?php echo htmlspecialchars($fila['nombre_rol']); ?></td>
        <td><a href="?accion=aceptar&id_solicitud=<?php echo $fila['id']; ?>" class="btn btn-sm btn-success">Aceptar</a>
        <a href="?accion=rechazar&id_solicitud=<?php echo $fila['id']; ?>" class="btn btn-sm btn-danger">Rechazar</a></td>
    </tr>
    <?php endwhile; ?>
    <?php if(mysqli_num_rows($resultado_recibidas) == 0): ?>
    <tr><td colspan="5" class="text-center">No hay solicitudes pendientes</td></tr>
    <?php endif; ?>
  </tbody>
</table>
</div>

    <a class="btn regresar" href="perfil.php">Regresar</a>

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