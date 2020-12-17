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
        <a href="admin.php?action=Clients" type="button" class="btn btn-default navbar-btn ">Access and modify details of Clients</a> <br/>
        <a href="admin.php?action=Products" type="button" class="btn btn-default navbar-btn ">Access, add and modify details of Products</a>
        <?php break;
    }
    case 'Clients' :
    {include("clients.php");break;}
    case 'Products' :
    {include("products.php");break;}
}
?>

