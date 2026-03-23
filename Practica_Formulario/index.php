<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href= "styles.css">
        <title>Formulario</title>
    </head>
    <body>
        <h1 style="text-align: center;">Formulario de datos</h1>
        <form type= "formulario" method="POST">
            <label class="titulos"> nombre </label>
            <input type= "text" name= "nombre" pattern="[A-Za-záéíóúñÁÉÍÓÚÑ\s]+" required> <br>
            <label class="titulos"> apellido </label>
            <input type= "text" name= "apellido" pattern= "[A-Za-záéíóúñÁÉÍÓÚÑ\s]+" required> <br>
            <label class="titulos"> direccion </label>
            <input type= "text" name= "direccion" required> <br>
            <label class="titulos"> numero de telefono </label>
            <input type= "tel" name= "numero de telefono" pattern="[0-9]+" required> <br>
            <label class="titulos"> correo </label>
            <input type= "email" name= "correo" required> <br>
            <button type = "submit"> Continuar </button>
        </form>

        <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['numero_de_telefono'];
    $correo = $_POST['correo'];

   echo "<div class='resultado'>";
    echo "<h2>Datos Recibidos:</h2>";
    echo "<p><strong>Nombre:</strong> $nombre</p>";
    echo "<p><strong>Apellido:</strong> $apellido</p>";
    echo "<p><strong>Dirección:</strong> $direccion</p>";
    echo "<p><strong>Teléfono:</strong> $telefono</p>";
    echo "<p><strong>Correo:</strong> $correo</p>";
    echo "</div>";
}
?>

<label class="Estudiantes">Fernando Ruiz. C.I: 31.083.595 <br> Claudia Colorado. C.I: 31036412</label>

    </body>
</html>