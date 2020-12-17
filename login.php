<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$mail = $password = "";
$mail_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if mail is empty
    if(empty(trim($_POST["mail"]))){
        $mail_err = "Por favor, introduzca un correo electrónico..";
    } else{
        $mail = trim($_POST["mail"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor, introduzca una contraseña.";
    } else{
        $password = trim($_POST["password"]);
    }

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

                            // Verify if the connected user is an admin and store his state in session variable
                            $sql_admin = "SELECT id FROM admin WHERE id_user = ?";
                            $stmt_admin = mysqli_prepare($link, $sql_admin);
                            mysqli_stmt_bind_param($stmt_admin, "s", $i);
                            try { mysqli_stmt_execute($stmt_admin); mysqli_stmt_store_result($stmt_admin);}
                            catch (Exception $e) {echo "something went wrong : ",  $e->getMessage(), "\n";}
                            $_SESSION["admin"] = (mysqli_stmt_num_rows($stmt_admin) >= 1);

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
}
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
    <h2>Inicio</h2>
    <p>Por favor, rellene sus credenciales para entrar.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($mail_err)) ? 'has-error' : ''; ?>">
            <label>Dirección de correo</label>
            <input type="text" name="mail" class="form-control" value="<?php echo $mail; ?>">
            <span class="help-block"><?php echo $mail_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control">
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Login">
        </div>
        <p>¿No tienes una cuenta? <a href="register.php">Inscríbete ahora</a>.</p>
    </form>
</div>
</body>
</html>