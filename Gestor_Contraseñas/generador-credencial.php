<?php
session_start();
require_once 'conexion.php';
$mensaje = "";

// Seguridad: Si intentan entrar directo a esta vista sin registrar antes un admin, se les devuelve al registro
if (!isset($_SESSION['temp_registro'])) {
    header("Location: registro.php");
    exit();
}

// 1. LÓGICA PARA GENERAR EL CÓDIGO (Llamada por el fetch en JS)
if (isset($_GET['action']) && $_GET['action'] === 'generar_credencial') {
    $archivo_registro = 'direcciones_generadas.txt';
    
    function generarParLetras() {
        $letras = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return $letras[rand(0, 25)] . $letras[rand(0, 25)];
    }

    function crearDireccionFormatada() {
        return generarParLetras() . '-' . generarParLetras() . '-' . generarParLetras() . '-' . generarParLetras();
    }

    $direcciones_existentes = [];
    if (file_exists($archivo_registro)) {
        $direcciones_existentes = file($archivo_registro, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }

    do {
        $direccion_actual = crearDireccionFormatada();
    } while (in_array($direccion_actual, $direcciones_existentes));

    file_put_contents($archivo_registro, $direccion_actual . PHP_EOL, FILE_APPEND);
    
    echo $direccion_actual;
    exit;
}

// 2. LÓGICA AL PRESIONAR EL BOTÓN "CONTINUAR" EN EL FORMULARIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $credencial_codigo = isset($_POST['credencial_codigo']) ? trim($_POST['credencial_codigo']) : '';

    if (!empty($credencial_codigo)) {
        // Recuperamos los datos del administrador guardados temporalmente en el paso anterior
        $datos = $_SESSION['temp_registro'];
        
        try {
            // A. Registrar la credencial generada
            $stmt_cred = mysqli_prepare($conexion, "INSERT INTO credenciales (codigo, tipo) VALUES (?, 'Administrador')");
            mysqli_stmt_bind_param($stmt_cred, "s", $credencial_codigo);
            mysqli_stmt_execute($stmt_cred);
            
            $credencial_admin_id = mysqli_insert_id($conexion);

            // B. Registrar el usuario enlazando la credencial recién creada
            $stmt = mysqli_prepare($conexion, "INSERT INTO usuarios (nombre_usuario, correo_electronico, contrasena, rol_id, grupo_id, credencial_admin_id) VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sssiii", $datos['usuario'], $datos['correo'], $datos['contrasena'], $datos['rol_id'], $datos['grupo_id'], $credencial_admin_id);
            
            if (mysqli_stmt_execute($stmt)) {
                $mensaje = "<div class='alert alert-success'>Administrador y credencial registrados exitosamente.</div>";
                // Limpiamos la sesión para que el proceso quede finalizado y evitar reenvíos
                unset($_SESSION['temp_registro']);
            }
        } catch (mysqli_sql_exception $e) {
            // Validamos por si ocurrió una casualidad donde alguien más registró el correo en ese lapso de segundos
            if ($e->getCode() == 1062) {
                $mensaje = "<div class='alert alert-danger'>El usuario o correo ya existen en la base de datos. Por favor, intenta el registro nuevamente.</div>";
            } else {
                $mensaje = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
            }
        }
    } else {
        $mensaje = "<div class='alert alert-danger'>Debe generar una credencial pulsando el botón antes de poder continuar.</div>";
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
          <button class="btn dropdown-toggle" data-bs-toggle="dropdown">grupos</button>
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

         <?php echo $mensaje; ?>

         <form method="POST" class="card-text">

          <div class="p-3 mb-3 generador">
              <label class="form-label"><strong>Generador De Credencial De Administrador:</strong></label><br>
              
              <input type="hidden" name="credencial_codigo" id="inputCredencial">
              
              <button type="button" class="btn generar-btn" id="btnGenerar">Generar Nueva Credencial</button>
              
              <div class="mt-2 fw-bold text-success" id="zonaMostrarCredencial" style="font-size: 1.2rem; min-height: 25px;">
                  Ninguna generada aún
              </div>
          </div>

          <?php if(isset($_SESSION['temp_registro'])): ?>
            <button class="btn continuar" type="submit">Continuar</button>
          <?php else: ?>
            <a class="btn continuar" href="inicio.php">Inicio</a>
          <?php endif; ?>
          <a class="btn cancelar" href="registro.php">Cancelar</a>
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


<script>
document.getElementById('btnGenerar').addEventListener('click', function() {
    // Atención: Ahora la petición se hace a este mismo archivo "generador-credencial.php"
    fetch('generador-credencial.php?action=generar_credencial')
        .then(response => response.text())
        .then(codigo => {
            // Inserta el código en el input oculto para el POST de PHP
            document.getElementById('inputCredencial').value = codigo;
            // Lo muestra visualmente abajo del botón
            document.getElementById('zonaMostrarCredencial').innerHTML = "Código: " + codigo;
        })
        .catch(error => {
            console.error('Error al generar:', error);
            document.getElementById('zonaMostrarCredencial').innerHTML = "<span class='text-danger'>Error al generar</span>";
        });
});
</script>

</body>
</html>