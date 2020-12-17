<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        header{display: flex;}
    </style>
</head>
<body>
<header>
    <a class="btn btn-primary" href="index.php">Inicio</a>
    <a class="btn btn-primary" href="search.php">Buscar</a>
    <a class="btn btn-primary" href="cart.php">Cesta</a>
    <?php // Change the link if user is connected or not
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){?>
        <a class="btn btn-primary" href="logout.php">Cerrar sesión</a>
    <?php
        if (isset($_SESSION["loggedin"]) && $_SESSION["admin"] === true){?>
            <a class="btn btn-primary" href="admin.php">Admin Home</a>
    <?php}
    } else {?>
        <a class="btn btn-primary" href="login.php">Initiar sesión</a>
    <?php }?>
</header>
<p>Bienvenido a nuestro nuevo sitio web de comercio electrónico.</p>
<p>No dudes en ir a las diferentes pestañas en la parte superior de la página.</p>
</body>
</html>