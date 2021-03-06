<?php
session_start();

// Define variables
$error = "";
$order = "";
$debug = false;
$debugOutput = "";

// Check if the user is already logged in, if yes then redirect him to welcome page
if(!isset($_SESSION["loggedin"]) or $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

if (empty($_GET["id"]) or !is_numeric($_GET["id"]))
    $error = "El número de orden es inválido";
elseif (($orderId = intval($_GET["id"])) <= 0)
    $error = "El número de orden es inválido";

// Include config file
require_once "config.php";

// Create list of article selected by user
$cart = array();

// Check if the order exist and belongs to user
if ($debug) $debugOutput .= "<br>Value of orderId : $orderId";
if (empty($error)) {
    $sql = "SELECT id, id_user, created_at, delivery_date FROM orders WHERE id = $orderId";
    if ($debug) $debugOutput .= "<br>First SQL command : $sql";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        //mysqli_stmt_bind_param($stmt, "s", $orderId);
        if ($debug) $debugOutput .= "<br>Enter to mysqli_prepare";
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            // Check if there are a result
            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $orderId, $userid, $orderDate, $deliveryDate);
                mysqli_stmt_fetch($stmt);
                $sessionId = $_SESSION['id'];
                if ($debug) $debugOutput .= "<br>User id : .$userid. and Session id : .$sessionId.";
                if ($userid == $sessionId){
                    $sql = "SELECT name, category, brand, color, price, orders_products.quantity FROM products ";
                    $sql .= "INNER JOIN orders_products ON products.id=orders_products.id_product ";
                    $sql .= "WHERE id_order = $orderId";
                    if ($debug) $debugOutput .= "<br>Second SQL command : $sql";
                    // Add products in order
                    if ($stmt2 = mysqli_prepare($link, $sql)) {
                        if($debug) $debugOutput .= "<br>List order";
                        if (mysqli_stmt_execute($stmt2)) {
                            mysqli_stmt_store_result($stmt2);

                            // Check if there are a result
                            if (mysqli_stmt_num_rows($stmt2) != 0) {
                                mysqli_stmt_bind_result($stmt2, $product, $category, $brand, $color, $price, $quantity);
                                $totalPrice = 0;
                                while (mysqli_stmt_fetch($stmt2)) {
                                    $totalPriceItem = intval($quantity)*intval($price);
                                    $totalPrice += $totalPriceItem;
                                    $order .= "<tr><td>$product</td><td>$category</td><td>$brand</td><td>$color</td><td>$price</td><td>$quantity</td><td>$totalPriceItem</td></tr>";
                               }
                            }
                            else $error = "La orden no pudo ser recuperada.";
                        }
                        else
                            echo "¡Uy! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
                        mysqli_stmt_close($stmt2);
                    }
                } else
                    $error = "La orden no es suya.";
            } else
                $error = "La orden no fue encontrada";
        } else {
            $error = "¡Uy! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
        }
        mysqli_stmt_close($stmt);
    }
}


// Close connection
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ padding: 20px; }
    </style>
    <script>
        // This function change an item in cart
        // If success reload the page
        function changeCart(id, addQuantity){
            $.post("addCart.php", {id: id, quantity: addQuantity}, function (){location.reload(true)});
        }
    </script>
</head>
<body>
<header>
    <a class="btn btn-primary" href="index.php">Inicio</a>
    <a class="btn btn-primary" href="search.php">Buscar</a>
    <a class="btn btn-primary" href="cart.php">Cesta</a>
    <?php // Change the link if user is connected or not
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        echo '<a class="btn btn-primary" href="logout.php">Cerrar sesión</a>';
        if ($_SESSION["admin"] === true)
            echo '<a class="btn btn-primary" href="admin.php">Admin Home</a>';
    } else
        echo '<a class="btn btn-primary" href="login.php">Initiar sesión</a>';
    ?>
</header>
<div class="wrapper">
    <h2>Cesta</h2>
    <?php
    // Create list of panier
    if ($debug)
        echo "<p>$debugOutput</p>";
    if (empty($order))
        echo "<p>$error</p>";
    else {
        echo '<table class="table"><tr>';
        echo '<th scope="col">Nombre</th>';
        echo '<th scope="col">Categoría</th>';
        echo '<th scope="col">Marca</th>';
        echo '<th scope="col">Color</th>';
        echo '<th scope="col">Precio por unidad</th>';
        echo '<th scope="col">Cantidad</th>';
        echo '<th scope="col">Precio total</th></tr>';
        echo $order;
        echo "</table><p>El precio total es: <b>$totalPrice</b></p>";
        $date = date("Y-m-d H:i:s");
        echo "<p>Fecha actual: <input type='text' name='delivery' value='$date' disabled></p>";
        echo "<p>Fecha de orden: <input type='text' name='delivery' value='$orderDate' disabled></p>";
        echo "<p>Fecha de entrega: <input type='text' name='delivery' value='$deliveryDate' disabled></p>";
    }?>

</div>
</body>
</html>