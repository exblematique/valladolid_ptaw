<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        header{display: flex;}
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
<header>
    <a class="button" href="index.php">Inicio</a>
    <a class="button" href="search.php">Buscar</a>
    <a class="button" href="cart.php">Cesta</a>
    <?php // Change the link if user is connected or not
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){?>
        <a class="button" href="logout.php">Cerrar sesión</a>
    <?php } else {?>
        <a class="button" href="login.php">Initiar sesión</a>
    <?php } ?>
</header>
<h1>Bienvenido a nuestro nuevo sitio web de comercio electrónico.</h1>
<h1>No dudes en ir a las diferentes pestañas en la parte superior de la página.</h1>
</body>
</html>