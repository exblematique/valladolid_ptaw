<?php
// Initialize the session
session_start();

// Include config file
require_once "config.php";

// Initialize variables
$id = $name = $category = $brand = $color = $price = "";

// Prepare request SQL
// Return the demanding list for POST method, else return all value
$sql = "SELECT id, name, category, brand, color, price FROM products";
if($_SERVER["REQUEST_METHOD"] == "GET") {
    // Check if not a advance search
    if (isset($_GET["search"])) {
        // This way enable to check all words in all SQL column
        $sql .= " WHERE name REGEXP ? or category REGEXP ? or brand REGEXP ? or color REGEXP ?";
        // Create a regex sytaxe with "OR"
        $input = str_replace('\n', '|', $_GET["search"]);

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $input, $input, $input, $input);
        }
    }
    // If it a advance search, adding condition only if needed
    else {
        $nbItem = 0;
        if (isset($_GET["name"]) and !empty($_GET["name"]))
            $sql .= " ";
    }


    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Store result
        mysqli_stmt_store_result($stmt);

        // Check if not empty, then create HTML array
        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Bind result variables
            mysqli_stmt_bind_result($stmt, $id, $name, $category, $brand, $color, $price);
            $html_array = '';
            while (mysqli_stmt_fetch($stmt)) {
                $html_array .= "<tr><td>$name</td><td>$category</td><td>$brand</td><td>$color</td><td>$price</td></tr>";
            }
        } else {
            // Display an error message if username doesn't exist
            $username_err = "No account found with that username.";
        }
    } else
        echo "Oops! Something went wrong. Please try again later.";

    // Close statement
    mysqli_stmt_close($stmt);
}

// Close connection
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
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
    <h2>Buscar un artículo</h2>
    <h3>Búsqueda general</h3>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
        <div class="form-group">
            <label>Búsqueda</label>
            <input type="text" name="search" class="form-control">
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Búsqueda">
        </div>
    </form>

    <h3>Búsqueda avanzada</h3>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="name" class="form-control">
        </div>
        <div class="form-group">
            <label>Categoría</label>
            <input type="text" name="category" class="form-control">
        </div>
        <div class="form-group">
            <label>Color</label>
            <input type="text" name="color" class="form-control">
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Búsqueda">
        </div>
    </form>

    <h2>Lista de artículos en la tienda</h2>
    <table class="table">
        <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Categoría</th>
            <th scope="col">Marca</th>
            <th scope="col">Color</th>
            <th scope="col">Precio</th>
        </tr>
        <?php echo $html_array; ?>
    </table>
</div>
</body>
</html>