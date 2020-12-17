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
        <button href="admin.php?action=Clients" class="btn btn-default navbar-btn ">Access and modify details of Clients</button> <br/>
        <button href="admin.php?action=Products" class="btn btn-default navbar-btn ">Access, add and modify details of Products</button>
        <?php break;
    }
    case 'Clients' :
    {include("clients.php");break;}
    case 'Products' :
    {include("products.php");break;}
}
?>

