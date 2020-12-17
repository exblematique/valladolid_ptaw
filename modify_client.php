<?php
session_start();
require_once "config.php";
if(isset($_POST["id"])){
    $id = $_POST["id"];
    $_SESSION["id_client"] = $id;
    unset($_POST["id"]);
}
else $id = $_SESSION["id_client"];

$sql = "SELECT id, name, mail, address, postal, city, created_at FROM users WHERE id = ".$id;
$req = mysqli_prepare($link, $sql);
mysqli_stmt_execute($req);
mysqli_stmt_bind_result($req, $col1,$col2,$col3,$col4,$col5,$col6,$col7);
mysqli_stmt_fetch ($req);
$client['id']=$col1;
$client['name']=$col2;
$client['mail']=$col3;
$client['address']=$col4;
$client['postal']=$col5;
$client['city']=$col6;
$client['created_at']=$col7;

mysqli_stmt_close($req);
// Update Client's details
if (isset($_POST["update_client"])&&isset($_POST['updated_name'])&&isset($_POST['updated_mail'])&&isset($_POST['updated_password'])&&isset($_POST['updated_address'])&&isset($_POST['updated_postal'])&&isset($_POST['updated_city']) && isset($_SESSION["loggedin"])){
    $sql = "UPDATE users SET name = '".$_POST['updated_name']."', mail = '".$_POST['updated_mail']."', address = '".$_POST['updated_address']."', postal = '".$_POST['updated_postal']."', city = '".$_POST['updated_city']."' WHERE id = ".$id;
    $req = mysqli_prepare($link, $sql);
    mysqli_stmt_execute($req);
    unset($_POST["update_client"]);
    mysqli_stmt_close($req);
}
mysqli_close($link);
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style>
        table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
        }

        td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
        }
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

<h1>Client's details : </h1>
<p>Notice : You can edit the details in the table and confirm it by submitting them with the Update button.</p>
<form action="modify_client.php" method="post">
<table>
    <tr>
        <th>ID</th>
        <th>Name : <?php echo json_encode($client['name']) ?></th>
        <th>Mail : <?php echo json_encode($client['mail']) ?></th>
        <th>Address : <?php echo json_encode($client['address']) ?></th>
        <th>Postal : <?php echo json_encode($client['postal']) ?></th>
        <th>City : <?php echo json_encode($client['city']) ?></th>
        <th>Created at</th>
    </tr>
    <tr>
        <td id="id" contenteditable='false'><?php echo json_encode($client['id']) ?></td>
        <td id="name" contenteditable='true'><input type="text" name="updated_name" id="updated_name" value=<?php echo json_encode($client['name']) ?>></td>
        <td id="mail" contenteditable='true'><input type="text" name="updated_mail" id="updated_mail" value=<?php echo json_encode($client['mail']) ?>></td>
        <td id="address" contenteditable='true'><input type="text" name="updated_address" id="updated_address" value=<?php echo json_encode($client['address']) ?>></td>
        <td id="postal" contenteditable='true'><input type="text" name="updated_postal" id="updated_postal" value=<?php echo json_encode($client['postal']) ?>></td>
        <td id="city" contenteditable='true'><input type="text" name="updated_city" id="updated_city" value=<?php echo json_encode($client['city']) ?>></td>
        <td id="created_at" contenteditable='false'><?php echo json_encode($client['created_at']) ?></td>
    </tr>
</table>
    <input type="hidden" name="update_client" id="update_client" value="true"><br/>
    <input type="submit" name="update" id="update" value="Update Client's Details" onclick="return confirm('Are you sure you want to update client\'s details ?');">
</form>

<form action="admin.php?action=Clients" method="post">
    <input type="hidden" name="erase_client" id="erase_client" value="true"><br/>
    <input type="submit" name="delete" id="delete" value="Delete Client" onclick="return confirm('Warning : Are you sure you want to delete the client ?');">
</form>

</body>
</html>