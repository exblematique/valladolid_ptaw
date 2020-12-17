<?php
session_start();
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Check if "id" and "quantity" are not empty and are numbers
    if (isset($_POST["id"]) and is_numeric($_POST["id"]) and isset($_POST["quantity"]) and is_numeric($_POST["quantity"])){
        $id = intval($_POST["id"]);
        $quantity = intval($_POST["quantity"]);

        // If array doesn't exist, create it
        if (!isset($_SESSION['cart']))
            $_SESSION['cart'] = array();

        // If element is not in cart add it
        // Else add with the current quantity
        if (!isset($_SESSION['cart'][$id]))
            $_SESSION['cart'][$id] = $quantity;
        else
            $_SESSION['cart'][$id] += $quantity;

        // Cart cannot be negative or null
        // Remove element if it is the case
        if ($_SESSION['cart'][$id] <= 0)
            unset($_SESSION['cart'][$id]);
    }
}