<?php
session_start();
require_once 'conexion.php';
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);
    $correo = trim($_POST['correo']);
    $rol_id = intval($_POST['rol']);
    $grupo_id = ($_POST['grupo'] === 'NULL' || $rol_id == 1) ? NULL : intval($_POST['grupo']);
    $pass1 = $_POST['contrasena'];
    $pass2 = $_POST['confirmar_contrasena'];

    if (!empty($usuario) && !empty($correo) && !empty($pass1) && !empty($pass2)) {
        if ($pass1 === $pass2) {
            
            $hash_password = password_hash($pass1, PASSWORD_DEFAULT);

            // Si es administrador, guardamos los datos temporalmente y redirigimos
            if ($rol_id == 1) {
                $_SESSION['temp_registro'] = [
                    'usuario' => $usuario,
                    'correo' => $correo,
                    'contrasena' => $hash_password,
                    'rol_id' => $rol_id,
                    'grupo_id' => $grupo_id
                ];
                header("Location: generador-credencial.php");
                exit();
            } else {
                // Si es empleado (3) o líder (2), lo registramos directamente
                try {
                    $stmt = mysqli_prepare($conexion, "INSERT INTO usuarios (nombre_usuario, correo_electronico, contrasena, rol_id, grupo_id) VALUES (?, ?, ?, ?, ?)");
                    mysqli_stmt_bind_param($stmt, "sssii", $usuario, $correo, $hash_password, $rol_id, $grupo_id);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        $mensaje = "<div class='alert alert-success'>Usuario registrado con éxito.</div>";
                    }
                } catch (mysqli_sql_exception $e) {
                    if ($e->getCode() == 1062) {
                        $mensaje = "<div class='alert alert-danger'>El nombre de usuario o el correo ya están registrados.</div>";
                    } else {
                        $mensaje = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
                    }
                }
            }
        } else {
            $mensaje = "<div class='alert alert-danger'>Las contraseñas no coinciden.</div>";
        }
    } else {
        $mensaje = "<div class='alert alert-danger'>Todos los campos son obligatorios.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos/styles-registro.css">
    <title>Registrar usuario</title>
</head>
<body>

<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="inicio.php"><img src="imagenes\Sombrero2-logo.png" alt="HatStall" class="d-inline-block align-text-top"></a>
    <h1><a class="navbar-brand" href="inicio.php">HatStall Security</a></h1>

    <div class="collapse navbar-collapse" id="navbarNav">

      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="usuarios_Lista.php">usuarios</a></li>

        <li class="nav-item dropdown">
          <button class="btn dropdown-toggle" data-bs-toggle="dropdown">Grupos</button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="grupo1.php">Grupo 1</a></li>
            <li><a class="dropdown-item" href="grupo2.php">Grupo 2</a></li>
          </ul>
        </li>
        
        <li class="nav-item"><a class="nav-link" href="credenciales.php">credenciales</a></li>

        <li class="nav-item">
          <a class="nav-link" href="registro.php">agg usuario</a>
        </li>
        
        <li class="nav-item"><a class="nav-link" href="perfil.php">ver perfil</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="card border-0">
  <div class="row g-0">
    <div class="col">
      <div class="card-body">
        <h2 class="card-title">Registrar Nuevo Usuario</h2>
        
         <?php echo $mensaje; ?>

         <form method="POST" class="card-text">

          <label> Nombre de Usuario:</label> <br>
          <input type="text" name="usuario" required> <br>

          <label> Correo Electrónico:</label> <br>
          <input type="email" name="correo" required> <br>

          <div class="row">
              <div class="col ">
                  <label>Rol:</label><br>
                  <select class="form-select" name="rol" id="rolSelect">
                    <option value="3">Empleado</option>
                    <option value="2">Lider de grupo</option>
                    <option value="1">Administrador</option>
                  </select>
              </div>
              <div class="col grupo">
                  <label>Grupo de trabajo:</label><br>
                  <select class="form-select" name="grupo" id="grupoSelect">
                    <option value="NULL">Ninguno (Admin)</option>
                    <option value="1">Grupo 1</option>
                    <option value="2">Grupo 2</option>
                </select>
              </div>
          </div>
          <br>

          <label>Contraseña:</label> <br>
          <input type="password" name="contrasena" required> <br>

          <label>Confirmar contraseña:</label> <br>
          <input type="password" name="confirmar_contrasena" required> <br>
          <br>

          <button class="btn continuar" type="submit">Continuar</button>
          <a class="btn cancelar" href="inicio.php">Cancelar</a>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>