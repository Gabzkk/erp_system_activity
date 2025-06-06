<?php
include('db.php');

if (isset($_POST['add_product'])) {
    $name = $_POST['product_name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock_quantity'];

    $query = "INSERT INTO products (product_name, category, price, stock_quantity) VALUES ('$name', '$category', '$price', '$stock')";

    if (mysqli_query($conn, $query)) {
        echo "PRODUCT ADDED SUCCESSFULLY!";
        header("Location: products.php");
        exit();
    } else {
        die("ERROR: SQL QUERY FAILED! " . mysqli_error($conn));
    }
}
?>