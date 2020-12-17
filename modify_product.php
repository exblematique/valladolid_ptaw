<?php
session_start();
require_once "config.php";
if(isset($_POST["id"])){
    $id = $_POST["id"];
    $_SESSION["id_product"] = $id;
    unset($_POST["id"]);
}
else $id = $_SESSION["id_product"];

$sql = "SELECT id, name, category, brand, color, price FROM products WHERE id = ".$id;
$req = mysqli_prepare($link, $sql);
mysqli_stmt_execute($req);
mysqli_stmt_bind_result($req, $col1,$col2,$col3,$col4,$col5,$col6);
mysqli_stmt_fetch ($req);
$product['id']=$col1;
$product['name']=$col2;
$product['category']=$col3;
$product['brand']=$col4;
$product['color']=$col5;
$product['price']=$col6;

mysqli_stmt_close($req);
// Update Product's details
if (isset($_POST["update_product"])&&isset($_POST['updated_name'])&&isset($_POST['updated_category'])&&isset($_POST['updated_brand'])&&isset($_POST['updated_color'])&&isset($_POST['updated_price']) && isset($_SESSION["loggedin"])){
    echo "UPDATE products SET name = '".$_POST['updated_name']."', category = '".$_POST['updated_category']."', brand = '".$_POST['updated_brand']."', color = '".$_POST['updated_color']."', price = ".$_POST['updated_price']." WHERE id = ".$id;
    $sql = "UPDATE products SET name = '".$_POST['updated_name']."', category = '".$_POST['updated_category']."', brand = '".$_POST['updated_brand']."', color = '".$_POST['updated_color']."', price = ".$_POST['updated_price']." WHERE id = ".$id;
    $req = mysqli_prepare($link, $sql);
    mysqli_stmt_execute($req);
    unset($_POST["update_product"]);
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
<h1>Product's details : </h1>
<p>Notice : You can edit the details in the table and confirm it by submitting them with the Update button.</p>
<form action="modify_product.php" method="post">
    <table>
        <tr>
            <th>ID</th>
            <th>Name : <?php echo json_encode($product['name']) ?></th>
            <th>Category : <?php echo json_encode($product['category']) ?></th>
            <th>Brand : <?php echo json_encode($product['brand']) ?></th>
            <th>Color : <?php echo json_encode($product['color']) ?></th>
            <th>Price : <?php echo json_encode($product['price']) ?></th>
        </tr>
        <tr>
            <?php echo '<td id="id">'.json_encode($product["id"]).' ?></td>';
            echo '<td id="name"><input type="text" name="updated_name" id="updated_name" value='.$product['name'].'></td>';
            echo '<td id="category"><input type="text" name="updated_category" id="updated_category" value='.$product['category'].'></td>';
            echo '<td id="brand"><input type="text" name="updated_brand" id="updated_brand" value='.$product['brand'].'></td>';
            echo '<td id="color"><input type="text" name="updated_color" id="updated_color" value='.$product['color'].'></td>';
            echo '<td id="price"><input type="text" name="updated_price" id="updated_price" value='.$product['price'].'></td>';?>
        </tr>
    </table>
    <input type="hidden" name="update_product" id="update_product" value="true"><br/>
    <input type="submit" name="update" id="update" value="Update Product's Details" onclick="return confirm('Are you sure you want to update product\'s details ?');">
</form>

<form action="admin.php?action=Products"  method="post">
    <input type="hidden" name="erase_product" id="erase_product" value="true"><br/>
    <input type="submit" name="delete" id="delete" value="Delete Product" onclick="return confirm('Warning : Are you sure you want to delete the product ?');">
</form>

</body>
</html>