<?php
session_start(); 
include("conexion.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario  = trim($_POST['usuario']);
    $nombre   = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $correo   = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);
    $password = $_POST['password'];
    $conf_password = $_POST['conf_password'];

    if (empty($usuario) || empty($nombre) || empty($apellido) || empty($correo) || empty($telefono) || empty($password)) {
        $mensaje = "Error: Todos los campos son obligatorios.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Error: El formato del correo electrónico no es válido.";
    } elseif (strlen($password) < 8) {
        $mensaje = "Error: La contraseña debe tener al menos 8 caracteres.";
    } elseif ($password !== $conf_password) {
        $mensaje = "Error: Las contraseñas no coinciden.";
    } else {
        
        $stmt_check = mysqli_prepare($conexion, "SELECT id FROM usuarios WHERE usuario = ? OR correo = ?");
        mysqli_stmt_bind_param($stmt_check, "ss", $usuario, $correo);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $mensaje = "Error: El nombre de usuario o correo ya están registrados.";
        } else {

            $pass_encriptada = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt_insert = mysqli_prepare($conexion, "INSERT INTO usuarios (usuario, nombre, apellido, correo, telefono, password) VALUES (?, ?, ?, ?, ?, ?)");

            mysqli_stmt_bind_param($stmt_insert, "ssssss", $usuario, $nombre, $apellido, $correo, $telefono, $pass_encriptada);
            
            if (mysqli_stmt_execute($stmt_insert)) {

                $_SESSION["usuario"]  = $usuario;
                $_SESSION["nombre"]   = $nombre;
                $_SESSION["apellido"] = $apellido;
                $_SESSION["correo"]   = $correo;
                $_SESSION["telefono"] = $telefono;

                $mensaje = "Usuario registrado exitosamente. <a href='Login.php'>Ir al Login</a>";
            } else {
                $mensaje = "Error técnico al registrar el usuario. Intente más tarde.";
            }
            mysqli_stmt_close($stmt_insert);
        }
        mysqli_stmt_close($stmt_check);
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="main-container">
    <h1 class="form-title">Registro de Usuario</h1>
    
    <form action="registro.php" method="post">
        <div class="form-group">
            <label>Usuario</label>
            <input type="text" name="usuario" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" required>
            </div>
            <div class="form-group">
                <label>Apellido</label>
                <input type="text" name="apellido" required>
            </div>
        </div>

        <div class="form-group">
            <label>Número de teléfono</label>
            <input type="tel" name="telefono" required>
        </div>

        <div class="form-group">
            <label>Correo Electrónico</label>
            <input type="email" name="correo" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Confirmar</label>
                <input type="password" name="conf_password" required>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Crear Cuenta</button>
        <a href="index.php" class="btn-link">Cancelar</a>
    </form>
</div>
</body>
</html>