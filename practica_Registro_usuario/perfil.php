<?php 
session_start();
include("conexion.php");

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
        <div class="profile-img">👤</div>
        <h1>Mi Perfil</h1>
        
        <div style="text-align: left; background: rgba(255,255,255,0.05); padding: 15px; border-radius: 10px; margin-bottom: 20px;">
            <p style="margin-bottom: 8px;"><strong>Usuario:</strong> <?php echo htmlspecialchars($_SESSION["usuario"]); ?></p>
            <p style="margin-bottom: 8px;"><strong>Nombre:</strong> <?php echo htmlspecialchars($_SESSION["nombre"] . " " . $_SESSION["apellido"]); ?></p>
            <p style="margin-bottom: 8px;"><strong>Correo:</strong> <?php echo htmlspecialchars($_SESSION["correo"]); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($_SESSION["telefono"]); ?></p>
        </div>

        <a href="inicio.php" class="btn btn-primary" style="text-decoration:none; display:block;">Regresar</a>
    </div>
</body>
</html>