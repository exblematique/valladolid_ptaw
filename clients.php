<?php
session_start();
require_once "config.php";

// Delete a user
if (isset($_POST["erase_client"]) && isset($_SESSION["loggedin"])){
    $sql = "DELETE FROM users WHERE id = ".$_SESSION["id_client"];
    $req = mysqli_prepare($link, $sql);
    mysqli_stmt_execute($req);
    unset($_POST["erase_client"]);
}
mysqli_stmt_close($req);

// Request details to the database and stores it in differents variables
    $sql_search = "SELECT id, name, mail, address, postal, city, created_at FROM users";
    $stmt_search = mysqli_prepare($link, $sql_search);
    try { mysqli_stmt_execute($stmt_search); mysqli_stmt_bind_result($stmt_search, $col1,$col2,$col3,$col4,$col5,$col6,$col7);}
    catch (Exception $e) {echo "something went wrong : ",  $e->getMessage(), "\n";}
    $i=0;
while (mysqli_stmt_fetch ($stmt_search)) {
    $id[$i] = $col1;
    $name[$i] = $col2;
    $mail[$i] = $col3;
    $address[$i] = $col4;
    $postal[$i] = $col5;
    $city[$i] = $col6;
    $created_at[$i] = $col7;
    $i++;
}
mysqli_stmt_close($stmt_search);

// If a client has been added, send the details to the database
if (isset($_POST['name'])&&isset($_POST['mail'])&&isset($_POST['password'])&&isset($_POST['address'])&&isset($_POST['postal'])&&isset($_POST['city'])){
    $sql = 'INSERT INTO users(name,mail,password,address,postal,city) VALUES (?,?,?,?,?,?)';
    $req = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($req, "s", $_POST['name'],$_POST['mail'],password_hash($_POST['password'], PASSWORD_DEFAULT),$_POST['address'],$_POST['postal'],$_POST['city']);
    mysqli_stmt_execute($req);
    header('/admin.php?action=Clients');
}
mysqli_stmt_close($req);

?>

<!doctype html>
<html>
    <head>
        <title>List of Clients' informations</title>
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
        <h1>Search a client in the database : </h1><br/>
        <label for="list_clients">Choose a client to modify his details :</label>
        <select name="clients" id="list_clients">
            <option value="">--Select a client in the list--</option>
        </select>
        <p> Or : </p>
        <button id="create_client">Create a new client</button>
        <p id="detail_client"></p>
        <br/>
    </body>

    <p id="demo"></p>
    <script>
        let ids = <?php echo json_encode($id) ?>;
        let names = <?php echo json_encode($name) ?>;
        let mails = <?php echo json_encode($mail) ?>;
        let addresses = <?php echo json_encode($address) ?>;
        let postals = <?php echo json_encode($postal) ?>;
        let citys = <?php echo json_encode($city) ?>;
        let select = document.getElementById ("list_clients");

        for (let i =0; i<ids.length; i++){
            select.options[select.options.length] = new Option ("Client ID n°"+ids[i]+" : "+names[i], ids[i]);
        }

        select.onchange = function() {
            //Dynamic creation of the form
            let id = document.createElement('input');
            let form = document.createElement('form');
            form.method = "post";
            form.action = "/modify_client.php";
            id.type = "hidden";
            id.name = "id";
            id.id = "id";
            id.value = select.value;
            form.appendChild(id);
            //add the form to the page and submit it
            document.body.appendChild(form);
            form.submit();
        }
        document.getElementById("create_client").onclick = function() {

            let url = window.location.search;
            let detail = document.getElementById('detail_client');
            let form = document.createElement('form');
            let name = document.createElement('input');
            let mail = document.createElement('input');
            let password = document.createElement('input');
            let address = document.createElement('input');
            let postal = document.createElement('input');
            let city = document.createElement('input');
            let validate = document.createElement('input');
            form.id = "create_client";
            form.action = url;
            form.method = "post";
            name.type = "text";
            name.name = "name";
            name.id = "name";
            name.required = true;
            name.placeholder = "Name";
            mail.type = "text";
            mail.id = "mail";
            mail.name = "mail";
            mail.required = true;
            mail.placeholder = "Mail";
            password.type = "text";
            password.id = "password";
            password.name = "password";
            password.required = true;
            password.placeholder = "Password";
            address.type = "text";
            address.id = "address";
            address.name = "address";
            address.required = true;
            address.placeholder = "Address";
            postal.type = "text";
            postal.id = "postal";
            postal.name = "postal";
            postal.required = true;
            postal.placeholder = "ZIP Code";
            city.type = "text";
            city.id = "title";
            city.name = "title";
            city.required = true;
            city.placeholder = "City";
            validate.id = "btn_validate";
            validate.type = "submit";
            validate.value = "Create Client";

            form.appendChild(name);
            form.appendChild(mail);
            form.appendChild(password);
            form.appendChild(address);
            form.appendChild(postal);
            form.appendChild(city);
            form.appendChild(validate);
            detail.appendChild(form);

        }
    </script>
</html>