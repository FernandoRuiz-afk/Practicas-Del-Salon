<?php
session_start();
$mensaje_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'conexion.php';

    $nombre = trim($_POST['usuario']);
    $pass = trim($_POST['contrasena']);

    if (!empty($nombre) && !empty($pass)) {

        $stmt = mysqli_prepare($conexion, "SELECT * FROM usuarios WHERE nombre_usuario = ?");
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $nombre);
            mysqli_stmt_execute($stmt);
            
            $resultado = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($resultado);

            if ($user && password_verify($pass, $user['contrasena'])) {

                // Guardamos los datos en la sesión
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['nombre_usuario'] = $user['nombre_usuario'];
                $_SESSION['rol_id'] = $user['rol_id'];
                
                // === REDIRECCIÓN DINÁMICA SEGÚN EL ROL ===
                if ($user['rol_id'] == 1) {
                    // Rol 1: Administrador
                    header("Location: inicio.php");
                } elseif ($user['rol_id'] == 2) {
                    // Rol 2: Líder de grupo
                    header("Location: vistas-lider/inicio-lider.php");
                } elseif ($user['rol_id'] == 3) {
                    // Rol 3: Empleado
                    header("Location: vistas-usuario/inicio-usuario.php");
                } else {
                    // Por seguridad, si el rol no es válido, lo devuelve al index
                    header("Location: index.php");
                }
                exit();

            } else {
                $mensaje_error = "<div class='alert alert-danger'>Usuario o contraseña incorrectos.</div>";
            }
        } else {
            $mensaje_error = "<div class='alert alert-danger'>Error en el sistema al procesar la solicitud.</div>";
        }
    } else {
        $mensaje_error = "<div class='alert alert-danger'>Por favor, llena todos los campos.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="estilos/styles-login.css">
    <title>Inicio de sesion</title>
</head>
<body>

<nav class="navbar">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <img src="imagenes\Sombrero2-logo.png" alt="HatStall" class="d-inline-block align-text-top">
      HatStall Security
    </a>
  </div>
</nav>

<div class="card border-0">
  <div class="row g-0">
    <div class="col-7">
      <div class="card-body text-center">
        <h1 class="card-title">Iniciar Sesión</h1>

         <?php echo $mensaje_error; ?>

         <form action="login.php" method="POST" class="card-text">
          <label> Nombre de Usuario:</label> <br>
          <input type="text" name="usuario" required> <br>
          
          <label>Contraseña:</label> <br>
          <input type="password" name="contrasena" required> <br>
          
          <button class="btn" type="submit">Continuar</button>
          <a class="btn" href="index.php">Cancelar</a>
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

</body>
</html>