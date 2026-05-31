<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../estilos/styles-inicio.css">
    <title>HatStall Security</title>
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
      <div class="card-body text-center">
        <h2 class="card-title">Bienvenido a HatStall Security</h2>
        <p class="card-text">Nos encargamos de resguardar tu seguridad. <br> <br> ¡Ya puedes solicitar las credenciales que necesites y puedes ver el grupo al que perteneces y quien es el lider de tu grupo!</p>
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