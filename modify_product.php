<?php
$id = $_POST["id"];
$req = $link->prepare("SELECT id, name, category, brand, color, price FROM products WHERE id = ".$id);
$product = $req->execute();

// Delete a product
if (isset($_POST["erase"]) && isset($_SESSION["loggedin"])){
    $req = $link->prepare("DELETE FROM products WHERE id = ".$id);
    $req->execute();
    unset($_POST["erase"]);
}

// Update Product's details
if (isset($_POST["update_product"])&&isset($_POST['updated_name'])&&isset($_POST['updated_category'])&&isset($_POST['updated_brand'])&&isset($_POST['updated_color'])&&isset($_POST['updated_price']) && isset($_SESSION["loggedin"])){
    $req = $link->prepare("UPDATE products SET name = ".$_POST['updated_name'].", category = ".$_POST['updated_category'].", brand = ".$_POST['updated_brand'].", color = ".$_POST['updated_color'].", price = ".$_POST['updated_price']." WHERE id = ".$id);
    $req->execute();
    unset($_POST["update_product"]);
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
            <td id="id" contenteditable='false'><?php echo json_encode($product[id]) ?></td>
            <td id="name" contenteditable='true'><input type="hidden" name="updated_name" id="updated_name" value="<?php echo json_encode($product[name]) ?>"></td>
            <td id="category" contenteditable='true'><input type="hidden" name="updated_category" id="updated_category" value="<?php echo json_encode($product[category]) ?>"></td>
            <td id="brand" contenteditable='true'><input type="hidden" name="updated_brand" id="updated_brand" value="<?php echo json_encode($product[brand]) ?>"></td>
            <td id="color" contenteditable='true'><input type="hidden" name="updated_color" id="updated_color" value="<?php echo json_encode($product[color]) ?>"></td>
            <td id="price" contenteditable='true'><input type="hidden" name="updated_price" id="updated_price" value="<?php echo json_encode($product[price]) ?>"></td>
            <td id="created_at" contenteditable='false'><?php echo json_encode($product[created_at]) ?></td>
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