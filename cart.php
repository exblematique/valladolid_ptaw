<?php
session_start();
// Check if the user is already logged in, if yes then redirect him to welcome page
if(!isset($_SESSION["loggedin"]) or $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables
$date_err = "";
// Calcul dateDeliveryMin with remove saturday, sunday and add 2 days;
$currentDay = getdate()['wday'];
// If sunday add 3 days
if ($currentDay == 0)
    $dateDeliveryMin = mktime(0,0,0,date("m"),date("d")+3,date("Y"));
// For weekend
elseif ($currentDay >= 4)
    $dateDeliveryMin = mktime(0,0,0,date("m"),date("d")+4,date("Y"));
else
    $dateDeliveryMin = mktime(0,0,0,date("m"),date("d")+2,date("Y"));

// Create list of article selected by user
$cart = array();
// Download all products from database
if (!empty($_SESSION["cart"])) {
    $sql = "SELECT id, name, category, brand, color, price FROM products WHERE id IN (''";
    foreach ($_SESSION["cart"] as $key => $value) {
        if (!empty($key) and !empty($value))
            $sql .= ",$key";
    }
    $sql .= ')';

    if ($stmt = mysqli_prepare($link, $sql)) {
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            // Check if there are a result
            if (mysqli_stmt_num_rows($stmt) != 0) {
                mysqli_stmt_bind_result($stmt, $id, $name, $category, $brand, $color, $price);
                while (mysqli_stmt_fetch($stmt)) {
                    $cart[] = array(
                        'id' => $id,
                        'name' => $name,
                        'category' => $category,
                        'brand' => $brand,
                        'color' => $color,
                        'price' => $price,
                        'quantity' => $_SESSION["cart"][$id]
                    );
                }
            }
        } else {
            echo "¡Uy! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
        }
    }
    mysqli_stmt_close($stmt);
}

// List of previous delivery
$idUser = $_SESSION["id"];
$previousOrder = "";
$sql = "SELECT order, delivery_date INTO orders WHERE id_user = $idUser";
// Add products in order
if ($stmt = mysqli_prepare($link, $sql)) {
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        // Check if there are a result
        if (mysqli_stmt_num_rows($stmt) != 0) {
            mysqli_stmt_bind_result($stmt, $orderNb, $deliveryDate);
            while (mysqli_stmt_fetch($stmt))
                $previousOrder .= "<tr><td>$orderNb</td><td>$deliveryDate</td></tr>";
        }
    }
    else
        echo "¡Uy! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
    mysqli_stmt_close($stmt);
}




// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['delivery']))
        $date_err = "La fecha de entrega no está definida.";
    elseif (strlen($date_input = trim($_POST['delivery'])) != 10)
        $date_err = "La fecha de entrega no está en el formato correcto.";
    elseif (count($date_input = explode("-", $date_input)) != 3)
        $date_err = "La fecha de entrega no está en el formato correcto.";
    elseif (!checkdate($date_input[1], $date_input[2], $date_input[0]))
        $date_err = "La fecha de entrega no está correcto.";
    // If the date from input is before minimal date
    elseif ($dateDeliveryMin > ($date_input = mktime(0,0,0,$date_input[1],$date_input[2],$date_input[0])))
        $date_err = "La fecha de entrega debe ser posterior a " . $dateDeliveryMin;
    // If the date is the weekend
    elseif (date('N', $date_input) >= 6)
        $date_err = "La cita no puede ser durante el fin de semana.";

    // Validate credentials
    if(empty($date_err)){
        // Create order
        $idUser = $_SESSION["id"];
        $dateOfDelivery = trim($_POST['delivery']);
        $sql = "INSERT INTO orders (id_user, delivery_date) VALUES ($idUser, DATE('$dateOfDelivery')); SELECT MAX(id) FROM orders WHERE id_user=$idUser";

        if ($stmt = mysqli_prepare($link, $sql)) {
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                // Check if there are a result
                if (mysqli_stmt_num_rows($stmt) != 0) {
                    mysqli_stmt_bind_result($stmt, $idOrder);
                    if (!mysqli_stmt_fetch($stmt))
                        echo "¡Uy! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
                    else {
                        $sql = "";
                        // Add products of order in DB
                        for ($i=0; $i<count($cart); $i++) {
                            $idProduct = $cart[$i]['id'];
                            $quantity = $cart[$i]['quantity'];
                            $sql .= "INSERT INTO orders_products (id_order, id_product, quantity) VALUES ($idOrder, $idProduct, $quantity);";
                        }
                        // Add products in order
                        if ($stmt2 = mysqli_prepare($link, $sql)) {
                            if (mysqli_stmt_execute($stmt2)) {
                                mysqli_stmt_store_result($stmt2);
                                while (mysqli_stmt_fetch($stmt2))
                                    continue;
                            }
                            else
                                echo "¡Uy! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
                            mysqli_stmt_close($stmt2);
                        }

                    }
                }
            } else
                echo "¡Uy! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
            mysqli_stmt_close($stmt);
        }
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
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){?>
        <a class="btn btn-primary" href="logout.php">Cerrar sesión</a>
    <?php } else {?>
        <a class="btn btn-primary" href="login.php">Initiar sesión</a>
    <?php } ?>
</header>
<div class="wrapper">
    <h2>Cesta</h2>
    <?php
    // Create list of panier
    if (empty($cart))
        echo "Su cesta está vacía";
    else {
    ?> <table class="table">
        <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Categoría</th>
            <th scope="col">Marca</th>
            <th scope="col">Color</th>
            <th scope="col">Precio por unidad</th>
            <th scope="col">Cantidad</th>
            <th scope="col">Precio total</th>
        </tr>
    <?
        $totalPrice = 0;
        for ($i=0; $i<count($cart); $i++){
    ?>
        <tr>
            <td><?php echo $cart[$i]['name'];?></td>
            <td><?php echo $cart[$i]['category'];?></td>
            <td><?php echo $cart[$i]['brand'];?></td>
            <td><?php echo $cart[$i]['color'];?></td>
            <td><?php echo $cart[$i]['price'];?></td>
            <td>
                <button class="btn btn-primary" onclick="changeCart(<?php echo $cart[$i]['id'];?>,-1)">-</button>
                <div class="btn btn-outline-dark"><?php echo $cart[$i]['quantity'];?></div>
                <button class="btn btn-primary" onclick="changeCart(<?php echo $cart[$i]['id'];?>,1)">+</button>
            </td>
            <td>
                <?php
                    $price = intval($cart[$i]['price'])*intval($cart[$i]['quantity']);
                    $totalPrice += $price;
                    echo $price;
                ?>
            </td>
        </tr>
    <?php }
        echo "</table><p>El precio total es: <b>$totalPrice</b></p>";
        echo "<p>Por favor, elija una fecha de entrega: <input type='date' name='delivery' value='$dateDeliveryMin' min='$dateDeliveryMin'></p>";
        echo "<p>¡Cuidado! La entrega toma dos días de trabajo.</p>";
        $thisPage = htmlspecialchars($_SERVER["PHP_SELF"]);
        echo "<form action='$thisPage' method='post'>";
        echo "<button type='submit' class='btn btn-primary'>Haga clic aquí para completar el pedido</button>";
        echo "</form>";
    }?>

    <h2>Listas de pedidos</h2>
    <table class="table">
        <tr>
            <th scope="col">Número de orden</th>
            <th scope="col">Fecha de entrega</th>
        </tr>
        <?php if (!empty($previousOrder)) echo $previousOrder; ?>
    </table>
        <?php if (empty($previousOrder)) echo "<p>Aún no ha hecho un pedido.</p>" ?>
</div>
</body>
</html>