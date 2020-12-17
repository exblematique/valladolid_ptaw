<?php
session_start();
require_once "config.php";
$id = $_POST["id"];
$req = $link->prepare("SELECT id, name, mail, address, postal, city, created_at FROM users WHERE id = ".$id);
$client = $req->execute();

// Delete a user
if (isset($_POST["erase"]) && isset($_SESSION["loggedin"])){
    $req = $link->prepare("DELETE FROM users WHERE id = ".$id);
    $req->execute();
    unset($_POST["erase"]);
}

// Update Client's details
if (isset($_POST["update_client"])&&isset($_POST['updated_name'])&&isset($_POST['updated_mail'])&&isset($_POST['updated_password'])&&isset($_POST['updated_address'])&&isset($_POST['updated_postal'])&&isset($_POST['updated_city']) && isset($_SESSION["loggedin"])){
    $req = $link->prepare("UPDATE users SET name = ".$_POST['updated_name'].", mail = ".$_POST['updated_mail'].", address = ".$_POST['updated_address'].", postal = ".$_POST['updated_postal'].", city = ".$_POST['updated_city']." WHERE id = ".$id);
    $req->execute();
    unset($_POST["update_client"]);
}
?>
<!doctype html>
<html>
<head>
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
    </style>
</head>
<body>
<h1>Client's details : </h1>
<p>Notice : You can edit the details in the table and confirm it by submitting them with the Update button.</p>
<form action="modify_client.php" method="post">
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Mail</th>
        <th>Address</th>
        <th>Postal</th>
        <th>City</th>
        <th>Created at</th>
    </tr>
    <tr>
        <td id="id" contenteditable='false'><?php echo json_encode($client[id]) ?></td>
        <td id="name" contenteditable='true'><input type="hidden" name="updated_name" id="updated_name" value="<?php echo json_encode($client[name]) ?>"></td>
        <td id="mail" contenteditable='true'><input type="hidden" name="updated_mail" id="updated_mail" value="<?php echo json_encode($client[mail]) ?>"></td>
        <td id="address" contenteditable='true'><input type="hidden" name="updated_address" id="updated_address" value="<?php echo json_encode($client[address]) ?>"></td>
        <td id="postal" contenteditable='true'><input type="hidden" name="updated_postal" id="updated_postal" value="<?php echo json_encode($client[postal]) ?>"></td>
        <td id="city" contenteditable='true'><input type="hidden" name="updated_city" id="updated_city" value="<?php echo json_encode($client[city]) ?>"></td>
        <td id="created_at" contenteditable='false'><?php echo json_encode($client[created_at]) ?></td>
    </tr>
</table>
    <input type="hidden" name="update_client" id="update_client" value="true">
    <input type="submit" name="update" id="update" value="Update Client's Details" onclick="return confirm('Are you sure you want to update client\'s details ?');">
</form>

<form>
    <input type="hidden" name="erase" id="erase" value="true">
    <input type="submit" name="delete" id="delete" value="Delete Client" onclick="return confirm('Warning : Are you sure you want to delete the client ?');">
</form>

</body>
</html>