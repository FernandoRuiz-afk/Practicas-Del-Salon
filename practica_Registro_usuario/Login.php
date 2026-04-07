<?php
session_start();
include("conexion.php");

$error_login = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_ingresado = trim($_POST['usuario']);
    $password_ingresada = $_POST['password'];

    if (empty($usuario_ingresado) || empty($password_ingresada)) {
        $error_login = "Por favor, complete todos los campos.";
    } else {

        $stmt = mysqli_prepare($conexion, "SELECT * FROM usuarios WHERE usuario = ?");
        mysqli_stmt_bind_param($stmt, "s", $usuario_ingresado);
        mysqli_stmt_execute($stmt);
        
        $resultado = mysqli_stmt_get_result($stmt);

        if ($fila = mysqli_fetch_assoc($resultado)) {
            
            if (password_verify($password_ingresada, $fila['password'])) {

                session_regenerate_id(true);

                $_SESSION["usuario"]  = $fila['usuario'];
                $_SESSION["nombre"]   = $fila['nombre'];
                $_SESSION["apellido"] = $fila['apellido'];
                $_SESSION["correo"]   = $fila['correo'];
                $_SESSION["telefono"] = $fila['telefono'];
                
                header("Location: inicio.php");
                exit();
            } else {
                $error_login = "Usuario o contraseña incorrectos.";
            }
        } else {
            $error_login = "Usuario o contraseña incorrectos.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesion</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="main-container">
        <h1>Iniciar sesión</h1>
        <?php if ($error_login !== "") echo "<p style='color:#ff6b6b; font-size:0.9rem; margin-bottom:10px;'>$error_login</p>"; ?>
        
        <form method="post" action="Login.php">
            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="usuario" required>
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Continuar</button>
            <a href="index.php" class="btn-link">Cancelar</a>
        </form>
        <p style="margin-top:20px; font-size:0.8rem;">¿No tienes cuenta? <a href="Registro.php" style="color:#00d2ff;">Regístrate aquí</a></p>
    </div>
</body>
</html>