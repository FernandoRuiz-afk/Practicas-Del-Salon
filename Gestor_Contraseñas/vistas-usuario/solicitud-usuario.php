<?php
session_start();
require_once '../conexion.php';
$mensaje = "";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$mi_id = $_SESSION['usuario_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_destino = trim($_POST['usuario']);
    $rol_destino = intval($_POST['rol']);

    $stmt = mysqli_prepare($conexion, "SELECT id FROM usuarios WHERE nombre_usuario = ? AND rol_id = ?");
    mysqli_stmt_bind_param($stmt, "si", $usuario_destino, $rol_destino);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    
    if ($fila = mysqli_fetch_assoc($resultado)) {
        $destinatario_id = $fila['id'];

        $stmt_insert = mysqli_prepare($conexion, "INSERT INTO solicitudes (remitente_id, destinatario_id) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt_insert, "ii", $mi_id, $destinatario_id);
        
        if (mysqli_stmt_execute($stmt_insert)) {
            $mensaje = "<div class='alert alert-success'>Solicitud enviada a $usuario_destino exitosamente.</div>";
        } else {
            $mensaje = "<div class='alert alert-danger'>Error al enviar la solicitud.</div>";
        }
    } else {
        $mensaje = "<div class='alert alert-danger'>Usuario no encontrado o no tiene el rol seleccionado.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../estilos/styles-solicitud.css">
    <title>Enviar Solicitud</title>
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

<div class="card border-0">
  <div class="row g-0">
    <div class="col">
      <div class="card-body">
        <h2 class="card-title">Enviar Solicitud de Credencial</h2>
        
         <?php echo $mensaje; ?>

         <form method="POST" class="card-text">

          <label>Nombre de usuario a solicitar:</label> <br>
          <input type="text" name="usuario" required> <br>

          <div class="row">
              <div class="col ">
                  <label>Solicitar Credencial a:</label><br>
                  <select class="form-select" name="rol" id="rolSelect">
                    <option value="2">Lider de grupo</option>
                    <option value="1">Administrador</option>
                  </select>
              </div>
            </div>

          <button class="btn enviar" type="submit">Enviar</button>
          <a class="btn cancelar" href="inicio-lider.php">Cancelar</a>
        </form>
      </div>
    </div>
  </div>
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
