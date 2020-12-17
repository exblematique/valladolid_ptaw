<?php
session_start();
require_once "config.php";
$id = $_POST["id"];
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

// Delete a product
if (isset($_POST["erase"]) && isset($_SESSION["loggedin"])){
    $sql = "DELETE FROM products WHERE id = ".$id;
    $req = mysqli_prepare($link, $sql);
    mysqli_stmt_execute($req);
    unset($_POST["erase"]);
}

// Update Product's details
if (isset($_POST["update_product"])&&isset($_POST['updated_name'])&&isset($_POST['updated_category'])&&isset($_POST['updated_brand'])&&isset($_POST['updated_color'])&&isset($_POST['updated_price']) && isset($_SESSION["loggedin"])){
    $sql = "UPDATE products SET name = ".$_POST['updated_name'].", category = ".$_POST['updated_category'].", brand = ".$_POST['updated_brand'].", color = ".$_POST['updated_color'].", price = ".$_POST['updated_price']." WHERE id = ".$id;
    $req = mysqli_prepare($link, $sql);
    mysqli_stmt_execute($req);
    unset($_POST["update_product"]);
}
mysqli_stmt_close($req);
mysqli_close($link);
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
<form action="modify_product.php" method="post">
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Brand</th>
            <th>Color</th>
            <th>Price</th>
        </tr>
        <tr>
            <td id="id"><?php echo json_encode($product['id']) ?></td>
            <td id="name"><input type="text" name="updated_name" id="updated_name" placeholder="<?php echo json_encode($product['name']) ?>"></td>
            <td id="category"><input type="text" name="updated_category" id="updated_category" placeholder="<?php echo json_encode($product['category']) ?>"></td>
            <td id="brand"><input type="text" name="updated_brand" id="updated_brand" placeholder="<?php echo json_encode($product['brand']) ?>"></td>
            <td id="color"><input type="text" name="updated_color" id="updated_color" placeholder="<?php echo json_encode($product['color']) ?>"></td>
            <td id="price"><input type="text" name="updated_price" id="updated_price" placeholder="<?php echo json_encode($product['price']) ?>"></td>
            <td id="created_at"><?php echo json_encode($product['created_at']) ?></td>
        </tr>
    </table>
    <input type="hidden" name="update_product" id="update_product" value="true">
    <input type="submit" name="update" id="update" value="Update Product's Details" onclick="return confirm('Are you sure you want to update product\'s details ?');">
</form>

<form>
    <input type="hidden" name="erase" id="erase" value="true">
    <input type="submit" name="delete" id="delete" value="Delete Product" onclick="return confirm('Warning : Are you sure you want to delete the product ?');">
</form>

</body>
</html>