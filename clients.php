<?php
// Request details to the database and stores it in differents variables
    $sql_search = "SELECT id, name, mail, address, postal, city, created_at FROM users";
    $stmt_search = mysqli_prepare($link, $sql_search);
    try { mysqli_stmt_execute($stmt_search); mysqli_stmt_store_result($stmt_search);}
    catch (Exception $e) {echo "something went wrong : ",  $e->getMessage(), "\n";}
    $users = $stmt_search->fetchAll();
    for ($i=0; $i<count($users); $i++) {
        $id[$i] = $users[$i]['id'];
        $name[$i] = $users[$i]['name'];
        $mail[$i] = $users[$i]['mail'];
        $address[$i] = $users[$i]['address'];
        $postal[$i] = $users[$i]['postal'];
        $city[$i] = $users[$i]['city'];
        $created_at[$i] = $users[$i]['created_at'];
    }
// If a client has been added, send the details to the database
if (isset($_POST['name'])&&isset($_POST['mail'])&&isset($_POST['password'])&&isset($_POST['address'])&&isset($_POST['postal'])&&isset($_POST['city'])){
    $req = $link->prepare('INSERT INTO users(name,mail,password,address,postal,city) VALUES(?,?,?,?,?,?)');
    $req->execute(array($_POST['name'],$_POST['mail'],password_hash($_POST['password'], PASSWORD_DEFAULT),$_POST['address'],$_POST['postal'],$_POST['city']));

    header('/admin.php?action=Clients');
}

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
        <p> OU </p>
        <button id="create_client">Create a new client</button>
        <p id="detail_client"></p>
        <br/>
    </body>

    <p id="demo"></p>
    <script>
        let ids = <?php echo json_encode($id) ?>;
        let names = <?php echo json_encode($name) ?>;
        let mails = <?php echo $mail ?>;
        let addresses = <?php echo json_encode($address) ?>;
        let postals = <?php echo json_encode($postal) ?>;
        let citys = <?php echo json_encode($city) ?>;
        let select = document.getElementById ("list_clients");

        for (let i =0; i<ids.length; i++){
            select.options[select.options.length] = new Option ("Client ID n°"+ids[i]+" : "+name, ids[i]);
        }

        select.onchange = function() {
            let line_selected = select.selectedIndex;
            let idSpot = select.value;
            let name_client = select.options[select.selectedIndex].text.substr(7);

            document.getElementById("demo").innerHTML = idSpot + " / " + nom_spot; // .options[liste.selectedIndex].text

            //Dynamic creation of the form
            let form = document.createElement('form');
            let name = document.createElement('input');
            let mail = document.createElement('input');
            let address = document.createElement('input');
            let postal = document.createElement('input');
            let city = document.createElement('input');
            let url = window.location.search;
            form.method = "post";
            form.action = "/modify_client.php";
            //Add details of the client in hidden parameters
            name.type = "hidden";
            name.name = "name";
            name.id = "name";
            name.value = names[line_selected];
            mail.type = "hidden";
            mail.id = "mail";
            mail.name = "mail";
            mail.value = mails[line_selected];
            address.type = "hidden";
            address.id = "address";
            address.name = "address";
            address.value = addresses[line_selected];
            postal.type = "hidden";
            postal.id = "postal";
            postal.name = "postal";
            postal.value = postals[line_selected];
            city.type = "hidden";
            city.id = "city";
            city.name = "city";
            city.value = citys[line_selected];
            form.appendChild(name);
            form.appendChild(mail);
            form.appendChild(address);
            form.appendChild(postal);
            form.appendChild(city);
            //add the form to tha page and submit it
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
            name.type = "hidden";
            name.name = "name";
            name.id = "name";
            name.required = true;
            name.placeholder = "Name";
            mail.type = "hidden";
            mail.id = "mail";
            mail.name = "mail";
            mail.required = true;
            mail.placeholder = "Mail";
            password.type = "hidden";
            password.id = "password";
            password.name = "password";
            password.required = true;
            password.placeholder = "Password";
            address.type = "hidden";
            address.id = "address";
            address.name = "address";
            address.required = true;
            address.placeholder = "Address";
            postal.type = "hidden";
            postal.id = "postal";
            postal.name = "postal";
            postal.required = true;
            postal.placeholder = "ZIP Code";
            city.type = "hidden";
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
            detail.appendChild(form);

        }
    </script>
</html>