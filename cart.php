<?php
// Check if the user is already logged in, if yes then redirect him to welcome page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";


// Create list of article selected by user
$cert = array();
// Download all products from database
if (!empty($_SESSION["cert"])) {
    $sql = "SELECT id, name, category, brand, color, price FROM products WHERE id IN (''";
    foreach ($_SESSION["cert"] as $key => $value) {
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
                    $cert[] = array(
                        'id' => $id,
                        'name' => $name,
                        'category' => $category,
                        'brand' => $brand,
                        'color' => $color,
                        'price' => $price,
                        'quantity' => $_SESSION["cert"][$id]
                    );
                }
            }
        } else {
            echo "¡Uy! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
        }
    }
}

/*/ Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate credentials
    if(empty($mail_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, mail, password FROM users WHERE mail = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $mail;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if mail exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $mail, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["mail"] = $mail;

                            // Redirect user to welcome page
                            header("location: index.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "La contraseña que introdujo no era válida.";
                        }
                    }
                } else{
                    // Display an error message if mail doesn't exist
                    $mail_err = "No se encontró ninguna cuenta con ese correo.";
                }
            } else{
                echo "¡Uy! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
    <script>
        // This function change an item in cart
        // If success reload the page
        function changeCart(id, newQuantity){
            $.post("addCart.php", {id: id, quantity: newQuantity}, function (){location.reload(true)});
        }
    </script>
</head>
<body>
<header>
    <a class="btn btn-primary" href="index.php">Inicio</a>
    <a class="btn btn-primary" href="search.php">Buscar</a>
    <a class="btn btn-primary" href="addCart.php">Cesta</a>
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
    if (empty($cert))
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
        for ($i=0; $i<count($cert); $i++){
    ?>
        <tr>
            <td><?php echo $cert[$i]['name'];?></td>
            <td><?php echo $cert[$i]['category'];?></td>
            <td><?php echo $cert[$i]['brand'];?></td>
            <td><?php echo $cert[$i]['color'];?></td>
            <td><?php echo $cert[$i]['price'];?></td>"
            <td><button class="btn btn-primary" onclick="changeCart(<?php echo $cert[$i]['id'];?>,-1)">-</button>
                <div class="btn btn-outline-dark"><?php echo $cert[$i]['quantity'];?></div>
                <button class="btn btn-primary" onclick="changeCart(<?php echo $cert[$i]['id'];?>,1)">+</button>
            </td>
        </tr>
    <?php } echo '</table>';}?>

    <h2>Listas de pedidos</h2>
</div>
</body>
</html>