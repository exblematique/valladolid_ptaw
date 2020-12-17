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
    <a class="btn btn-primary" href="logout.php">Cerrar sesión</a>
    <a class="btn btn-primary" href="admin.php">Admin Home</a>
</header>

<?php
session_start();
require_once "config.php";
if(!isset($_REQUEST['action']))
    $action = 'Home';
else
    $action = $_REQUEST['action'];
switch($action)
{
    case 'Home':
    {?>
        <button href="admin.php?action=Clients" class="btn btn-default navbar-btn ">Access and modify details of Clients</button>
        <br/><br/>
        <button href="admin.php?action=Products" class="btn btn-default navbar-btn ">Access, add and modify details of Products</button>
        <?php break;
    }
    case 'Clients' :
    {include("clients.php");break;}
    case 'Products' :
    {include("products.php");break;}
}
?>
</body>
</html>
