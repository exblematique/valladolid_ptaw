<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$name = $mail = $password = $confirm_password = $address = $postal = $city = "";
$name_err = $mail_err = $password_err = $confirm_password_err = $address_err = $postal_err = $city_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate mail
    // First if it's not empty
    // Second if it's valid syntax
    if(empty(trim($_POST["mail"]))){
        $mail_err = "Por favor, introduzca un correo electrónico.";
    } elseif (!filter_var($_POST["mail"], FILTER_VALIDATE_EMAIL)){
        $mail_err = "Por favor, introduzca un correo con un formato correcto.";
    }
    else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE mail = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_mail);

            // Set parameters
            $param_mail = trim($_POST["mail"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $mail_err = "Este correo ya está ocupado.";
                } else{
                    $mail = trim($_POST["mail"]);
                }
            } else{
                echo "¡Uy! Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if(empty(trim($_POST["name"])))
        $name_err = "Por favor, introduzca un apellido y un nombre.";
    else
        $name = trim($_POST["name"]);

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor, introduzca una contraseña.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "La contraseña debe tener al menos 6 caracteres.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Por favor, confirme la contraseña.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "La contraseña no coincide.";
        }
    }

    // Validate address
    $address = trim($_POST["address"]);
    if(empty($address)){
        $confirm_address_err = "Por favor, introduzca una dirección.";
    }

    // Validate postal code
    $postal = trim($_POST["postal_code"]);
    if(empty($postal))
        $postal_err = "Por favor, introduzca una código postal.";
    else if (strlen($postal) != 5 or !is_numeric($postal))
        $postal_err = "Por favor, introduzca una código postal correcto.";
    else {
        $code = intval($postal);
        if ($code < 1000 or $code > 99999)
            $postal_err = "Por favor, introduzca una código postal correcto.";
    }

    // Validate city
    $city = trim($_POST["city"]);
    if(empty($city)){
        $city_err = "Por favor, introduzca una dirección.";
    }

    // Check input errors before inserting in database
    if(empty($mail_err) && empty($password_err) && empty($confirm_password_err) && empty($address_err) && empty($postal_err) && empty($city_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO users (name, mail, password, address, postal, city) VALUES (?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_name,$param_mail, $param_password, $param_address, $param_postal, $param_city);

            // Set parameters
            $param_name = $name;
            $param_mail = $mail;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_address = $address;
            $param_postal = $postal;
            $param_city = $city;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Algo salió mal. Por favor, inténtalo de nuevo más tarde.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inscríbete</title>
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
    <h2>Inscríbete</h2>
    <p>Por favor, rellene este formulario para crear una cuenta.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
            <label>Apellido y nombre</label>
            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
            <span class="help-block"><?php echo $name_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($mail_err)) ? 'has-error' : ''; ?>">
            <label>Dirección de correo</label>
            <input type="text" name="mail" class="form-control" value="<?php echo $mail; ?>">
            <span class="help-block"><?php echo $mail_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
            <label>Confirmar la contraseña</label>
            <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
            <span class="help-block"><?php echo $confirm_password_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
            <label>Dirección</label>
            <input type="text" name="address" class="form-control" value="<?php echo $address; ?>">
            <span class="help-block"><?php echo $address_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($postal_err)) ? 'has-error' : ''; ?>">
            <label>Código postal</label>
            <input type="text" name="postal_code" class="form-control" value="<?php echo $postal; ?>">
            <span class="help-block"><?php echo $postal_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($city_err)) ? 'has-error' : ''; ?>">
            <label>Ciudad</label>
            <input type="text" name="city" class="form-control" value="<?php echo $city; ?>">
            <span class="help-block"><?php echo $city_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="reset" class="btn btn-default" value="Reset">
        </div>
        <p>¿Ya tienes una cuenta? <a href="login.php">Entra aquí</a>.</p>
    </form>
</div>
</body>
</html>