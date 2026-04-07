<?php 
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: Login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel= "stylesheet" href="styles.css">
</head>
<body>
    <div class="main-container">
        <h1>¡Hola, <?php echo $_SESSION["usuario"] ?>!</h1>
        <p>Has iniciado sesión correctamente.</p>
        <br>
        <a href="perfil.php" class="btn btn-primary" style="text-decoration:none; display:block; margin-bottom:10px;">Ver mi Perfil</a>
        <a href="cerrar.php" class="btn-link" style="color:#ff6b6b;">Cerrar Sesión</a>
    </div>
</body>
</html>