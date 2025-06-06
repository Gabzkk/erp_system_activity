<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('db.php');

// Get product ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<p class='error'>ERROR: No product ID provided.</p>");
}

$product_id = $_GET['id'];

// Delete product from database
$delete_query = "DELETE FROM products WHERE id = '$product_id'";
if (mysqli_query($conn, $delete_query)) {
    header("Location: products.php"); // Redirect after successful deletion
    exit();
} else {
    die("<p class='error'>ERROR: Failed to delete product.</p>");
}
?>