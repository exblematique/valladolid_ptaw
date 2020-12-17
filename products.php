<?php
session_start();
require_once "config.php";

// Delete a product
if (isset($_POST["erase_product"]) && isset($_SESSION["loggedin"])){
    $sql = "DELETE FROM products WHERE id = ".$_SESSION["id_product"];
    $req = mysqli_prepare($link, $sql);
    mysqli_stmt_execute($req);
    unset($_POST["erase_product"]);
}
mysqli_stmt_close($req);

// Request details of products to the database and stores it in differents variables
$sql_search = "SELECT id, name, category, brand, color, price FROM products";
$stmt_search = mysqli_prepare($link, $sql_search);
try { mysqli_stmt_execute($stmt_search); mysqli_stmt_bind_result($stmt_search, $col1,$col2,$col3,$col4,$col5,$col6);}
catch (Exception $e) {echo "something went wrong : ",  $e->getMessage(), "\n";}
echo $col1.$col2;
$id = array();
$name = array();
$category = array();
$brand = array();
$color = array();
$price = array();
$i=0;
while (mysqli_stmt_fetch ($stmt_search)){
    $id[$i] = $col1;
    $name[$i] = $col2;
    $category[$i] = $col3;
    $brand[$i] = $col4;
    $color[$i] = $col5;
    $price[$i] = $col6;
    $i++;
}
mysqli_stmt_close($stmt_search);

// If a product has been added, send the details to the database
if (isset($_POST['name'])&&isset($_POST['category'])&&isset($_POST['brand'])&&isset($_POST['color'])&&isset($_POST['price'])){
    $sql = 'INSERT INTO products(name,category,brand,color,price) VALUES (?,?,?,?,?)';
    $req = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($req, "s", $_POST['name'],$_POST['category'],$_POST['brand'],$_POST['color'],$_POST['price']);
    mysqli_stmt_execute($req);
    header('/admin.php?action=Products');
}

mysqli_stmt_close($req);
mysqli_close($link);
?>

<!doctype html>
<html>
<head>
    <title>List of Products' informations</title>
    <meta charset="UTF-8" />
    <style>
        .blocktext {
            margin-left: auto;
            margin-right: auto;
            width: 6em
        }
    </style>
</head>
<body>
<h1>Search a product in the database : </h1><br/>
<label for="list_products">Choose a product to modify his details :</label>
<select name="products" id="list_products">
    <option value="">--Select a product in the list--</option>
</select>
<p> Or :</p>
<button id="create_product">Create a new product</button>
<p id="detail_product"></p>
<br/>
</body>

<p id="demo"></p>
<script>
    let ids = <?php echo json_encode($id) ?>;
    let names = <?php echo json_encode($name) ?>;
    let categories = <?php echo json_encode($category) ?>;
    let brands = <?php echo json_encode($brand) ?>;
    let colors = <?php echo json_encode($color) ?>;
    let prices = <?php echo json_encode($price) ?>;
    let select = document.getElementById ("list_products");

    for (let i =0; i<ids.length; i++){
        select.options[select.options.length] = new Option ("Category : "+categories[i]+", Brand : "+brands[i]+", Name : "+names[i]+", Price : "+prices[i], ids[i]);
    }

    select.onchange = function() {
        //Dynamic creation of the form
        let id = document.createElement('input');
        let form = document.createElement('form');
        form.method = "post";
        form.action = "/modify_product.php";
        id.type = "hidden";
        id.name = "id";
        id.id = "id";
        id.value = select.value;
        form.appendChild(id);
        //add the form to the page and submit it
        document.body.appendChild(form);
        form.submit();
    }
    document.getElementById("create_product").onclick = function() {

        let url = window.location.search;
        let detail = document.getElementById('detail_product');
        let form = document.createElement('form');
        let name = document.createElement('input');
        let category = document.createElement('input');
        let brand = document.createElement('input');
        let color = document.createElement('input');
        let price = document.createElement('input');
        let validate = document.createElement('input');
        form.id = "create_client";
        form.action = url;
        form.method = "post";
        name.type = "text";
        name.name = "name";
        name.id = "name";
        name.required = true;
        name.placeholder = "Name";
        category.type = "text";
        category.id = "category";
        category.name = "category";
        category.required = true;
        category.placeholder = "Category";
        brand.type = "text";
        brand.id = "brand";
        brand.name = "brand";
        brand.required = true;
        brand.placeholder = "Brand";
        color.type = "text";
        color.id = "color";
        color.name = "color";
        color.required = true;
        color.placeholder = "Color";
        price.type = "text";
        price.id = "price";
        price.name = "price";
        price.required = true;
        price.placeholder = "Price";
        validate.id = "btn_validate";
        validate.type = "submit";
        validate.value = "Create Product";

        form.appendChild(name);
        form.appendChild(category);
        form.appendChild(brand);
        form.appendChild(color);
        form.appendChild(price);
        form.appendChild(validate);
        detail.appendChild(form);

    }
</script>
</html>
